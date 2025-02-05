<?php
session_start();  /* Démarre une session PHP, permettant de stocker des informations persistantes entre les pages, comme le pseudo ou le mot de passe de l'utilisateur */

/* Vérifie si le formulaire a été soumis avec les champs 'pseudo' et 'password' et si ces champs ne sont pas vides */
if (isset($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['pseudo']) && !empty($_POST['password'])) {
    /* Si les champs sont correctement remplis, les données sont stockées dans des variables de session */
    $_SESSION['nom'] = $_POST['nom'];  /* Stocke le nom de l'utilisateur dans la variable de session 'nom' */
    $_SESSION['prenom'] = $_POST['prenom'];  /* Stocke le prénom de l'utilisateur dans la variable de session 'prenom' */
    $_SESSION['pseudo'] = $_POST['pseudo'];  /* Stocke le pseudo de l'utilisateur dans la variable de session 'pseudo' */
    $_SESSION['password'] = $_POST['password'];  /* Stocke le mot de passe de l'utilisateur dans la variable de session 'password' */
}

/* Affiche toutes les variables de session pour déboguer et vérifier ce qui a été stocké dans la session */
var_dump($_SESSION);
?>

<!DOCTYPE html>  /* Déclare que le document est un fichier HTML5 */
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  /* Déclare la langue du document en français (important pour l'accessibilité et le référencement) */
<head>
    <title></title>  /* Titre de la page (actuellement vide) */
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  /* Définit l'encodage de caractères de la page en UTF-8 pour supporter tous les caractères spéciaux */
</head>
<body>
<h2>Veuillez saisir votre login et votre mot de passe</h2>  /* Titre principal de la page, demandant à l'utilisateur de se connecter */

<form action="4session.php" method="POST">  /* Formulaire d'envoi de données en POST vers la même page (4session.php) */
pseudo:<input type="text" name="pseudo" /><br /><br />  /* Champ de texte pour entrer le pseudo de l'utilisateur */
password:<input type="text" name="password" /><br /><br />  /* Champ de texte pour entrer le mot de passe de l'utilisateur */
<input type="submit" name="envoyer" value="Se connecter"/>  /* Bouton d'envoi du formulaire, avec le texte "Se connecter" */
<br /><br />

<?php
/* Vérifie si un message a été envoyé dans l'URL avec le paramètre 'message' ayant la valeur '1' */
if (isset($_GET['message']) && $_GET['message'] == '1') {
    /* Si ce paramètre existe, affiche un message d'erreur en rouge, indiquant que le pseudo est incorrect */
    echo "<span style='color:#ff0000'>Le pseudo est incorrect</span>";
}
?>
</form>
</body>
</html>