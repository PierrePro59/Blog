<?php
// Démarre la session afin de pouvoir stocker et récupérer les informations de l'utilisateur
session_start();

// Connexion à la base de données
$serveur = "localhost"; // Adresse du serveur de la base de données
$utilisateur = "root"; // Nom d'utilisateur pour se connecter à la base de données
$mot_de_passe = "root"; // Mot de passe pour l'utilisateur de la base de données
$base_de_donnees = "blog_db"; // Nom de la base de données à utiliser

try {
    // Crée une instance PDO pour la connexion à la base de données avec les informations définies précédemment
    $base = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    // Définit les options de PDO pour afficher les erreurs sous forme d'exception
    $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la connexion échoue, l'exécution du script s'arrête et affiche l'erreur
    die("Erreur : " . $e->getMessage());
}

// Vérifie si une recherche a été effectuée par l'utilisateur via l'URL (paramètre 'query')
$results = []; // Initialise un tableau vide pour stocker les résultats de la recherche
if (isset($_GET['query']) && !empty($_GET['query'])) {
    // Si la recherche est présente et non vide, on prépare la recherche avec des jokers (pour permettre la recherche partielle)
    $search = "%" . $_GET['query'] . "%"; // Ajoute les symboles '%' pour une recherche partielle

    try {
        // Prépare la requête SQL pour rechercher dans la table 'inscriptions' par nom ou email
        // La requête recherche les utilisateurs dont le nom ou l'email correspond à la recherche
        $stmt = $base->prepare("SELECT id, nom, email FROM inscriptions WHERE nom LIKE ? OR email LIKE ?");
        // Exécute la requête avec les paramètres de recherche
        $stmt->execute([$search, $search]);
        // Récupère tous les résultats sous forme de tableau associatif
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Si la requête échoue, l'exécution s'arrête et affiche l'erreur
        die("Erreur lors de la recherche : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Définit le jeu de caractères pour la page HTML -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Assure que la page est responsive sur les appareils mobiles -->
    <title>Résultats de la recherche</title> <!-- Titre de la page -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> <!-- Lien vers la feuille de style Bootstrap -->
</head>
<body>
    <div class="container mt-4"> <!-- Conteneur principal avec un espacement en haut (mt-4) -->
        <h2 class="text-center">Résultats de la recherche</h2> <!-- Titre centré -->

        <?php if (empty($results)): ?> <!-- Si les résultats sont vides, afficher ce message -->
            <p class="text-center text-danger">Aucun utilisateur trouvé.</p> <!-- Message d'erreur en rouge si aucun utilisateur n'est trouvé -->
        <?php else: ?> <!-- Sinon, afficher les résultats sous forme de tableau -->
            <table class="table table-bordered table-striped"> <!-- Table avec bordures et lignes alternées -->
                <thead class="table-dark"> <!-- En-tête de la table avec un fond sombre -->
                    <tr>
                        <th>ID</th> <!-- Colonne pour l'ID de l'utilisateur -->
                        <th>Nom</th> <!-- Colonne pour le nom de l'utilisateur -->
                        <th>Email</th> <!-- Colonne pour l'email de l'utilisateur -->
                        <th>Action</th> <!-- Colonne pour les actions possibles sur chaque utilisateur -->
                    </tr>
                </thead>
                <tbody> <!-- Corps du tableau -->
                    <?php foreach ($results as $user): ?> <!-- Pour chaque utilisateur dans les résultats -->
                        <tr> <!-- Ligne du tableau -->
                            <td><?= $user['id']; ?></td> <!-- Affiche l'ID de l'utilisateur -->
                            <td><?= htmlspecialchars($user['nom']); ?></td> <!-- Affiche le nom de l'utilisateur en échappant les caractères spéciaux -->
                            <td><?= htmlspecialchars($user['email']); ?></td> <!-- Affiche l'email de l'utilisateur en échappant les caractères spéciaux -->
                            <td>
                                <!-- Bouton pour ajouter l'utilisateur en ami, lien vers la page 'add_friend.php' avec l'ID de l'utilisateur -->
                                <a href="add_friend.php?id=<?= $user['id']; ?>" class="btn btn-success btn-sm">Ajouter en ami</a>
                            </td>
                        </tr>
                    <?php endforeach; ?> <!-- Fin de la boucle foreach -->
                </tbody>
            </table> <!-- Fin du tableau -->
        <?php endif; ?> <!-- Fin de la condition si les résultats sont vides -->

        <!-- Bouton pour revenir au tableau de bord -->
        <div class="text-center mt-3">
            <a href="dashboard.php" class="btn btn-secondary">Retour au Tableau de Bord</a>
        </div>
    </div>
</body>
</html>
