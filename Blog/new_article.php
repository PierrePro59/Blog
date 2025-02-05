<?php
session_start();  // Démarre la session pour accéder aux variables de session, comme l'ID utilisateur

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {  // Vérifie si l'ID utilisateur est défini dans la session
    header("Location: login.php");  // Si l'utilisateur n'est pas connecté, le redirige vers la page de connexion
    exit();  // Arrête l'exécution du script pour empêcher l'accès à la page suivante
}

// Connexion à la base de données
$serveur = "localhost";  // Spécifie le serveur de la base de données
$utilisateur = "root";  // Spécifie l'utilisateur pour la connexion à la base de données
$mot_de_passe = "root";  // Spécifie le mot de passe pour la connexion
$base_de_donnees = "blog_db";  // Nom de la base de données à utiliser

try {
    // Crée une connexion PDO à la base de données
    $base = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Définit la gestion des erreurs pour lancer des exceptions
} catch (PDOException $e) {  // Si une erreur se produit lors de la connexion
    die("Erreur : " . $e->getMessage());  // Affiche un message d'erreur et arrête l'exécution du script
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  // Vérifie si la requête est de type POST (envoi de formulaire)
    $title = $_POST['title'];  // Récupère le titre de l'article envoyé via le formulaire
    $content = $_POST['content'];  // Récupère le contenu de l'article envoyé via le formulaire
    $user_id = $_SESSION['user_id'];  // Récupère l'ID de l'utilisateur depuis la session

    // Insertion de l'article dans la base de données
    try {
        // Prépare la requête SQL pour insérer l'article dans la table "articles"
        $stmt = $base->prepare("INSERT INTO articles (title, content, user_id, created_at) VALUES (:title, :content, :user_id, NOW())");
        // Lie les variables à la requête préparée
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();  // Exécute la requête d'insertion

        header("Location: dashboard.php");  // Redirige vers le tableau de bord après la publication de l'article
        exit();  // Arrête l'exécution du script
    } catch (PDOException $e) {  // Si une erreur se produit lors de l'insertion
        die("Erreur lors de l'insertion de l'article : " . $e->getMessage());  // Affiche un message d'erreur et arrête l'exécution
    }
}
?>

<!DOCTYPE html>
<html lang="fr">  <!-- Déclare que le document est en français -->
<head>
    <meta charset="UTF-8">  <!-- Définit l'encodage des caractères à UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Assure la compatibilité mobile pour la mise en page responsive -->
    <title>Ajouter un Article</title>  <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">  <!-- Lien vers le fichier CSS de Bootstrap pour le style -->
</head>
<body>
    <div class="container mt-4">  <!-- Conteneur principal avec une marge supérieure de 4 unités -->
        <h2>Publier un nouvel article</h2>  <!-- Titre principal de la page -->
        <form action="new_article.php" method="POST">  <!-- Formulaire pour soumettre un nouvel article, avec la méthode POST -->
            <div class="mb-3">  <!-- Div contenant le champ de saisie pour le titre -->
                <label for="title" class="form-label">Titre</label>  <!-- Label du champ de saisie pour le titre -->
                <input type="text" class="form-control" id="title" name="title" required>  <!-- Champ de saisie pour le titre, requis -->
            </div>
            <div class="mb-3">  <!-- Div contenant le champ de saisie pour le contenu -->
                <label for="content" class="form-label">Contenu</label>  <!-- Label du champ de saisie pour le contenu -->
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>  <!-- Champ de saisie pour le contenu, requis avec 5 lignes de hauteur -->
            </div>
            <button type="submit" class="btn btn-primary">Publier</button>  <!-- Bouton pour soumettre le formulaire et publier l'article -->
        </form>
    </div>
</body>
</html>
