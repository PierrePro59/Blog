<?php
session_start();  /* Démarre une session PHP pour pouvoir utiliser les variables de session */

$pseudo_valeur = "";  /* Initialise la variable $pseudo_valeur avec une chaîne vide, elle servira à stocker la valeur du champ "Pseudo" par défaut */

/* Vérifie que tous les champs nécessaires sont remplis dans le formulaire d'inscription. Si l'un des champs est vide, le script arrête l'exécution et affiche un message d'erreur */
if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['pseudo']) || empty($_POST['password']) || empty($_POST['email'])) {
    die("Tous les champs sont obligatoires.");  /* Si un champ est vide, un message d'erreur est affiché et l'exécution du script est arrêtée */
}

/* Inclut le fichier 'db.php' qui contient la logique de connexion à la base de données */
require('./db.php');

/* Définit l'encodage des caractères en UTF-8 pour éviter les problèmes d'affichage des caractères spéciaux dans la page */
header('Content-Type: text/html; charset=UTF-8');

/* Exécute une requête SQL pour forcer l'encodage des caractères de la connexion à la base de données à UTF-8 */
$base->exec("SET NAMES utf8");

/* Récupère le pseudo saisi dans le formulaire */
$pseudo = $_POST['pseudo'];

/* Prépare une requête SQL pour vérifier si le pseudo existe déjà dans la base de données */
$sql_check_pseudo = "SELECT * FROM inscriptions WHERE pseudo = :pseudo";
$stmt_check_pseudo = $base->prepare($sql_check_pseudo);  /* Prépare la requête SQL avec un marqueur nommé (:pseudo) */
$stmt_check_pseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);  /* Lie le paramètre ':pseudo' à la variable $pseudo */
$stmt_check_pseudo->execute();  /* Exécute la requête */
$existing_user = $stmt_check_pseudo->fetch(PDO::FETCH_ASSOC);  /* Récupère les résultats de la requête et les stocke dans $existing_user */

/* Si un utilisateur avec le même pseudo est trouvé, affiche un message d'erreur et arrête l'exécution */
if ($existing_user) {
    die("Ce pseudo est déjà utilisé. Veuillez en choisir un autre.");
}

/* Récupère le mot de passe saisi dans le formulaire (sans hachage pour l'instant, ce qui n'est pas recommandé pour la sécurité) */
$mot_de_passe = $_POST['password'];

/* Prépare une requête SQL pour insérer les données de l'utilisateur dans la base de données */
$sql = "INSERT INTO inscriptions(nom, prenom, email, pseudo, password) 
        VALUES (:nom1, :prenom1, :email1, :pseudo1, :password1)";
$resultat = $base->prepare($sql);  /* Prépare la requête SQL avec des marqueurs nommés pour les champs à insérer */

/* Lie les variables aux marqueurs dans la requête SQL pour sécuriser les données avant insertion */
$resultat->bindParam(':nom1', $_POST['nom'], PDO::PARAM_STR);  /* Lie le champ 'nom' à la requête */
$resultat->bindParam(':prenom1', $_POST['prenom'], PDO::PARAM_STR);  /* Lie le champ 'prenom' à la requête */
$resultat->bindParam(':email1', $_POST['email'], PDO::PARAM_STR);  /* Lie le champ 'email' à la requête */
$resultat->bindParam(':pseudo1', $pseudo, PDO::PARAM_STR);  /* Lie le champ 'pseudo' à la requête */
$resultat->bindParam(':password1', $mot_de_passe, PDO::PARAM_STR);  /* Lie le champ 'password' (mot de passe en clair, à ne pas faire en production) */

/* Essaye d'exécuter la requête d'insertion */
try {
    $resultat->execute();  /* Exécute la requête SQL pour insérer les données dans la base de données */
    echo "Vous avez bien été ajouté à la base de données. Votre pseudo est : " . htmlspecialchars($pseudo);  /* Affiche un message de succès avec le pseudo de l'utilisateur */
} catch (PDOException $e) {
    die("Échec de l'insertion : " . $e->getMessage());  /* Si une erreur se produit lors de l'insertion, affiche un message d'erreur */
}
?>