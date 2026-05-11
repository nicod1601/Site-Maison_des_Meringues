# 🍬 La Maison des Meringues

Site web artisanal pour la vente en ligne de meringues — boutique, gestion des produits, système de panier et authentification client.

---

## 📋 Description

**La Maison des Meringues** est une application web Laravel développée pour une boutique artisanale normande. Elle permet :

- D'afficher une boutique en ligne avec les produits organisés par **rayons** et **thèmes**
- De gérer les produits, formes, parfums, conditionnements et prix via une interface d'administration
- D'importer des produits en masse via un fichier **Excel / CSV**
- D'organiser les produits selon des **events** (Noël, Anniversaire, Printemps…)
- D'ajouter des produits au **panier** sans être connecté (session)
- De créer un **compte client** et de passer commande
- De séparer les rôles **admin** et **client**

---

## 🛠 Stack technique

| Technologie | Version |
|-------------|---------|
| PHP | ^8.2 |
| Laravel | ^12.0 |
| Node.js | ^20.19 ou ≥22.12 |
| PostgreSQL | 15+ |
| Vite | ^7.0 |
| Maatwebsite/Excel | ^3.1 |
| Laravel Breeze | ^2.4 |
| CSS | Fait main + assistance IA |

---

## 📁 Structure du projet

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AccueilController.php       # Page d'accueil
│   │   ├── ShopController.php          # Boutique (rayons, thèmes, produits)
│   │   ├── GestionController.php       # Interface d'administration
│   │   ├── CreationController.php      # CRUD produits, rayons, events…
│   │   ├── ImportController.php        # Import Excel/CSV
│   │   ├── PanierController.php        # Gestion du panier
│   │   └── Auth/                       # Controllers Breeze (login, register…)
│   └── Middleware/
│       └── IsAdmin.php                 # Protection routes admin
├── Models/
│   ├── Produit.php
│   ├── Forme.php / Forme_Condi.php
│   ├── Parfum.php
│   ├── Conditionnement.php
│   ├── Rayon.php
│   ├── Theme.php
│   ├── Event.php
│   ├── Image.php
│   ├── Boutique.php
│   ├── Panier.php                      # Panier (session ou compte)
│   ├── PanierLigne.php                 # Lignes du panier
│   └── User.php                        # Utilisateur (role: admin/client)
├── Listeners/
│   └── TransfererPanierApresLogin.php  # Fusion panier session → compte
└── Imports/
    └── UsersImport.php                 # Logique d'import Excel
resources/
├── views/
│   ├── index.blade.php                 # Accueil
│   ├── shop.blade.php                  # Boutique
│   ├── panier.blade.php                # Panier client
│   ├── gestion.blade.php              # Administration
│   ├── auth/
│   │   ├── login.blade.php            # Connexion (design personnalisé)
│   │   └── register.blade.php         # Inscription (design personnalisé)
│   └── templet/
│       ├── header.blade.php
│       └── footer.blade.php
├── css/
│   ├── style.css                       # Design system global
│   ├── boutique.css                    # Styles boutique
│   ├── gestion.css                     # Styles administration
│   ├── login.css                       # Styles login/register
│   └── index.css                       # Styles accueil
public/
└── fichier/image/meringues/
    ├── Mini/
    │   ├── sachet_de_10/
    │   └── individuelle/
    └── Nid/
        ├── sachet_de_4/
        ├── boite_de_8/
        └── individuelle/
```

---

## 🗄 Modèle de données

```
users
    ├── role (admin / client)
    └── panier (via user_id)

boutique
    └── rayon (many)
            └── produit (many-to-many via produit_rayon)

produit
    ├── forme → forme_condi → conditionnement
    ├── parfum
    ├── theme (optionnel — tri visuel)
    ├── events (many-to-many via produit_event)
    └── images (par forme_condi)

rayon
    └── events (many-to-many via rayon_event)

panier
    ├── user_id (nullable — null = visiteur anonyme)
    ├── session_id (pour les visiteurs)
    └── panier_ligne (many)
            ├── id_produit
            ├── id_forme_condi
            ├── quantite
            └── prix_unitaire

