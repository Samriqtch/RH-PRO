# RH-PRO

RH-PRO est une application web de gestion des ressources humaines, conçue pour permettre aux entreprises de gérer facilement leurs employés, leurs profils, leurs congés et leurs informations administratives dans une interface moderne, dynamique et responsive.

---

## Fonctionnalités principales

- **Authentification sécurisée** : Connexion par identifiant et mot de passe, gestion de session.
- **Dashboard dynamique** : Vue d’ensemble des employés, statistiques et graphiques interactifs (Chart.js).
- **Gestion des employés** :
  - Ajouter, modifier, supprimer et consulter les fiches employés.
  - Recherche dynamique par nom, prénom ou email.
  - Statut des employés (Actif, En congé, Inactif) avec badges colorés.
- **Profil utilisateur** : Affichage du profil connecté, informations sur l’entreprise.
- **Sidebar et navigation** : Menu latéral moderne, navigation rapide entre les pages.
- **Notifications et feedbacks** : Messages de succès/erreur, notifications animées lors des actions importantes.
- **Responsive design** : Interface adaptée à tous les écrans (desktop, tablette, mobile).
- **Sécurité** : Vérification des droits d’accès, protection des routes sensibles.

---

## Installation et configuration

1. **Prérequis** :
   - PHP 7.4 ou supérieur
   - Serveur web (Apache recommandé, compatible XAMPP/WAMP)
   - MySQL/MariaDB

2. **Cloner le projet** :
   - Placez le dossier `RH-PRO` dans le répertoire `htdocs` de XAMPP ou le dossier web de votre serveur.

3. **Base de données** :
   - Créez une base de données MySQL (ex : `rhpro`).
   - Importez le script SQL fourni (à adapter selon votre structure) pour créer les tables `users`, `employes`, `entreprises`, etc.
   - Configurez le fichier `db.php` avec vos identifiants MySQL.

4. **Lancer l’application** :
   - Démarrez Apache et MySQL via XAMPP.
   - Accédez à [http://localhost/RH-PRO/login.php](http://localhost/RH-PRO/login.php) dans votre navigateur.

---

## Utilisation

- **Connexion** : Accédez à la page de login, entrez vos identifiants.
- **Dashboard** : Visualisez les statistiques RH et la liste des employés.
- **Ajouter/Modifier/Supprimer** : Utilisez les boutons d’action pour gérer les employés.
- **Recherche** : Utilisez la barre de recherche pour filtrer rapidement les employés.
- **Profil** : Consultez votre profil et les informations de votre entreprise.
- **Déconnexion** : Cliquez sur "Déconnexion" dans la sidebar ou le profil.

---

## Personnalisation

- **Logos et images** : Remplacez les fichiers dans `uploads/` pour personnaliser le branding.
- **Styles** : Les couleurs principales sont le bleu (#0d6efd) et le blanc, modifiables dans les fichiers CSS ou dans les balises `<style>` des fichiers PHP.
- **Graphiques** : Les graphiques utilisent Chart.js, personnalisables dans le JS en bas de `home.php`.

---


## Dépendances

- [Bootstrap 5](https://getbootstrap.com/) (CDN)
- [Bootstrap Icons](https://icons.getbootstrap.com/) (CDN)
- [Chart.js](https://www.chartjs.org/) (CDN)

---

## Auteurs

- Projet développé par Samson.
- Pour toute question ou contribution, contactez moi

---



