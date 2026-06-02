# 🍬 La Maison des Meringues

> **Plateforme e-commerce moderne pour une boutique artisanale de meringues normandes**

Découvrez une application web complète dédiée à la vente en ligne de meringues artisanales. Notre plateforme offre une expérience utilisateur fluide côté client et des outils puissants de gestion pour les administrateurs.

[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15%2B-336791?style=flat-square&logo=postgresql)](https://www.postgresql.org)
[![Node.js](https://img.shields.io/badge/Node.js-20.19%2B-339933?style=flat-square&logo=node.js)](https://nodejs.org)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)

---

## 📋 Table des matières

- [À propos](#à-propos)
- [Fonctionnalités](#-fonctionnalités)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Déploiement](#-déploiement)
- [Utilisation](#-utilisation)
- [Structure du projet](#-structure-du-projet)
- [Technologies](#-stack-technique)
- [Contribution](#-contribution)
- [License](#-license)

---

## À propos

**La Maison des Meringues** est une application e-commerce moderne et performante destinée à une boutique artisanale normande spécialisée dans la vente de meringues. Le projet combine une interface client intuitive avec des outils d'administration robustes pour la gestion des produits, des commandes et des clients.

Cette application a été développée en tant que projet de stage, mettant en pratique les meilleures pratiques de développement web moderne.

---

## ✨ Fonctionnalités

### 🛍️ Pour les clients
- ✅ **Boutique en ligne** avec parcours produits fluide
- ✅ **Filtrage avancé** par rayons, thèmes et événements
- ✅ **Panier persistant** (session ou compte utilisateur)
- ✅ **Système d'authentification** complet (inscription, connexion, profil)
- ✅ **Gestion des commandes** avec historique et suivi
- ✅ **Commentaires et réactions** sur les articles du blog
- ✅ **Blog intégré** avec articles curatés

### 🎛️ Pour les administrateurs
- ✅ **Gestion complète des produits** (CRUD avec prévisualisation et statuts)
- ✅ **Gestion des rayons et thèmes** pour l'organisation des produits
- ✅ **Gestion des événements** (Noël, Anniversaire, Printemps, etc.) avec couleurs personnalisées
- ✅ **Import en masse** de produits via Excel/CSV
- ✅ **Gestion des formes, parfums et conditionnements**
- ✅ **Gestion des images produits** avec remontée de fichiers
- ✅ **Gestion des utilisateurs** (rôles et permissions)
- ✅ **Gestion des commandes** avec suivi client détaillé
- ✅ **Panel d'administration** sécurisé avec authentification et validation
- ✅ **Statistiques** (stock total, nombre de produits, rayons actifs)

---

## 📦 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

| Composant | Version minimale | Installation |
|-----------|------------------|--------------|
| **PHP** | 8.2 | [php.net](https://www.php.net/downloads) |
| **Node.js** | 20.19 ou 22.12+ | [nodejs.org](https://nodejs.org/) |
| **PostgreSQL** | 15+ | [postgresql.org](https://www.postgresql.org/download/) |
| **Composer** | 2.6+ | [getcomposer.org](https://getcomposer.org/download/) |
| **Git** | (optional) | [git-scm.com](https://git-scm.com/) |

### Extensions PHP requises
- `pdo_pgsql` - Driver PostgreSQL
- `mbstring` - Support UTF-8
- `openssl` - Chiffrement SSL
- `json` - JSON support
- `fileinfo` - File MIME types
- `gd` - Image processing

---

## 🚀 Installation

### 1. Cloner le projet

```bash
git clone <repository-url>
cd Site-Maison_des_Meringues
```

### 2. Installation automatique (recommandée)

Utilisez le script de configuration intégré :

```bash
composer run setup
```

Ce script va :
- ✅ Installer les dépendances PHP via Composer
- ✅ Copier le fichier `.env.example` vers `.env`
- ✅ Générer la clé d'application Laravel
- ✅ Exécuter les migrations de base de données
- ✅ Installer les dépendances Node.js
- ✅ Compiler les assets frontend

### 3. Installation manuelle

Si vous préférez une installation étape par étape :

#### 3.1 Dépendances PHP
```bash
composer install
```

#### 3.2 Configuration environnement
```bash
cp .env.example .env
php artisan key:generate
```

#### 3.3 Base de données
```bash
php artisan migrate --force
```

#### 3.4 Dépendances Node.js et build frontend
```bash
npm install
npm run build
```

---

## ⚙️ Configuration

### Fichier `.env`

Les paramètres essentiels à configurer :

```env
# Application
APP_NAME=Maison_des_Meringues
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=maison_meringues
DB_USERNAME=postgres
DB_PASSWORD=votre_mot_de_passe

# Mail (pour les notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username
MAIL_PASSWORD=votre_password
MAIL_FROM_ADDRESS=noreply@maisondesmeringues.local
MAIL_FROM_NAME="La Maison des Meringues"

# Cache & Queue
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Session
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
```

### Créer un compte administrateur

Après les migrations, créez un compte admin :

```bash
php artisan tinker
```

Puis dans la console Tinker :

```php
App\Models\User::create([
    'name' => 'Administrateur',
    'email' => 'admin@maisondesmeringues.local',
    'password' => Hash::make('mot_de_passe_securise'),
    'role' => 'admin'
]);
```

---

## 🌐 Déploiement

### Mode développement

Pour lancer le serveur de développement avec all-in-one (Laravel + Queue + Logs + Vite) :

```bash
composer run dev
```

Cela démarre simultanément :
- 🔵 **Serveur Laravel** sur `http://localhost:8000`
- 🟣 **Queue worker** pour les jobs asynchrones
- 🔴 **Logs** (Pail pour suivre les logs en temps réel)
- 🟠 **Vite** pour la compilation des assets en mode watch

### Mode développement séparé

Si vous préférez lancer les services séparément :

**Terminal 1 - Serveur Laravel :**
```bash
php artisan serve
```

**Terminal 2 - Queue worker :**
```bash
php artisan queue:listen
```

**Terminal 3 - Logs :**
```bash
php artisan pail
```

**Terminal 4 - Vite (frontend) :**
```bash
npm run dev
```

### Production

Pour préparer le déploiement en production :

```bash
# Optimiser l'application
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compiler les assets
npm run build

# Nettoyer les logs
php artisan cache:clear
```

---

## 📖 Utilisation

### Accès client

1. **Accueil** : `http://localhost:8000/`
2. **Boutique** : Parcourez les produits par rayon et thème
3. **Inscription** : Créez un compte pour passer commande
4. **Panier** : Ajoutez des produits et finalisez votre commande

### Accès administrateur

1. **Panel admin** : `http://localhost:8000/gestion`
2. **Connexion** : Utilisez votre compte administrateur
3. **Gestion** :
   - Produits : Créer, modifier, supprimer
   - Rayons & Thèmes : Organiser les catégories
   - Événements : Créer des collections saisonnières
   - Utilisateurs : Gérer les clients et administrateurs
   - Commandes : Suivre et gérer les commandes
   - Imports : Importer des produits en masse (Excel/CSV)

### Import de produits

Format attendu pour l'import Excel/CSV :

| Nom | Parfum | Forme | Conditionnement | Prix | Rayon | Stock |
|-----|--------|-------|-----------------|------|-------|-------|
| Meringue nature | Nature | Coucou | Boîte 200g | 8.50 | Classiques | 50 |
| Meringue fraise | Fraise | Géante | Vrac | 12.00 | Fruits | 30 |

---

## 📁 Structure du projet

```
.
├── app/                          # Code applicatif
│   ├── Http/
│   │   ├── Controllers/          # Contrôleurs (logique métier)
│   │   │   ├── AccueilController.php      # Page d'accueil
│   │   │   ├── ShopController.php         # Boutique et catalogue
│   │   │   ├── PanierController.php       # Gestion du panier
│   │   │   ├── CommandeController.php     # Gestion des commandes
│   │   │   ├── GestionController.php      # Panel d'administration
│   │   │   ├── CreationController.php     # Création des entités (produits, rayons, etc.)
│   │   │   ├── ImportController.php       # Import de produits via Excel/CSV
│   │   │   ├── ImageController.php        # Gestion des images
│   │   │   ├── BlogController.php         # Gestion du blog
│   │   │   ├── ProfileController.php      # Profil utilisateur
│   │   │   ├── SettingsController.php     # Paramètres application
│   │   │   └── LoginController.php        # Authentification personnalisée
│   │   ├── Middleware/           # Middlewares (authentification, autorisations)
│   │   └── Requests/             # Form Requests (validation)
│   ├── Models/                   # Modèles Eloquent (entités)
│   │   ├── User.php
│   │   ├── Produit.php
│   │   ├── Commande.php
│   │   ├── PanierLigne.php
│   │   ├── Rayon.php
│   │   ├── BlogPost.php
│   │   └── ...
│   ├── Mail/                     # Notifications email
│   ├── Imports/                  # Imports (Excel)
│   └── Listeners/                # Event Listeners
│
├── bootstrap/                    # Fichiers de démarrage
├── config/                       # Fichiers de configuration
├── database/
│   ├── migrations/               # Migrations BD
│   ├── factories/                # Model factories (tests)
│   └── seeders/                  # Seeders (données initiales)
│
├── public/                       # Assets publics (compilés)
│   ├── build/                    # Assets compilés par Vite
│   └── fichier/                  # Uploads utilisateurs
│
├── resources/
│   ├── views/                    # Vue Blade (templates)
│   ├── js/                       # Scripts JavaScript/Alpine
│   └── css/                      # Feuilles de style
│
├── routes/                       # Routes application
│   ├── web.php                   # Routes web
│   ├── auth.php                  # Routes authentification
│   └── console.php               # Commandes console
│
├── storage/                      # Fichiers générés (logs, cache)
├── tests/                        # Tests unitaires & fonctionnels
├── vendor/                       # Dépendances Composer
│
├── .env.example                  # Modèle de configuration
├── composer.json                 # Dépendances PHP
├── package.json                  # Dépendances Node.js
├── tailwind.config.js            # Configuration Tailwind CSS
├── vite.config.js                # Configuration Vite
├── postcss.config.js             # Configuration PostCSS
├── phpunit.xml                   # Configuration PHPUnit
└── artisan                       # CLI Laravel
```

---

## 🛠️ Stack technique

### Backend
| Technologie | Version | Rôle |
|-------------|---------|------|
| **PHP** | 8.2+ | Langage serveur |
| **Laravel** | 12.0 | Framework web |
| **PostgreSQL** | 15+ | Base de données relationnelle |
| **Laravel Breeze** | 2.4 | Authentification starter |

### Frontend
| Technologie | Version | Rôle |
|-------------|---------|------|
| **Node.js** | 20.19+ / 22.12+ | Runtime JavaScript |
| **Vite** | 7.0+ | Bundler & dev server |
| **Tailwind CSS** | 3.1+ | Framework CSS utility-first |
| **Alpine.js** | 3.4+ | Interactivité légère |
| **Axios** | 1.11+ | Requêtes HTTP |

### Autres dépendances
| Librairie | Version | Utilité |
|-----------|---------|---------|
| **Maatwebsite/Excel** | 3.1 | Import/Export Excel |
| **Intervention/Image** | 1.5 | Traitement images |
| **Monetico-PHP** | 2.0 | Paiement Monetico |
| **Laravel Tinker** | 3.0 | REPL console |
| **PHPUnit** | 11.5+ | Tests unitaires |

---

## 🧪 Tests

### Lancer les tests

```bash
# Tests unitaires
php artisan test

# Tests spécifiques
php artisan test tests/Feature/CommandeControllerTest.php

# Avec couverture de code
php artisan test --coverage
```

### Créer de nouveaux tests

```bash
# Test unitaire
php artisan make:test MonTest --unit

# Test fonctionnel
php artisan make:test MonTest
```

---

## 🔐 Sécurité

### Bonnes pratiques implémentées

- ✅ Authentification Laravel Breeze
- ✅ Protection CSRF sur tous les formulaires
- ✅ Hachage des mots de passe via Bcrypt
- ✅ Protection contre les injections SQL (Eloquent ORM)
- ✅ Validation côté serveur des formulaires
- ✅ Middleware d'authentification pour routes protégées
- ✅ Séparation des rôles (admin/client)

### Recommandations de sécurité

- Changez les clés par défaut en production
- Utilisez HTTPS obligatoirement en production
- Sécurisez les variables d'environnement
- Validez toujours les entrées utilisateur
- Maintenez les dépendances à jour

---

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. **Fork** le projet
2. **Créez une branche** (`git checkout -b feature/VotreFonctionnalite`)
3. **Commitez vos changes** (`git commit -am 'Ajoute VotreFonctionnalite'`)
4. **Push vers la branche** (`git push origin feature/VotreFonctionnalite`)
5. **Ouvrez une Pull Request**

### Standards de code

- Suivez PSR-12 pour le code PHP
- Utilisez les conventions de nommage Laravel
- Documentez les fonctions complexes
- Écrivez des tests pour les nouvelles fonctionnalités

---

## 📝 License

Ce projet est sous license [MIT](LICENSE). Consultez le fichier LICENSE pour plus de détails.

---

## 📞 Support

Pour toute question ou bug :
- 📧 Créez une issue sur le repository
- 💬 Consultez la documentation Laravel : [laravel.com/docs](https://laravel.com/docs)
- 🐛 Vérifiez les issues existantes avant de signaler un bug

---

## 👨‍💻 Auteur

Développé en tant que **projet de stage** par **Nicolas Delpech**  
Maison des Meringues

---

**Dernière mise à jour : Juin 2026**
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
