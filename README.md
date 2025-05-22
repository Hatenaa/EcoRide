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
1. Stack technique
2. Fonctionnalités livrées
3. Arborescence du projet
4. Installation / déploiement en local
5. Configuration de la base de données
6. Comptes de démonstration
7. Workflow Git (Git Flow simplifié)
8. Roadmap & Améliorations
9. License

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

---

## Workflow Git (Git Flow simplifié)

Le projet suit également une organisation Git structurée pour assurer une bonne lisibilité et évolutivité du code.

### Branches principales

- `main` → Contient **uniquement le code stable en production**.
- `develop` → Contient le **code en cours de développement** testé et fonctionnel.

### Branches secondaires

- Chaque **nouvelle fonctionnalité** doit être développée sur une branche dédiée :

```bash
git checkout develop
git checkout -b feature/nom-fonctionnalite
```
Exemple :
```
git checkout -b feature/ajout-covoiturage
```
Les branches de fonctionnalités sont toujours créées à partir de develop.
### Processus de fusion

#### 1. Développement de la fonctionnalité sur feature/...
#### 2. Test local
#### 3. Merge dans develop une fois validée :

```
git checkout develop
git merge feature/ajout-covoiturage
```
#### 4. Une fois dev jugée stable (fin de sprint, milestone atteinte…), merge vers main :
```
git checkout main
git merge develop
```
### Nettoyage
Après chaque merge, il est conseillé de supprimer la branche de feature :
```
git branch -d feature/ajout-covoiturage
```
---

## Roadmap & Améliorations

Bien que le projet **EcoRide** soit livré sous forme de MVP fonctionnel, plusieurs évolutions pourraient considérablement enrichir la plateforme, tant sur le plan fonctionnel, marketing que technique :

### 1. Intégration d’un module de paiement (Checkout)

Pour permettre la réservation de trajets payants, l’ajout d’une page de paiement sécurisée serait une étape logique. Elle pourrait inclure :

- Paiement par carte bancaire via une API comme Stripe ou PayPal
- Répartition automatique des revenus entre le conducteur et la plateforme
- Affichage d’un récapitulatif clair du trajet avant validation
- Possibilité d’ajouter une assurance voyage ou des options supplémentaires (bagage XXL, prise en charge à domicile…)

Cela permettrait aussi d’introduire des techniques d’up-selling (options premium, trajets flexibles…) et de cross-selling (abonnements, accessoires écologiques…).

---

### 2. Création d’un blog orienté écologie et mobilité durable

La mise en place d’un blog intégré à la plateforme aurait un double intérêt :

- Renforcer la stratégie SEO (référencement naturel) avec du contenu régulier :
  - Les avantages du covoiturage
  - Les éco-gestes en voyage
  - Les retours d’expérience des utilisateurs
- Attirer des backlinks depuis d'autres sites/blogs spécialisés pour améliorer la visibilité

Cette stratégie éditoriale pourrait attirer un public engagé et renforcer la crédibilité du projet.

---

### 3. Autres pistes d'amélioration à moyen terme

- Système de notes et avis plus poussé entre passagers et conducteurs
- Notifications par e-mail ou SMS lors de la réservation ou annulation
- Filtres de recherche plus fins (confort, musique, préférence animaux…)
- Intégration d’un calendrier personnel
- Création d’un profil public partageable

---

## Enjeux SEO et visibilité long terme

### Structuration du contenu : approche en silo

Pour maximiser l’impact SEO, un blog bien structuré est recommandé. Le contenu doit être organisé par silos :

- Pages mères : Covoiturage, Mobilité verte, Réduction des émissions
- Pages filles : Covoiturage urbain, Comparatif auto vs train…
- Articles longue traîne :  
  “Pourquoi le covoiturage est idéal pour les trajets domicile-travail”  
  “Éthanol E85 : économie ou arnaque ?”

Cette architecture renforce la cohérence sémantique, ce que Google valorise fortement.

---

### Objectif SEO : Améliorer le E-A-T (Expertise – Authority – Trust)

- Expertise : contenu qualitatif, structuré et sourcé
- Autorité : présence éditoriale + backlinks crédibles
- Fiabilité : mentions légales, politique de confidentialité, UX professionnelle

---

### Maillage interne & transmission du PageRank

- Liens logiques entre articles et sections
- Navigation fluide pour l’utilisateur
- Les pages importantes (ex : réservation) gagnent du “poids SEO”

---

### URLs lisibles et hiérarchiques

Exemples recommandés :
```
/blog/mobilite-verte/comparatif-voiture-train
/blog/covoiturage/avantages-court-trajet
```

Ces URLs sont :

- Compréhensibles pour l’utilisateur
- Optimisées pour les moteurs de recherche
- Faciles à exploiter dans les outils comme Google Search Console

---

Ces évolutions ne sont pas encore en place, mais elles sont clairement identifiées et cohérentes avec les objectifs du projet. Leur intégration future permettrait de faire évoluer **EcoRide** d’un projet pédagogique vers un produit crédible et compétitif sur le marché.

---
## Licence

Ce projet est un travail académique réalisé dans le cadre du titre professionnel DWWMA (Studi).  
**Toute réutilisation, copie ou diffusion du code est strictement interdite.**

