# AL AMIINE LOGISTIQUE – Guide d'installation XAMPP

## Structure du projet
```
al_amiine/
├── index.php              ← Page principale du site
├── database.sql           ← Script SQL à importer
├── includes/
│   └── config.php         ← Configuration BD
├── admin/
│   ├── login.php          ← Connexion admin
│   ├── dashboard.php      ← Tableau de bord
│   ├── devis.php          ← Gestion des devis
│   ├── produits.php       ← Gestion des produits
│   ├── galerie.php        ← Gestion de la galerie
│   ├── temoignages.php    ← Modération témoignages
│   └── logout.php
└── uploads/
    ├── gallery/           ← Photos de la galerie
    └── products/          ← Images des produits
```

## Installation (5 minutes)

### 1. Copier les fichiers
Placez le dossier `al_amiine` dans :
```
C:\xampp\htdocs\al_amiine\
```

### 2. Créer la base de données
1. Démarrez XAMPP (Apache + MySQL)
2. Ouvrez **phpMyAdmin** : http://localhost/phpmyadmin
3. Cliquez sur "Importer"
4. Sélectionnez le fichier `database.sql`
5. Cliquez "Exécuter"

### 3. Accéder au site
- **Site public** : http://localhost/al_amiine/
- **Admin** : http://localhost/al_amiine/admin/login.php

### 4. Connexion admin par défaut
- Identifiant : `admin`
- Mot de passe : `password`

> ⚠️ **IMPORTANT** : Changez le mot de passe après la première connexion !
> Dans phpMyAdmin, exécutez :
> ```sql
> UPDATE admins SET password = '$2y$10$...' WHERE username = 'admin';
> ```
> Ou créez un script PHP pour générer le hash : `password_hash('votre_mdp', PASSWORD_DEFAULT)`

## Fonctionnalités
- ✅ Site vitrine complet (7 sections)
- ✅ Formulaire de devis enregistré en base
- ✅ Gestion produits (fer & bois) avec images
- ✅ Galerie photos dynamique avec upload
- ✅ Témoignages avec modération
- ✅ Tableau de bord admin complet
- ✅ Design moderne responsive

## Personnalisation
Modifiez dans `index.php` :
- Numéros de téléphone (cherchez "+221 XX XXX XX XX")
- Adresse email (cherchez "contact@alaamine")
- Textes de présentation (section À Propos)
- Statistiques hero (500+ clients, 10+ ans, etc.)
