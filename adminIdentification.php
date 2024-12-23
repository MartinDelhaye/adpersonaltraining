<?php
include("config/config.php");

// Démarrer une session pour gérer l'authentification
// session_start();

startSession();

// Traitement de l'identification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Vérifie les champs obligatoires
    if (!empty($username) && !empty($password)) {
        // Obtient les informations de l'utilisateur
        $user = obtenirDonnees("id_users, username, password, role", "users", "username"." = '$username'", '', 'fetch');

        // Vérifie l'utilisateur existe et que le mdp est correct 
        if ($user && password_verify($password, $user['password'])) {
            // Stocker les informations d'authentification dans la session
            $_SESSION['id_users'] = $user['id_users'];
            $_SESSION['role'] = $user['role'];

            // Donne accès à l'utilisateur en fonction de son rôle (user : pas le droit d'accès, admin/superadmin : accès autorisé)
            if ($user['role'] !== 'user') {
                header("Location: admin.php");
                exit();
            } else {
                $error_message = "Accès refusé. Contactez l'administrateur.";
            }
        } else {
            $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php infometa(); ?>
    <meta name="description" content="Administration du Site AD Personal Training" />
    <title>Identification</title>
</head>
<body class="flex column align-item-center justify-content-center color-main">
    <main class="color-second padding-2 bordure-radius-20 width-67 text-white">
        <div>
            <?php
            afficheTitre(1, "Identification", "texte-center text-white", "");   
            ?>
            <form action="" method="POST" class="flex column gap-1 align-item-center justify-content-center">
                Login : <input type="text" name="username" required>
                Mot de passe : <input type="password" name="password" required>
                <input type="submit" value="Connexion">
            </form>

        </div>
        <?php
        if (isset($error_message)) {
            echo "<p class='texte-center' style='color: red;'>$error_message</p>";
        }
        ?>
        
    </main>
    <a href="index.php" title="retour accueil" class=" texte-center action-button">Retour accueil</a>
</body>
</html>
