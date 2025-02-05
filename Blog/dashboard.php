<?php
session_start(); // Démarre la session pour vérifier si l'utilisateur est connecté

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) { // Si l'utilisateur n'est pas connecté
    header("Location: login.php"); // Redirige vers la page de login
    exit(); // Arrête l'exécution du script
}

// Connexion à la base de données
$serveur = "localhost"; // Définir le serveur de base de données
$utilisateur = "root"; // Définir l'utilisateur de la base de données
$mot_de_passe = "root"; // Définir le mot de passe de la base de données
$base_de_donnees = "blog_db"; // Définir le nom de la base de données

try {
    // Crée une connexion à la base de données avec les informations fournies
    $base = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active le mode d'erreur pour afficher les exceptions
} catch (PDOException $e) { // Si une erreur se produit lors de la connexion à la base de données
    die("Erreur : " . $e->getMessage()); // Arrête l'exécution et affiche le message d'erreur
}

// Récupération des articles associés à l'utilisateur
$articles = []; // Initialise un tableau vide pour stocker les articles
$user_id = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté
try {
    // Prépare la requête SQL pour récupérer les articles de l'utilisateur, triés par date de création
    $stmt = $base->prepare("SELECT id, title, created_at FROM articles WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Lie l'ID de l'utilisateur à la requête
    $stmt->execute(); // Exécute la requête
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère tous les articles dans un tableau associatif
} catch (PDOException $e) { // Si une erreur se produit lors de la récupération des articles
    die("Erreur lors de la récupération des articles : " . $e->getMessage()); // Affiche le message d'erreur
}

// Déconnexion
if (isset($_GET['logout'])) { // Si l'utilisateur a cliqué sur le bouton de déconnexion
    session_destroy(); // Détruit la session (déconnecte l'utilisateur)
    header("Location: login.php"); // Redirige vers la page de login
    exit(); // Arrête l'exécution du script
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Définit le jeu de caractères en UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Définit les paramètres de la vue pour les appareils mobiles -->
    <title>Tableau de Bord</title> <!-- Titre de la page -->
    <!-- Inclusion de la feuille de style Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Inclusion du script JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark"> <!-- Menu de navigation -->
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Mon Blog - Dashboard</a> <!-- Titre du blog -->
            <!-- Formulaire de recherche d'ami -->
            <form class="d-flex" action="search_user.php" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="Rechercher un ami" required>
                <button class="btn btn-outline-light" type="submit">Rechercher</button>
            </form>
            <div>
                <a href="actualites.php" class="btn btn-primary me-2">Actualité</a> <!-- Lien vers la page d'actualités -->
                <a href="?logout" class="btn btn-danger">Déconnexion</a> <!-- Lien pour se déconnecter -->
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Bienvenue sur votre tableau de bord</h2> <!-- Titre de la page -->
        
        <div class="d-flex justify-content-end mb-3">
            <a href="new_article.php" class="btn btn-success">+ Ajouter un Article</a> <!-- Bouton pour ajouter un nouvel article -->
        </div>

        <!-- Tableau des articles -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($articles)): ?> <!-- Si aucun article n'est trouvé -->
                        <tr><td colspan="4" class="text-center">Aucun article trouvé</td></tr> <!-- Message indiquant qu'il n'y a pas d'articles -->
                    <?php else: ?>
                        <?php foreach ($articles as $article): ?> <!-- Parcourt chaque article -->
                            <tr>
                                <td><?= $article['id']; ?></td> <!-- Affiche l'ID de l'article -->
                                <td><?= htmlspecialchars($article['title']); ?></td> <!-- Affiche le titre de l'article -->
                                <td><?= $article['created_at']; ?></td> <!-- Affiche la date de création de l'article -->
                                <td>
                                    <a href="edit_article.php?id=<?= $article['id']; ?>" class="btn btn-primary btn-sm">Modifier</a> <!-- Lien pour modifier l'article -->
                                    <a href="delete_article.php?id=<?= $article['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a> <!-- Lien pour supprimer l'article avec confirmation -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