image
    ├── id_produit
    ├── id_forme_condi
    └── url  (ex: fichier/image/meringues/Mini/sachet_de_10/Ananas.jpg)
```

---

## ⚙️ Installation

### 1. Cloner le projet

```bash
git clone https://github.com/votre-repo/maison-des-meringues.git
cd maison-des-meringues
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Modifier `.env` avec vos paramètres PostgreSQL :

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=meringue
DB_USERNAME=votre_user
DB_PASSWORD=votre_password

CACHE_STORE=file
SESSION_DRIVER=file
```

### 4. Migrations et seeders

```bash
php artisan migrate
php artisan db:seed
```

Les seeders créent :
- Les **formes** (Mini, Nid)
- Les **conditionnements** (sachet_de_4, boite_de_8, sachet_de_10, individuelle, vrac)
- Les **prix** (forme_condi)
- Les **parfums** de base
- Les **thèmes** (Fleurs, Fruits, Desserts, Epices, Saveur-Normandi)
- Les **events** (Printemps, Noël, Anniversaire)
- La **boutique** et le **rayon Base**

### 5. Lancer l'application

```bash
npm run dev
php artisan serve
```

### 6. Créer le premier compte admin

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'votre@email.com')->first();
$user->role = 'admin';
$user->save();
```

---

## 👤 Système d'authentification

- **Inscription** : `/register` — compte créé avec rôle `client` par défaut
- **Connexion** : `/login` — redirige vers l'accueil après connexion
- **Déconnexion** : bouton dans le menu navbar (formulaire POST)
- **Rôles** : `admin` (accès gestion) / `client` (accès boutique + panier)

---

## 🛒 Système de panier

- Panier accessible **sans compte** (stocké en session)
- Au moment du checkout → **connexion obligatoire**
- Après connexion → panier de session **fusionné** avec le compte
- Un article du panier = `Produit` + `Forme_Condi` (ex: Mini Framboise en sachet de 10)

---

## 📦 Import des produits

L'interface `/gestion` permet d'importer les produits via un fichier `.xlsx` ou `.csv`.

### Format du fichier Excel attendu

| Colonne | Contenu | Exemple |
|---------|---------|---------|
| A | Nom produit | `NANA` |
| B | Forme | `Nid` |
| C | Parfum | `Ananas` |
| D | Description | `Meringue artisanale...` |
| E | Quantité | `50` |
| F | Nouveauté | `oui` / `non` |
| G | Live | `oui` / `non` |
| H | Thème | `Fruits` |
| I | Events (virgule) | `Noël,Anniversaire` |
| J | Spécial | `oui` / `non` |

> La première ligne est ignorée (en-tête).

---

## 🖼 Images des produits

Les images doivent être placées dans `public/fichier/image/meringues/` selon la structure :

```
Mini/
  sachet_de_10/Ananas.jpg
  individuel/Ananas.jpg
Nid/
  sachet_de_4/Ananas.jpg
  boite_de_8/Ananas.jpg
  individuel/Ananas.jpg
```

---

## 🛍 Boutique

URL : `/shop/1`

- Navigation par **rayons** (visible uniquement si `live_rayon = true`)
- Produits organisés par **thèmes**
- Bouton **Ajouter au panier** selon disponibilité du stock
- Badges : Nouveau, Épuisé, Emporter, Expédition

---

## ⚙️ Interface de gestion

URL : `/gestion` — **accès admin uniquement**

Onglets disponibles :
- **Produits** — liste avec toggles Live, Emporter, Expédition, Nouveauté
- **Rayons** — création, association aux events
- **Formes** — Mini, Nid, etc.
- **Conditionnements** — sachet, boite, individuelle…
- **Parfums** — liste des saveurs
- **Thèmes** — avec icône et couleur
- **Events** — occasions spéciales
- **Prix** — combinaisons forme × conditionnement

---

## 🚀 Déploiement

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 👤 Auteur

Projet développé pour **La Maison des Meringues** — Auberge d'Ypreville, Normandie.
