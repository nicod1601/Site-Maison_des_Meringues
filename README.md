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
- [Modèle de données](#️-modèle-de-données)
- [Authentification](#-système-dauthentification)
- [Commandes utiles](#️-commandes-utiles)
- [Structure du projet](#-structure-du-projet)
- [Stack technique](#-stack-technique)
- [Tests](#-tests)
- [Sécurité](#-sécurité)
- [Troubleshooting](#-troubleshooting)
- [Contribution](#-contribution)
- [License](#-license)
- [Support](#-support)

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
│   │   │   ├── AccueilController.php       # Page d'accueil
│   │   │   ├── ShopController.php          # Boutique et catalogue
│   │   │   ├── PanierController.php        # Gestion du panier
│   │   │   ├── CommandeController.php      # Gestion des commandes
│   │   │   ├── GestionController.php       # Panel d'administration
│   │   │   ├── CreationController.php      # Création entités (CRUD)
│   │   │   ├── ImportController.php        # Import produits Excel/CSV
│   │   │   ├── ImageController.php         # Gestion images produits
│   │   │   ├── BlogController.php          # Blog et articles
│   │   │   ├── ProfileController.php       # Profil utilisateur
│   │   │   ├── SettingsController.php      # Configuration appli
│   │   │   └── LoginController.php         # Authentification custom
│   │   ├── Middleware/           # Middlewares (auth, authorization)
│   │   └── Requests/             # Form Requests (validation)
│   │
│   ├── Models/                   # Modèles Eloquent (entités)
│   │   ├── User.php              # Utilisateurs (client/admin)
│   │   ├── Produit.php           # Produits (meringues)
│   │   ├── Forme.php             # Types (Mini, Nid)
│   │   ├── Forme_Condi.php       # Conditionnements produits
│   │   ├── Conditionnement.php   # Tailles (sachet 10g, boîte 8, etc.)
│   │   ├── Parfum.php            # Saveurs (Fraise, Nature, etc.)
│   │   ├── Rayon.php             # Catégories (Classiques, Fruits)
│   │   ├── Theme.php             # Thèmes visuels
│   │   ├── Event.php             # Événements saisonniers
│   │   ├── Boutique.php          # Boutique principale
│   │   ├── Panier.php            # Panier utilisateur
│   │   ├── PanierLigne.php       # Lignes du panier
│   │   ├── Commande.php          # Commandes clients
│   │   ├── CommandeLigne.php     # Lignes des commandes
│   │   ├── BlogPost.php          # Articles blog
│   │   ├── BlogReaction.php      # Réactions sur articles
│   │   └── Image.php             # Images produits
│   │
│   ├── Mail/                     # Notifications email
│   │   ├── WelcomeMail.php       # Email bienvenue
│   │   └── TestMail.php          # Email de test
│   │
│   ├── Imports/
│   │   └── UsersImport.php       # Import utilisateurs Excel
│   │
│   ├── Listeners/
│   │   └── TransfererPanierApresLogin.php  # Fusion panier session→compte
│   │
│   ├── Providers/
│   │   └── AppServiceProvider.php  # Configuration services
│   │
│   └── View/
│       └── Components/            # Composants réutilisables Blade
│
├── bootstrap/                    # Fichiers de démarrage
├── config/                       # Configuration applicatio
│   ├── app.php                   # Config générale
│   ├── database.php              # Config BD
│   ├── auth.php                  # Config authentification
│   ├── cache.php                 # Config cache
│   ├── mail.php                  # Config email
│   ├── session.php               # Config sessions
│   └── ...
│
├── database/
│   ├── migrations/               # Migrations BD
│   │   ├── 2024_01_01_000000_create_all_tables.php
│   │   ├── 2026_04_29_135146_create_users_table.php
│   │   ├── 2026_04_29_135146_create_panier_tables.php
│   │   ├── 2026_05_18_062344_create_commandes_table.php
│   │   └── ...
│   ├── factories/                # Model factories (tests)
│   │   └── UserFactory.php
│   └── seeders/                  # Données initiales
│
├── public/                       # Assets publics
│   ├── index.php                 # Point d'entrée
│   ├── build/                    # Assets compilés par Vite
│   │   ├── app.js
│   │   ├── app.css
│   │   └── manifest.json
│   ├── fichier/                  # Uploads utilisateurs
│   │   └── image/meringues/      # Images produits
│   │       ├── Mini/
│   │       ├── Nid/
│   │       └── ...
│   └── robots.txt
│
├── resources/
│   ├── views/                    # Templates Blade
│   │   ├── index.blade.php       # Accueil
│   │   ├── shop.blade.php        # Boutique
│   │   ├── panier.blade.php      # Panier client
│   │   ├── gestion.blade.php     # Panel admin
│   │   ├── auth/
│   │   │   ├── login.blade.php   # Connexion (design custom)
│   │   │   └── register.blade.php # Inscription (design custom)
│   │   ├── templet/              # Composants réutilisables
│   │   │   ├── header.blade.php
│   │   │   └── footer.blade.php
│   │   └── layouts/
│   │       └── app.blade.php     # Layout principal
│   ├── js/
│   │   └── app.js                # Entrée Vite JavaScript
│   └── css/
│       ├── app.css               # Entrée Vite CSS
│       ├── style.css             # Design system global
│       ├── boutique.css          # Styles boutique
│       ├── gestion.css           # Styles admin
│       ├── login.css             # Styles auth
│       └── index.css             # Styles accueil
│
├── routes/                       # Routes application
│   ├── web.php                   # Routes web principales
│   ├── auth.php                  # Routes authentification
│   └── console.php               # Commandes console
│
├── storage/                      # Fichiers générés
│   ├── app/                      # Fichiers applicatifs
│   ├── framework/                # Cache/sessions
│   └── logs/                     # Fichiers logs
│
├── tests/                        # Tests unitaires & fonctionnels
│   ├── Feature/                  # Tests fonctionnels
│   ├── Unit/                     # Tests unitaires
│   └── TestCase.php              # Classe de base tests
│
├── vendor/                       # Dépendances Composer (auto-généré)
│
├── .env.example                  # Modèle de configuration
├── .gitignore                    # Fichiers ignorés Git
├── composer.json                 # Dépendances PHP
├── composer.lock                 # Lock versions dépendances PHP
├── package.json                  # Dépendances Node.js
├── package-lock.json             # Lock versions dépendances Node.js
├── tailwind.config.js            # Configuration Tailwind CSS
├── vite.config.js                # Configuration Vite (bundler)
├── postcss.config.js             # Configuration PostCSS
├── phpunit.xml                   # Configuration PHPUnit (tests)
├── artisan                       # CLI Laravel
├── README.md                     # Ce fichier
└── LICENSE                       # Licence MIT
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
| **Vite** | 7.0.7 | Bundler & dev server |
| **Tailwind CSS** | 3.1.0 | Framework CSS utility-first |
| **Alpine.js** | 3.4.2 | Interactivité légère |
| **Axios** | 1.11+ | Requêtes HTTP |
| **PostCSS** | 8.4.31 | Transformation CSS |
| **Autoprefixer** | 10.4.2 | Préfixes navigateurs |

### Dépendances critiques
| Librairie | Version | Utilité |
|-----------|---------|---------|
| **Maatwebsite/Excel** | 3.1 | Import/Export Excel |
| **Intervention/Image** | 1.5 | Traitement & redimensionnement images |
| **Monetico-PHP** | 2.0 | Intégration paiement Monetico |
| **Laravel Breeze** | 2.4 | Authentification & scaffolding |
| **Laravel Pail** | 1.2.2 | Suivi des logs en temps réel |
| **Laravel Tinker** | 3.0 | REPL console interactive |
| **PHPUnit** | 11.5.50 | Tests unitaires & fonctionnels |
| **Faker** | 1.23 | Données fictives pour tests |

---

## 🗄️ Modèle de données

### Hiérarchie des entités

```
users
    ├── role (admin / client)
    ├── email, password, name
    └── panier (via user_id) — nullable pour visiteurs anonymes

boutique
    └── rayon (many-to-many)
            └── produit (many-to-many)

produit
    ├── forme (Forme.php)
    │   └── forme_condi (Forme_Condi.php)
    │       ├── conditionnement (Conditionnement.php)
    │       ├── prix_unitaire
    │       ├── stock
    │       └── images (many)
    ├── parfum (Parfum.php)
    ├── theme (Theme.php — optionnel pour tri visuel)
    └── events (many-to-many via produit_event)

panier
    ├── user_id (nullable — null = visiteur anonyme)
    ├── session_id (pour les visiteurs)
    └── panier_ligne (many)
            ├── produit_id
            ├── forme_condi_id
            ├── quantite
            └── prix_unitaire

commande
    ├── user_id
    ├── reference_commande (unique)
    ├── montant_total
    ├── statut (pending, confirmed, shipped, delivered, cancelled)
    └── commande_ligne (many)
            ├── produit_id
            ├── forme_condi_id
            ├── quantite
            └── prix_unitaire

image
    ├── produit_id
    ├── forme_condi_id
    └── path (ex: `fichier/image/meringues/Mini/sachet_de_10/Ananas.jpg`)
```

### Relations principales

- **1 Produit** → **N Formes** (ex: Mini, Nid)
- **1 Forme** → **N Forme_Condi** (ex: sachet_de_10, boîte_de_8)
- **1 Forme_Condi** → **N Images** (galerie du conditionnement)
- **1 Panier** → **N Panier_Ligne** → **1 Produit** + **1 Forme_Condi**
- **1 Commande** → **N Commande_Ligne** (copie du panier au moment de la commande)

---

## 👤 Système d'authentification

### Flux d'authentification

1. **Inscription** (`/register`) :
   - Création de compte avec rôle `client` par défaut
   - Email de bienvenue envoyé
   - Redirection vers profil

2. **Connexion** (`/login`) :
   - Authentification via email + mot de passe
   - **Fusion du panier** : panier session → compte utilisateur via listener `TransfererPanierApresLogin`
   - Redirection vers accueil

3. **Rôles & Permissions** :
   - `client` : accès à la boutique, mon compte, panier, commandes
   - `admin` : accès à `/gestion` (panel d'administration complet)

4. **Gestion des sessions** :
   - Session visiteur anonyme : panier stocké en session
   - Session connecté : panier liée au compte utilisateur
   - Récupération du panier post-connexion via listener

---

## 🛠️ Commandes utiles

### Démarrage et développement

```bash
# ✨ Lancer TOUT en une commande (développement)
composer run dev
# Démarre : Serveur Laravel + Queue + Logs (Pail) + Vite

# 📦 Initialisation première utilisation
composer run setup
# Install Composer + .env + key + migrate + npm install + build

# 🔵 Serveur Laravel uniquement
php artisan serve

# 🟣 Worker de queue (jobs asynchrones)
php artisan queue:listen

# 🟠 Vite dev server (rebuild CSS/JS à chaque changement)
npm run dev

# 🟡 Compiler les assets en mode production
npm run build

# 🔴 Logs en temps réel
php artisan pail
```

### Migrations et base de données

```bash
# Exécuter toutes les migrations
php artisan migrate

# Créer et exécuter une nouvelle migration
php artisan make:migration nom_de_la_migration

# Rollback dernière migration
php artisan migrate:rollback

# Rollback tout et refaire depuis le début
php artisan migrate:refresh

# Avec seeders
php artisan migrate:fresh --seed
```

### Génération de modèles et contrôleurs

```bash
# Modèle + factory + migration + seeder
php artisan make:model Produit -mfsc

# Contrôleur avec méthodes resource
php artisan make:controller ProduitController -r

# Mail
php artisan make:mail NotificationCommande

# Job (queue)
php artisan make:job TraiterCommande
```

### Console interactive

```bash
# REPL interactive PHP
php artisan tinker

# Exemples dans Tinker :
$user = App\Models\User::first();
$user->update(['role' => 'admin']);
$produits = App\Models\Produit::all();
```

### Tests

```bash
# Lancer tous les tests
php artisan test

# Tests spécifiques
php artisan test tests/Feature/CommandeControllerTest.php

# Avec couverture de code
php artisan test --coverage

# Créer un test
php artisan make:test CommandeControllerTest
```

### Optimisation production

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimiser autoloader Composer
composer install --optimize-autoloader --no-dev

# Optimize Laravel
php artisan optimize
```

### Utilitaires

```bash
# Vider le cache
php artisan cache:clear

# Vider les logs
php artisan cache:clear

# Storage link (pour public/storage)
php artisan storage:link

# Afficher toutes les routes
php artisan route:list

# Afficher la config
php artisan config:show
```

---

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

## 🐛 Troubleshooting

### Problèmes courants

| Problème | Solution |
|----------|----------|
| **"SQLSTATE\[08006\]" — Connexion BD échouée** | Vérifiez PostgreSQL est en cours d'exécution et les credentials `.env` sont corrects |
| **"Class not found" — Erreur autoloader** | Lancez `composer dump-autoload` |
| **Assets CSS/JS ne se compilent pas** | Lancez `npm run build` ou `npm run dev` |
| **Session panier perdue après connexion** | Vérifiez le listener `TransfererPanierApresLogin` est enregistré dans `EventServiceProvider` |
| **Erreur 404 sur `/gestion`** | Connectez-vous avec un compte `admin` (rôle dans BD) |
| **"The stream or file ... is not writable"** | Changez les permissions : `chmod -R 775 storage bootstrap/cache` |
| **Migrations échouent** | Lancez `php artisan migrate:fresh` pour réinitialiser la BD |
| **Vite dev server refuse la connexion** | Changez le port dans `vite.config.js` ou tuez le processus qui l'utilise |

### Variables d'environnement critiques

```env
# ✅ À configurer absolument
APP_ENV=local              # local, staging, production
APP_DEBUG=true             # false en production
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=maison_meringues
DB_USERNAME=postgres
DB_PASSWORD=votre_motdepasse

# ✅ Email (pour notifications)
MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=noreply@maisondesmeringues.local

# ⚠️ En production uniquement
APP_URL=https://maisondesmeringues.fr
LARAVEL_HORIZON_BALANCING_OPTIONS=...
```

---

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
