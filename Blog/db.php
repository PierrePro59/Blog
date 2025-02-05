<?php
$serveur = "localhost";  /* Définit l'adresse du serveur de la base de données (localhost indique que la base est sur la même machine) */
$utilisateur = "root";  /* Définit le nom d'utilisateur pour se connecter à la base de données (ici 'root' pour un environnement local) */
$mot_de_passe = "root";  /* Définit le mot de passe pour l'utilisateur de la base de données (ici 'root' pour un environnement local) */
$base_de_donnees = "blog_db";  /* Définit le nom de la base de données à utiliser, ici "blog_db" */

try {
    /* Tente de créer une connexion à la base de données avec PDO */
    $pdo = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);  /* Crée une instance de PDO pour se connecter à la base de données MySQL */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  /* Définit le mode de gestion des erreurs de PDO à EXCEPTION, ce qui permet de gérer les erreurs avec des exceptions */

    // echo "Connexion réussie !"; /* Message de succès de la connexion (peut être désactivé en production pour des raisons de sécurité) */
} catch (PDOException $e) {
    /* Si une exception est lancée, afficher le message d'erreur et arrêter l'exécution du script */
    die("Erreur : " . $e->getMessage());  /* Affiche l'erreur et termine le script */
}
?>