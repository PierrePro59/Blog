<?php
session_start(); // Démarre une session pour pouvoir utiliser les variables de session
?>

<!DOCTYPE html>
<html lang="fr"> <!-- Définit la langue du document en français -->
<head>
    <meta charset="UTF-8"> <!-- Définit le jeu de caractères à UTF-8 pour supporter les caractères spéciaux -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Assure une bonne mise en page sur tous les appareils (responsive) -->
    <title>Accueil</title> <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <link rel="stylesheet" href="./style.css"> <!-- Lien vers le fichier CSS local pour la mise en page -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> <!-- Lien vers la bibliothèque Bootstrap pour la mise en page -->
</head>
<body>
    <div class="container mt-3"> <!-- Conteneur Bootstrap avec une marge en haut -->
        <div class="d-flex justify-content-end auth-buttons"> <!-- Conteneur pour les boutons de connexion/déconnexion alignés à droite -->
            <?php if (isset($_SESSION["user"])): ?> <!-- Si un utilisateur est connecté -->
                <span class="me-2">Bienvenue, <strong><?php echo htmlspecialchars($_SESSION["user"]); ?></strong>!</span> <!-- Affiche le nom de l'utilisateur connecté -->
                <a href="logout.php" class="btn btn-danger">Déconnexion</a> <!-- Lien pour se déconnecter -->
            <?php else: ?> <!-- Si aucun utilisateur n'est connecté -->
                <a href="Formulaireinscription.php" target="_blank" class="btn btn-primary me-2">Inscription</a> <!-- Lien vers la page d'inscription -->
                <a href="login.php" target="_blank" class="btn btn-secondary">Connexion</a> <!-- Lien vers la page de connexion -->
            <?php endif; ?> <!-- Fin de la condition -->
        </div>
    </div>

    <div class="container mt-4">  <h2 class="text-center rainbow-title">Bienvenue sur mon blog</h2> </div> <!-- Titre centré pour l'accueil du blog -->

    <div id="carouselExampleAutoplaying" class="carousel slide mt-4" data-bs-ride="carousel"> <!-- Carousel Bootstrap avec auto-défilement -->
        <div class="carousel-inner"> <!-- Conteneur des éléments du carousel -->
            <div class="carousel-item active"> <!-- Première image du carousel active par défaut -->
                <img src="assets/image1.jpg" class="d-block w-100" alt="image 1"> <!-- Image du carousel -->
            </div>
            <div class="carousel-item"> <!-- Deuxième image du carousel -->
                <img src="assets/image2.png" class="d-block w-100" alt="image 2"> <!-- Image du carousel -->
            </div>
            <div class="carousel-item"> <!-- Troisième image du carousel -->
                <img src="assets/image3.jpg" class="d-block w-100" alt="Image 3"> <!-- Image du carousel -->
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev"> <!-- Bouton pour revenir à l'image précédente -->
            <span class="carousel-control-prev-icon" aria-hidden="true"></span> <!-- Icône de flèche vers la gauche -->
            <span class="visually-hidden">Previous</span> <!-- Texte caché pour l'accessibilité -->
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next"> <!-- Bouton pour passer à l'image suivante -->
            <span class="carousel-control-next-icon" aria-hidden="true"></span> <!-- Icône de flèche vers la droite -->
            <span class="visually-hidden">Next</span> <!-- Texte caché pour l'accessibilité -->
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Inclusion du fichier JavaScript Bootstrap pour les fonctionnalités du carousel -->
</body>
</html>
