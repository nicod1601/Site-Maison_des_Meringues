# 🍬 La Maison des Meringues

Site web artisanal pour la vente en ligne de meringues — boutique, gestion des produits et importation via fichier Excel.

---

## 📋 Description

**La Maison des Meringues** est une application web Laravel développée pour une boutique artisanale normande. Elle permet :

- D'afficher une boutique en ligne avec les produits organisés par **rayons** et **thèmes**
- De gérer les produits, formes, parfums, conditionnements et prix via une interface d'administration
- D'importer des produits en masse via un fichier **Excel / CSV**
- D'organiser les produits selon des **events** (Noël, Anniversaire, Printemps…)

---

## 🛠 Stack technique

| Technologie | Version |
|-------------|---------|
| PHP | 8.2+ |
| Laravel | 11.x |
| PostgreSQL | 15+ |
| Vite | 5.x |
| Maatwebsite/Excel | 3.x |

---

## 📁 Structure du projet

```
app/
├── Http/Controllers/
│   ├── AccueilController.php       # Page d'accueil
│   ├── ShopController.php          # Boutique (rayons, thèmes, produits)
│   ├── GestionController.php       # Interface d'administration
│   ├── CreationController.php      # CRUD produits, rayons, events…
│   └── ImportController.php        # Import Excel/CSV
├── Models/
│   ├── Produit.php
│   ├── Forme.php / Forme_Condi.php
│   ├── Parfum.php
│   ├── Conditionnement.php
│   ├── Rayon.php
│   ├── Theme.php
│   ├── Event.php
│   ├── Image.php
│   └── Boutique.php
├── Imports/
│   └── UsersImport.php             # Logique d'import Excel
resources/
├── views/
│   ├── index.blade.php             # Accueil
│   ├── shop.blade.php              # Boutique
│   ├── gestion.blade.php           # Administration
│   └── templet/
│       ├── header.blade.php
│       └── footer.blade.php
├── css/
│   ├── style.css                   # Design system global
│   ├── boutique.css                # Styles boutique
│   ├── gestion.css                 # Styles administration
│   └── index.css                   # Styles accueil
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

### Logique d'import

- Les produits sont **créés ou mis à jour** (`updateOrCreate`) selon nom + forme + parfum
- Les **images** sont générées automatiquement selon la forme et le parfum
- Les produits `special` sont rattachés aux **rayons événementiels** correspondants
- Les produits normaux vont dans le **rayon Base** (id=1)

---

## 🖼 Images des produits

Les images doivent être placées dans `public/fichier/image/meringues/` selon la structure :

```
Mini/
  sachet_de_10/Ananas.jpg
  individuelle/Ananas.jpg
Nid/
  sachet_de_4/Ananas.jpg
  boite_de_8/Ananas.jpg
  individuelle/Ananas.jpg
```

> ⚠️ Les noms de fichiers doivent correspondre exactement aux noms de parfums en base (sensible à la casse, sans accents si possible).

---

## 🛍 Boutique

URL : `/shop/1`

- Navigation par **rayons** (visible uniquement si `live_rayon = true`)
- Produits organisés par **thèmes**
- Vue **grille** ou **liste**
- Badges : Nouveau, Épuisé, Emporter, Expédition

---

## ⚙️ Interface de gestion

URL : `/gestion`

Onglets disponibles :
- **Produits** — liste avec toggles Live, Emporter, Expédition, Nouveauté
- **Rayons** — création, association aux events
- **Formes** — Mini, Nid, etc.
- **Conditionnements** — sachet, boite, individuelle…
- **Parfums** — liste des saveurs
- **Thèmes** — avec icône et couleur
- **Events** — occasions spéciales
- **Prix** — combinaisons forme × conditionnement

Filtrage des produits par rayon via le sélecteur en haut à droite.

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
