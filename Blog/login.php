<?php
// Démarrer la session
session_start(); // Permet de démarrer une session, utile pour suivre l'utilisateur au cours de sa navigation

// Vérifier si l'utilisateur est déjà connecté, sinon rediriger vers le tableau de bord
if (isset($_SESSION['user_id'])) { // Vérifie si l'utilisateur a déjà une session active
    header("Location: dashboard.php"); // Si l'utilisateur est connecté, il est redirigé vers le tableau de bord
    exit(); // Interrompt le script pour empêcher toute autre exécution
}

$serveur = "localhost"; // Définition de l'hôte de la base de données
$utilisateur = "root"; // Nom d'utilisateur pour la connexion à la base de données
$mot_de_passe = "root"; // Mot de passe pour la connexion à la base de données
$base_de_donnees = "blog_db"; // Nom de la base de données à laquelle se connecter

try {
    // Connexion à la base de données
    $base = new PDO("mysql:host=$serveur;dbname=$base_de_donnees;charset=utf8", $utilisateur, $mot_de_passe); // Connexion à MySQL via PDO
    $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active le mode de gestion des erreurs

    // Récupérer les données du formulaire (assurez-vous que le formulaire envoie ces champs)
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Vérifie si le formulaire a été soumis
        $email = trim($_POST['email']); // Récupère et nettoie l'email
        $password = trim($_POST['password']); // Récupère et nettoie le mot de passe

        // Vérifier si les champs ne sont pas vides
        if (!empty($email) && !empty($password)) { // Vérifie que les champs email et mot de passe sont remplis
            // Requête pour vérifier l'utilisateur
            $stmt = $base->prepare("SELECT id, password FROM inscriptions WHERE email = :email"); // Prépare la requête pour chercher l'utilisateur dans la base de données
            $stmt->bindParam(':email', $email); // Lie l'email dans la requête
            $stmt->execute(); // Exécute la requête
            $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère les données de l'utilisateur

            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($user && password_verify($password, $user['password'])) { // Vérifie si l'utilisateur existe et si le mot de passe est valide
                // L'utilisateur est authentifié, stocker l'id dans la session
                $_SESSION['user_id'] = $user['id']; // Enregistre l'id de l'utilisateur dans la session
                header("Location: dashboard.php"); // Redirige vers le tableau de bord
                exit(); // Interrompt le script après la redirection
            } else {
                // Si les identifiants sont incorrects
                $error_message = "Identifiants incorrects, veuillez réessayer."; // Message d'erreur si l'utilisateur ou le mot de passe est incorrect
            }
        } else {
            // Si les champs sont vides
            $error_message = "Veuillez remplir tous les champs."; // Message d'erreur si l'un des champs est vide
        }
    }
} catch (PDOException $e) {
    // Erreur de connexion à la base de données
    $error_message = "Erreur de connexion à la base de données. " . $e->getMessage(); // Message d'erreur si la connexion à la base de données échoue
}

// Affichage de l'erreur si elle existe
if (isset($error_message)) { // Si une erreur est définie
    echo "<div style='color: red; text-align: center;'>$error_message</div>"; // Affiche le message d'erreur en rouge
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Spécifie le jeu de caractères utilisé dans la page -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Assure la compatibilité mobile de la page -->
    <title>Connexion</title> <!-- Titre de la page -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> <!-- Lien vers la feuille de style Bootstrap -->
    <link rel="stylesheet" type="text/css" href="styleformulaireinscription.css"> <!-- Lien vers le fichier CSS personnalisé -->
</head>
<body>
    <div class="container"> <!-- Conteneur Bootstrap -->
        <h2 class="text-center">Connexion</h2> <!-- Titre centré -->
        <p class="text-center">Veuillez vous connecter pour accéder à votre compte</p> <!-- Sous-titre centré -->

        <?php if (isset($error_message)): ?> <!-- Si un message d'erreur existe -->
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div> <!-- Affiche le message d'erreur -->
        <?php endif; ?>

        <form action="login.php" method="POST"> <!-- Formulaire d'authentification -->
            <div class="mb-3"> <!-- Champ email -->
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required> <!-- Champ de saisie email -->
            </div>
            <div class="mb-3"> <!-- Champ mot de passe -->
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required> <!-- Champ de saisie mot de passe -->
            </div>
            <div class="text-center"> <!-- Bouton de soumission -->
                <button type="submit" class="btn btn-primary">Se connecter</button> <!-- Bouton de connexion -->
            </div>
        </form>

        <div class="text-center mt-3"> <!-- Lien vers la page d'inscription -->
            <p>Pas encore de compte ? <a href="Formulaireinscription.php">Inscrivez-vous ici</a></p> <!-- Lien d'inscription -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Inclusion du script Bootstrap -->
</body>
</html>
