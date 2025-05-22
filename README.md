<!--
███████╗ ██████╗ ██████╗ ██████╗ ██╗██████╗ ███████╗
██╔════╝██╔════╝██╔═══██╗██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║     ██║   ██║██████╔╝██║██║  ██║█████╗  
██╔══╝  ██║     ██║   ██║██╔══██╗██║██║  ██║██╔══╝  
███████╗╚██████╗╚██████╔╝██║  ██║██║██████╔╝███████╗
╚══════╝ ╚═════╝ ╚═════╝ ╚═╝  ╚═╝╚═╝╚═════╝ ╚══════╝
-->

# EcoRide – Covoiturage durable (MVP)

Première version stable du dépôt **EcoRide**, un _Minimum Viable Product_ visant à
mettre en relation passagers et conducteurs pour des trajets éco-responsables.
Le projet a été réalisé dans le cadre du **TP Développeur Web & Web Mobile
(Studi)**.

> ⚠️ Ce projet est complet et fonctionnel dans le cadre de l’ECF. Toutefois, dans un contexte de production réel,
> certaines couches supplémentaires pourraient être intégrées (tests automatisés, sécurité renforcée, CI/CD…).

---

## Sommaire
1. [Stack technique]
2. [Fonctionnalités livrées]
3. [Arborescence du projet]
4. [Installation / déploiement en local]
5. [Configuration de la base de données]
6. [Comptes de démonstration]
7. [Workflow Git (Git Flow simplifié)]
8. [Roadmap & Améliorations]
9. [Liens utiles]

---

## Stack technique

| Couche | Outils / Langages | Raison du choix |
|--------|------------------|-----------------|
| Front  | HTML5 • Tailwind CSS • JS Vanilla | Rapidité de prototypage, design responsive |
| Back   | PHP 8 (natif) | Facile à héberger / apprendre pour un MVP |
| DB     | MySQL 8 | SGBDR classique, support PDO |
| Conteneurisation | Docker + Docker Compose | Parité local ➜ prod, onboarding rapide |
| Gestion de projet | Trello (Kanban) | Visualisation simple des US |
| CI/CD _(optionnel)_ | GitHub Actions (placeholder) | Mise en place future |

---

## Fonctionnalités livrées

- **US 1** : Page d’accueil (présentation + recherche)
- **US 2** : Menu de navigation
- **US 3** : Catalogue des covoiturages (recherche par ville + date)
- **US 4** : Filtres (prix max, durée, note, « voyage écologique »)
- **US 5** : Fiche détaillée d’un trajet
- **US 6** : Réservation (décrémentation des crédits / places)
- **US 7** : Création de compte (pseudo + e-mail + mdp sécurisé)
- **US 8** : Tableau de bord utilisateur (passager / chauffeur)
- **US 9** : Publication d’un trajet (côté chauffeur)
- **US 10** : Historique & annulation
- **US 11** : Démarrage / clôture de trajet (basiquement tracé)
- **US 12** : Espace employé (validation d’avis) – *proto écran*
- **US 13** : Espace admin (stats, suspension) – *proto écran*

> Des captures d’écran des trois variantes d’interface (A/B test) sont
> disponibles dans `docs/screenshots/`.

---

## Arborescence du projet

```
EcoRide/
├── assets/                     # Fichiers CSS, JS
├── class/                      # Classes PHP (ex: LegalNotice)
├── config/                     # Fichiers de configuration (BDD, constantes, etc.)
├── docker/                     # Configuration Docker
│   ├── mysql\init/
│   │   └── ecoride.sql         # Script SQL pour initier la BDD
│   ├── docker-compose.yml      # Docker Compose (services : PHP, MySQL, etc.)
│   └── Dockerfile              # Image PHP personnalisée
├── includes/                   # Fichiers inclus courants (header, footer, etc.)
├── public/                     # Racine accessible depuis le navigateur
│   ├── admin/                  # Pages liées à l'administration
│   ├── images/                 # Images publiques
│   ├── pages/                  # Pages visibles (ex: accueil, recherche)
│   ├── users/                  # Espace utilisateur
│   ├── .htaccess               # Configuration Apache
│   └── index.php               # Point d'entrée de l'application
├── vendor/                     # Librairies installées via Composer
├── composer.json               # Dépendances PHP
├── composer.lock               # Version exacte des dépendances
├── README.md                   # Instructions pour le projet
└── users.json                  # Données utilisateurs (JSON)
```

---

## Installation / déploiement en local

> **Pré-requis** : [Docker Desktop](https://www.docker.com/products/docker-desktop/) (ou équivalent) et **Git**.

### 1. Cloner le dépôt

```bash
git clone https://github.com/Hatenaa/EcoRide.git
cd EcoRide
```

### 2. Démarrer les conteneurs

```bash
docker compose -f docker/docker-compose.yml up -d --build
```

---

## Configuration de la base de données

> ⚠️ Il n'y a **pas de fichier `.env`** dans ce projet. Les paramètres de configuration sont définis en dur dans les fichiers PHP, pour simplifier l'installation.

Les identifiants de connexion à la base de données sont les suivants :

- **Hôte** : `localhost`
- **Utilisateur** : `root`
- **Mot de passe** : `root`
- **Nom de la base de données** : `ecoride`

Ces paramètres sont utilisés dans le cadre d’un **environnement de développement local uniquement** (ex : WAMP, MAMP, XAMPP ou Docker).

> ⚠️ **Attention** : N'utilisez jamais `root/root` dans un environnement de production.
>
> Ce couple identifiant/mot de passe est une mauvaise pratique en termes de sécurité.  
> En production, vous devez :
> - Créer un utilisateur dédié avec des permissions limitées.
> - Ne jamais stocker les identifiants directement dans le code.
> - Utiliser des fichiers `.env` ou un système de variables d’environnement.

## Comptes de démonstration

Voici quelques comptes prêts à l'emploi pour tester l'application :

| Rôle           | Email                                             | Mot de passe |
| -------------- | ------------------------------------------------- | ------------ |
| Utilisateur    | [yassine.benali@example.com](mailto:yassine.benali@example.com)     | yassinepass123       |
| Chauffeur      | [doz.riles@gmail.com](mailto:doz.riles@gmail.com) | a8w6WaUyZA4u66     |
| Employé        | [lucie.moreau@example.com](mailto:lucie.moreau@example.com)   | luciepass123      |
| Administrateur | [admin@example.com](mailto:admin@example.com)   | adminpassword       |
