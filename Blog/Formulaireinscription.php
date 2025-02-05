<?php
// Inclure le fichier de connexion à la base de données
include('db.php'); // Ce fichier contient la connexion à la base de données

// Vérifie si le formulaire a été soumis via la méthode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données envoyées par le formulaire
    $nom = $_POST['nom']; // Récupère le nom
    $prenom = $_POST['prenom']; // Récupère le prénom
    $email = $_POST['email']; // Récupère l'email
    $pseudo = $_POST['pseudo']; // Récupère le pseudo
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Sécurise le mot de passe en le hachant

    // Vérifie si le pseudo ou l'email existe déjà dans la table inscriptions
    $query = "SELECT * FROM inscriptions WHERE pseudo = ? OR email = ?"; // La requête pour vérifier si l'email ou le pseudo existe déjà
    $stmt = $pdo->prepare($query); // Prépare la requête
    $stmt->execute([$pseudo, $email]); // Exécute la requête avec les paramètres pseudo et email
    $existingUser = $stmt->fetch(); // Récupère les résultats de la requête

    if ($existingUser) { // Si un utilisateur avec ce pseudo ou cet email existe déjà
        echo "Ce pseudo ou cet email est déjà utilisé."; // Affiche un message d'erreur
    } else {
        // Si le pseudo et l'email sont disponibles, on les insère dans la base de données
        $insertQuery = "INSERT INTO inscriptions (nom, prenom, email, pseudo, password) VALUES (?, ?, ?, ?, ?)"; // La requête pour insérer les nouvelles données
        $stmt = $pdo->prepare($insertQuery); // Prépare la requête d'insertion
        $stmt->execute([$nom, $prenom, $email, $pseudo, $password]); // Exécute la requête avec les valeurs récupérées
        echo "Inscription réussie ! Vous pouvez maintenant vous connecter."; // Affiche un message de succès
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Déclare le charset pour la page -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Assure que la page est responsive -->
    <title>Inscription</title> <!-- Le titre de la page affiché dans l'onglet du navigateur -->
    <link rel="stylesheet" type="text/css" href="styleformulaireinscription.css"> <!-- Lien vers le fichier CSS spécifique au formulaire -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> <!-- Lien vers la bibliothèque Bootstrap -->
</head>
<body>
    <div class="container"> <!-- Conteneur pour le contenu de la page -->
        <h2 class="text-center">Inscription</h2> <!-- Titre centré de la page -->
        <p class="text-center">Veuillez remplir tous les champs</p> <!-- Sous-titre centré -->

        <!-- Formulaire d'inscription -->
        <form action="Formulaireinscription.php" method="POST" name="formulaire" onsubmit="return verif()"> <!-- Formulaire qui envoie les données via la méthode POST -->
            <div class="mb-3"> <!-- Champ pour le nom -->
              <center><label for="nom" class="form-label">Nom</label></center> <!-- Label du champ Nom -->
              <center><input type="text" class="form-control" name="nom" id="nom" placeholder="Entrez un nom" required maxlength="25"></center> <!-- Champ de saisie pour le nom -->
            </div>

            <div class="mb-3"> <!-- Champ pour le prénom -->
              <center><label for="prenom" class="form-label">Prénom</label></center> <!-- Label du champ Prénom -->
              <center><input type="text" class="form-control" name="prenom" id="prenom" placeholder="Entrez un prénom" required maxlength="25"></center> <!-- Champ de saisie pour le prénom -->
            </div>

            <div class="mb-3"> <!-- Champ pour l'email -->
              <center><label for="email" class="form-label">Email</label></center> <!-- Label du champ Email -->
              <center><input type="email" class="form-control" name="email" id="email" placeholder="Entrez votre email" required></center> <!-- Champ de saisie pour l'email -->
            </div>

            <div class="mb-3"> <!-- Champ pour le pseudo -->
              <center><label for="pseudo" class="form-label">Pseudo</label></center> <!-- Label du champ Pseudo -->
              <center><input type="text" class="form-control" name="pseudo" id="pseudo" placeholder="Choisissez un pseudo unique" required onkeyup="checkPseudo()"></center> <!-- Champ de saisie pour le pseudo, vérifie le pseudo à chaque frappe -->
              <span id="pseudoError" class="error"></span> <!-- Affiche l'erreur si le pseudo est trop court -->
            </div>

            <div class="mb-3"> <!-- Champ pour le mot de passe -->
              <center><label for="password" class="form-label">Mot de passe</label></center> <!-- Label du champ Mot de passe -->
              <center><input type="password" class="form-control" name="password" id="password" placeholder="Entrez un mot de passe" required></center> <!-- Champ de saisie pour le mot de passe -->
            </div>

            <div class="text-center"> <!-- Bouton d'inscription centré -->
                <button type="submit" class="btn btn-primary">S'inscrire</button> <!-- Bouton pour soumettre le formulaire -->
            </div>

            <!-- Nouveau bouton ajouté pour le lien vers la page de connexion -->
            <div class="text-center mt-3"> <!-- Bouton vers la page de connexion -->
                <button type="button" class="btn btn-success" onclick="window.location.href='login.php'">Vous avez déjà un compte ?</button>
            </div>

        </form>

        <!-- Affichage des messages d'erreur -->
        <p class="text-center text-danger mt-3" id="messages"></p> <!-- Espace pour afficher les messages d'erreur -->
    </div>

    <script>
        function checkPseudo() { // Fonction pour vérifier la longueur du pseudo
            let pseudo = document.getElementById('pseudo').value; // Récupère la valeur du pseudo
            let pseudoError = document.getElementById('pseudoError'); // Sélectionne l'élément pour afficher l'erreur

            if (pseudo.length < 3) { // Si le pseudo a moins de 3 caractères
                pseudoError.textContent = "Le pseudo doit contenir au moins 3 caractères."; // Affiche un message d'erreur
            } else {
                pseudoError.textContent = ""; // Si le pseudo est valide, enlève le message d'erreur
            }
        }

        function verif() { // Fonction pour vérifier que le formulaire est valide avant soumission
            let pseudo = document.getElementById('pseudo').value; // Récupère la valeur du pseudo
            if (pseudo.length < 3) { // Si le pseudo a moins de 3 caractères
                alert("Veuillez choisir un pseudo valide."); // Affiche une alerte
                return false; // Empêche l'envoi du formulaire
            }
            return true; // Permet l'envoi du formulaire si le pseudo est valide
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Inclusion du fichier JavaScript Bootstrap pour activer les fonctionnalités des composants -->
</body>
</html>
