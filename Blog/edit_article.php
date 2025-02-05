<?php
// Démarre la session, permettant de stocker des informations utilisateur sur plusieurs pages
session_start();

// Vérifie si l'utilisateur est connecté (si l'ID de l'utilisateur est stocké dans la session)
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, il est redirigé vers la page de connexion
    header("Location: login.php");
    exit();
}

// Définition des informations de connexion à la base de données
$serveur = "localhost"; // Adresse du serveur de la base de données
$utilisateur = "root"; // Utilisateur pour la connexion à la base de données
$mot_de_passe = "root"; // Mot de passe de l'utilisateur de la base de données
$base_de_donnees = "blog_db"; // Nom de la base de données utilisée

try {
    // Connexion à la base de données en utilisant PDO (PHP Data Object)
    $base = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    // Définition du mode de gestion des erreurs de PDO pour les afficher en cas de problème
    $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la connexion échoue, un message d'erreur est affiché et l'exécution du script s'arrête
    die("Erreur : " . $e->getMessage());
}

// Vérifie si un ID d'article est passé en paramètre dans l'URL (par exemple edit_article.php?id=1)
if (isset($_GET['id'])) {
    // Récupère l'ID de l'article depuis l'URL
    $article_id = $_GET['id'];

    // Récupère les informations de l'article à modifier à partir de la base de données
    $stmt = $base->prepare("SELECT * FROM articles WHERE id = :id AND user_id = :user_id");
    // Associe l'ID de l'article et l'ID de l'utilisateur connecté à la requête
    $stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    // Exécute la requête pour récupérer l'article
    $stmt->execute();
    // Récupère les résultats sous forme de tableau associatif
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si l'article n'existe pas ou l'utilisateur n'a pas l'autorisation de le modifier, on arrête le script avec un message d'erreur
    if (!$article) {
        die("Article non trouvé ou vous n'avez pas l'autorisation de modifier cet article.");
    }
} else {
    // Si aucun ID d'article n'est spécifié dans l'URL, on arrête le script avec un message d'erreur
    die("Aucun ID d'article spécifié.");
}

// Si le formulaire a été soumis (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données envoyées par le formulaire
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Prépare la requête pour mettre à jour l'article dans la base de données
    $stmt = $base->prepare("UPDATE articles SET title = :title, content = :content WHERE id = :id");
    // Associe les valeurs du formulaire aux paramètres de la requête
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
    // Exécute la mise à jour de l'article dans la base de données
    $stmt->execute();

    // Redirige l'utilisateur vers le tableau de bord après la mise à jour
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'article</title>
    <!-- Lien vers le fichier CSS de Bootstrap pour le style de la page -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <!-- Titre de la page -->
        <h2>Modifier l'article</h2>

        <!-- Formulaire pour modifier l'article -->
        <form action="edit_article.php?id=<?= $article['id']; ?>" method="POST">
            <!-- Champ pour le titre de l'article -->
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($article['title']); ?>" required>
            </div>
            <!-- Champ pour le contenu de l'article -->
            <div class="mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea id="content" name="content" class="form-control" rows="5" required><?= htmlspecialchars($article['content']); ?></textarea>
            </div>
            <!-- Bouton pour soumettre le formulaire -->
            <button type="submit" class="btn btn-success">Mettre à jour</button>
        </form>
    </div>
</body>
</html>
