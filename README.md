InitBureauSecuriteBundle

InitBureauSecuriteBundle est un bundle Symfony destiné à initialiser automatiquement la sécurité d’un back-office lors du premier lancement d’un projet.

Il permet notamment :

la création automatique des comptes Propriétaire et Technicien

l’envoi d’emails d’activation sécurisés

l’activation du compte avec définition du mot de passe

la mise en place du firewall /login

la protection du back-office tant que l’initialisation n’est pas terminée

Ce bundle est pensé pour être plug & play, tout en restant configurable.

✨ Fonctionnalités
🔐 Sécurité & utilisateurs

Création automatique :

d’un Propriétaire

d’un Technicien

Entité SecurAdmin intégrée

Gestion des rôles (ROLE_PROPRIETAIRE, ROLE_TECHNICIEN)

Compte inactif tant que l’activation n’est pas effectuée

📧 Activation par email

Envoi d’un email d’activation contenant un lien sécurisé :

/activation?code=xxxxxxxx


Vérification du code d’activation

Page dédiée si le code est invalide

Activation définitive du compte après validation

🔑 Définition du mot de passe

Formulaire sécurisé avec :

mot de passe

confirmation du mot de passe

Contraintes intégrées :

longueur : 8 à 20 caractères

caractères autorisés : A–Z a–z 0–9 - _ ! @

confirmation obligatoire

Messages d’erreur affichés dans le formulaire

Validation côté serveur avec contrainte personnalisée

🛡️ Authentification

Firewall /login prêt à l’emploi

Authenticator Symfony (LoginFormAuthenticator)

Redirection automatique après activation

Blocage de l’accès au back-office tant que l’activation n’est pas faite

⚙️ Initialisation automatique

Exécution automatique des migrations nécessaires

Détection de l’état d’initialisation via un flag

Blocage global du site tant que la sécurité n’est pas initialisée

📦 Installation
composer require webgiciel2/init-bureau-securite


Le bundle est automatiquement enregistré.

⚙️ Configuration

Créer le fichier suivant dans le projet hôte :

# config/packages/init_bureau_securite.yaml
init_bureau_securite:
  app_url: 'https://votre-domaine.tld'
  mail_robot: 'no-reply@votre-domaine.tld'

  proprietaire:
    email: 'proprietaire@votre-domaine.tld'
    username: 'proprietaire'

  technicien:
    email: 'technicien@votre-domaine.tld'
    username: 'technicien'

🚀 Parcours utilisateur

Installation du bundle

Accès au site

Création automatique des comptes

Envoi des emails d’activation

L’utilisateur clique sur le lien d’activation

Il définit son mot de passe

Le compte est activé

Redirection vers /login

Accès normal au back-office

🧩 Routes fournies
Route	Description
/activation	Activation du compte par code
/login	Page de connexion
/logout	Déconnexion
📁 Structure du bundle (simplifiée)
src/
├── Controller
│   ├── ActivationController.php
│   └── SecurityController.php
├── Entity
│   └── SecurAdmin.php
├── Form
│   ├── ActivationPasswordType.php
│   └── Data/ActivationPasswordData.php
├── Form/Constraint
│   ├── PasswordMatch.php
│   └── PasswordMatchValidator.php
├── Security
│   └── LoginFormAuthenticator.php
├── EventSubscriber
│   └── KernelRequestSubscriber.php
├── Service
│   └── ...
└── Resources
    └── views

🧪 Compatibilité

PHP >= 8.2

Symfony 6.x / 7.x

Doctrine ORM

Twig

Symfony Security

📄 Licence

MIT License
Libre d’utilisation, de modification et d’intégration dans des projets personnels ou professionnels.

🧠 Philosophie

Ce bundle a été conçu pour :

gagner du temps sur la mise en place de la sécurité

éviter les oublis critiques lors du démarrage d’un projet

fournir une base saine et extensible pour un back-office Symfony
