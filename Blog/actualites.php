<?php
session_start();  // Démarre la session pour pouvoir accéder à des variables de session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {  // Si l'ID de l'utilisateur n'est pas défini dans la session (c'est-à-dire que l'utilisateur n'est pas connecté)
    header("Location: login.php");  // Redirige l'utilisateur vers la page de connexion
    exit();  // Arrête l'exécution du script ici
}

// Connexion à la base de données
$serveur = "localhost";  // Spécifie le serveur de base de données
$utilisateur = "root";  // Spécifie l'utilisateur pour se connecter à la base de données
$mot_de_passe = "root";  // Spécifie le mot de passe pour l'utilisateur
$base_de_donnees = "blog_db";  // Nom de la base de données à utiliser

try {
    // Crée une nouvelle connexion PDO à la base de données
    $base = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Définit le mode de gestion des erreurs de PDO pour lancer des exceptions
} catch (PDOException $e) {  // Si une erreur se produit lors de la connexion à la base de données
    die("Erreur : " . $e->getMessage());  // Affiche un message d'erreur et arrête l'exécution du script
}

// Récupérer tous les articles triés par date de création (les plus récents en premier)
$articles = [];  // Initialise un tableau vide pour stocker les articles

try {
    // Prépare la requête SQL pour récupérer les articles triés par date de création décroissante
    $stmt = $base->prepare("SELECT id, title, created_at, content FROM articles ORDER BY created_at DESC");
    $stmt->execute();  // Exécute la requête
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Récupère tous les articles sous forme de tableau associatif
} catch (PDOException $e) {  // Si une erreur se produit lors de la récupération des articles
    die("Erreur lors de la récupération des articles : " . $e->getMessage());  // Affiche un message d'erreur et arrête l'exécution du script
}
?>

<!DOCTYPE html>
<html lang="fr">  <!-- Déclare le langage du document comme français -->
<head>
    <meta charset="UTF-8">  <!-- Définit l'encodage des caractères du document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Définit la mise en page responsive pour les appareils mobiles -->
    <title>Actualités</title>  <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">  <!-- Lien vers la feuille de style Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  <!-- Lien vers la bibliothèque jQuery -->
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">  <!-- Barre de navigation avec un fond sombre -->
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Mon Blog - Actualités</a>  <!-- Titre de la navbar -->
            <a href="dashboard.php" class="btn btn-info me-2">Retour au tableau de bord</a>  <!-- Bouton pour revenir au tableau de bord -->
            <a href="?logout" class="btn btn-danger">Déconnexion</a>  <!-- Bouton de déconnexion -->
        </div>
    </nav>

    <div class="container mt-4">  <!-- Conteneur principal avec une marge en haut -->
        <h2 class="text-center">Actualités</h2>  <!-- Titre principal de la page -->
        
        <div id="articles-list">  <!-- Liste des articles -->
            <?php if (empty($articles)): ?>  <!-- Si aucun article n'est disponible -->
                <p>Aucun nouvel article disponible.</p>  <!-- Affiche un message indiquant qu'il n'y a pas d'articles -->
            <?php else: ?>  <!-- Si des articles existent -->
                <?php foreach ($articles as $article): ?>  <!-- Parcourt chaque article -->
                    <div class="card mb-3" id="article-<?= $article['id']; ?>">  <!-- Carte d'article avec un ID dynamique -->
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($article['title']); ?></h5>  <!-- Affiche le titre de l'article -->
                            <p class="card-text"><?= substr(htmlspecialchars($article['content']), 0, 150); ?>...</p>  <!-- Affiche un extrait du contenu de l'article -->
                            <button class="btn btn-primary" onclick="toggleArticle(<?= $article['id']; ?>)">Lire l'article</button>  <!-- Bouton pour afficher l'article complet -->
                            <button class="btn btn-secondary" onclick="toggleArticle(<?= $article['id']; ?>)">Fermer l'article</button>  <!-- Bouton pour fermer l'article -->
                            <div class="full-article" id="full-article-<?= $article['id']; ?>" style="display: none;">  <!-- Contenu complet de l'article caché par défaut -->
                                <p><?= nl2br(htmlspecialchars($article['content'])); ?></p>  <!-- Affiche le contenu complet de l'article avec gestion des sauts de ligne -->
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>  <!-- Fin de la boucle foreach pour les articles -->
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Fonction pour afficher ou masquer un article complet
        function toggleArticle(articleId) {
            const articleContent = document.getElementById('full-article-' + articleId);  // Récupère l'élément contenant l'article complet
            const buttons = document.querySelectorAll('#article-' + articleId + ' .btn');  // Récupère les boutons pour cet article

            // Afficher ou cacher le contenu de l'article
            if (articleContent.style.display === "none") {  // Si l'article est actuellement caché
                articleContent.style.display = "block";  // Affiche le contenu complet
                buttons[0].style.display = "none";  // Cache le bouton "Lire l'article"
                buttons[1].style.display = "inline";  // Affiche le bouton "Fermer l'article"
            } else {  // Si l'article est déjà visible
                articleContent.style.display = "none";  // Cache le contenu complet
                buttons[0].style.display = "inline";  // Affiche le bouton "Lire l'article"
                buttons[1].style.display = "none";  // Cache le bouton "Fermer l'article"
            }
        }
    </script>
</body>
</html>
