# Spécifications Fonctionnelles

## 1. Introduction

Ce document décrit les besoins métier et les cas d'usage pour l'application de blog développée avec Symfony. Il sert de référence pour l'équipe projet et les parties prenantes.

**Contexte** : Le projet consiste à créer une plateforme de publication et de modération d'actualités.

**Objectifs** :

- Permettre aux utilisateurs de consulter et commenter des articles.
- Offrir des outils de modération pour les administrateurs.

## 2. Pages et Fonctionnalités

### 2.1. Page d'accueil

- Affiche les dernières actualités en mode vignettes.
- Barre de navigation vers les autres sections.

### 2.2. Page « Actualités »

- Liste paginée des articles.
- Filtre par catégorie ou mot-clé.
- Recherche textuelle.
- Lien vers la page de détail d’un article.

### 2.3. Page « Détail d’un article »

- Titre, contenu et métadonnées (auteur, date).
- Liste des commentaires.
- Formulaire d’ajout de commentaire (pour utilisateurs connectés).
- Fonction “Signaler” pour chaque commentaire.

### 2.4. Page « Inscription »

- Formulaire d’inscription (nom, email, mot de passe).
- Validation des champs et envoi d’un email de confirmation.

### 2.5. Page « Connexion »

- Formulaire de connexion (email + mot de passe).
- Lien “Mot de passe oublié”.

### 2.6. Page « Contact »

- Formulaire de contact (nom, email, message).
- Envoi d’un email vers l’administrateur.

### 2.7. Page « Espace Client »

- Tableau de bord de l’utilisateur connecté.
- Liste des commentaires postés.
- Possibilité de modifier son profil.

## 3. Parcours Utilisateur

### 3.1. Inscription → Connexion

1. L’utilisateur remplit le formulaire d’inscription.
2. Confirmation par email.
3. Connexion avec ses identifiants.

### 3.2. Consultation → Commentaire → Signalement

1. L’utilisateur navigue vers une actualité.
2. Il ajoute un commentaire.
3. Un autre utilisateur peut signaler un commentaire inapproprié.

## 4. Gestion Admin

- Liste des commentaires signalés.
- Actions : valider, supprimer, avertir l’utilisateur.
- Gestion des utilisateurs : bloquer/débloquer.

## 5. Annexes

- Glossaire des termes.
- Contacts projet.
