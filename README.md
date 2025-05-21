# 🌿 EcoRide – Plateforme de covoiturage

**EcoRide** est une application web de covoiturage développée avec PHP, MySQL et Docker. Elle permet de proposer, rechercher et réserver des trajets en ligne de manière intuitive, avec une interface responsive et des fonctionnalités complètes.

---

## 🚀 Déploiement avec Docker

L’application est entièrement conteneurisée avec **Docker** et peut être lancée en un seul clic via `docker-compose`.

### Prérequis

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Windows / Mac)
- Git (pour cloner le dépôt)

---

## Lancer l’application en local

### 1. Cloner le projet

```bash
git clone https://github.com/votre-utilisateur/ecoride.git
cd ecoride

---

Cela va :

Lancer un serveur web PHP (ecoride-web)
Lancer un conteneur MySQL avec la base ecoride
Lancer phpMyAdmin accessible sur le port 8080

Le fichier SQL (ecoride.sql) est automatiquement importé depuis :

docker/mysql/init/ecoride.sql

Aucune action manuelle n’est requise pour créer ou importer la base.

##  Accès à l'application

Service	URL
Frontend PHP	http://localhost:8000
phpMyAdmin	http://localhost:8080

Login phpMyAdmin :

    Utilisateur : root

    Mot de passe : root

## Structure du projet

ecoride/
├── public/               → Point d’entrée (frontend)
├── config/               → Connexion à la BDD
├── docker/               → Configs Docker
│   ├── mysql/init/       → SQL d'initialisation
│   ├── Dockerfile        → Image PHP personnalisée
│   └── docker-compose.yml
├── includes/             → Fichiers communs HTML (header/footer)
├── vendor/               → Dépendances (si utilisées avec Composer)
├── ecoride.sql           → Dump de la BDD (copie de secours)
└── README.md             → Fichier d’instructions

## Contexte

Projet développé dans le cadre de la formation Développeur Graduate Angular – Studi.
Il s’agit d’une application pédagogique complète, intégrant backend, frontend, gestion de base et déploiement conteneurisé.
   Fonctionnalités principales

    Connexion / inscription

    Gestion de compte et de photo de profil

    Ajout / modification de véhicules

    Création et réservation de trajets

    Préférences (clim, animaux, fumeurs…)

    Crédits utilisateur

    Interface responsive (Tailwind, Bootstrap)

   Bonus : commandes utiles

   Arrêter les conteneurs :

   docker-compose down

   Relancer après modification :

   docker-compose up --build

   Vérifier que tout fonctionne :
   Accédez à http://localhost:8000

    Licence

Projet open-source à but pédagogique uniquement.
Aucune réutilisation commerciale sans autorisation explicite.