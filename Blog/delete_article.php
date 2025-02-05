<?php
// Démarre la session, ce qui permet de stocker et d'accéder aux informations utilisateur pendant la navigation
session_start();

// Vérifie si l'utilisateur est connecté en vérifiant si l'ID de l'utilisateur est stocké dans la session
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, il est redirigé vers la page de connexion
    header("Location: login.php");
    exit();
}

// Définition des informations de connexion à la base de données
$serveur = "localhost"; // Adresse du serveur de la base de données
$utilisateur = "root"; // Nom d'utilisateur pour la connexion à la base de données
$mot_de_passe = "root"; // Mot de passe pour l'utilisateur de la base de données
$base_de_donnees = "blog_db"; // Nom de la base de données utilisée

try {
    // Connexion à la base de données en utilisant PDO (PHP Data Objects) pour interagir avec MySQL
    $base = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    // Définition du mode de gestion des erreurs de PDO pour afficher des exceptions en cas d'erreur
    $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la connexion échoue, un message d'erreur est affiché et l'exécution du script s'arrête
    die("Erreur : " . $e->getMessage());
}

// Vérifie si un ID d'article est passé en paramètre dans l'URL (exemple : delete_article.php?id=1)
if (isset($_GET['id'])) {
    // Récupère l'ID de l'article depuis l'URL
    $article_id = $_GET['id'];

    // Prépare la requête SQL pour supprimer l'article de la base de données
    // La suppression est effectuée uniquement si l'article appartient à l'utilisateur connecté (user_id)
    $stmt = $base->prepare("DELETE FROM articles WHERE id = :id AND user_id = :user_id");
    // Associe l'ID de l'article et l'ID de l'utilisateur connecté aux paramètres de la requête
    $stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    // Exécute la requête pour supprimer l'article
    $stmt->execute();

    // Redirige l'utilisateur vers le tableau de bord après la suppression de l'article
    header("Location: dashboard.php");
    exit();
} else {
    // Si aucun ID d'article n'est passé en paramètre, le script s'arrête avec un message d'erreur
    die("Aucun ID d'article spécifié.");
}
?>