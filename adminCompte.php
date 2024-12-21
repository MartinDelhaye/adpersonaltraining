<?php
include("config/config.php");

// Vérifie si l'utilisateur est connecté
isUserLoggedIn();

// Initialisation de la variable de session 'Form'
if (!isset($_SESSION['Form'])) {
    $_SESSION['Form'] = [];
}

// Récupère l'ID de l'utilisateur depuis la session
$userId = $_SESSION['id_users']; 

// Récupère les informations de l'utilisateur 
$userData = obtenirDonnees("*", "users", "id_users = " . intval($userId), "", 'fetch');

// Récupère les informations des utilisateurs
$tabUsers = obtenirDonnees("*", "users", "id_users != " . intval($userId), "", 'fetchAll');

// Récupère le nom d'utilisateur et le rôle
$username = $userData['username'];
$userRole = $userData['role']; 


// Initialise les messages d'erreur et de succès
$messages = [
    'error' => '',
    'success' => ''
];

$tabInputSelectUsers = prepareTabInputSelect($tabUsers,'users');

// Copie $tabInputSelectUsers pour le modifier sans affecter l'original
$tabInputsModifRoleUser = $tabInputSelectUsers;

// Étape 2 : Insérer l'élément à la position 2 dans la copie
array_splice($tabInputsModifRoleUser, 2, 0,
    [["select", "select", "", "new_role", ' required="required"', [
        ["value" => "admin", "texte" => "Admin"],
        ["value" => "user", "texte" => "User"]
    ], "Rôle : "]]
);

// Copie $tabInputSelectUsers pour le modifier sans affecter l'original
$tabInputsSupprUser = $tabInputSelectUsers;

// Étape 2 : Insérer l'élément à la position 2 dans la copie
array_splice($tabInputsSupprUser, 2, 0, [["hidden", "", "", "delete_user_id", "", "Reset", ""]]);



$tabInputsNewUsername = [
    ["reset", "", "", "reset", "", "Reset", ""],
    ["text", "new_username", "", "new_username", "", htmlspecialchars($username), "Nom : "],
    ["submit", "", "", "submit", "onclick='return confirmForm();'", "Validez", ""]
    
];

$tabInputsNewPassword = [
    ["reset", "", "", "reset", "", "Reset", ""],
    ["password", "current_password", "", "current_password", "","" , "Ancien mot de passe :"],
    ["password", "new_password", "", "new_password", "", "", "Nouveau mot de passe :"],
    ["password", "confirm_new_password", "", "confirm_new_password", "", "", "Confirmer le nouveau mot de passe :"],
    ["submit", "", "", "submit", "", "Validez", ""]
    
];


$tabInputsNewUser = [
    ["text", "new_user_username", "", "new_user_username", "", "", "Nom d'utilisateur :"],
    ["password", "new_user_password", "", "new_user_password", "", "", "Mot de passe :"],
    ["select", "select", "", "new_user_role", ' required="required"', [
        ["value" => "admin", "texte" => "Admin"],
        ["value" => "user", "texte" => "User"]
    ], "Rôle : "],
    ["submit", "", "", "submit", "", "Ajouter l'utilisateur", ""]
];




// Affichage des messages d'erreur ou de succès
if (isset($_SESSION['error_message'])) {
    $messages['error'] = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if (isset($_SESSION['success_message'])) {
    $messages['success'] = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}





// Traitement des modifications
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie si le formulaire a déjà été soumis
    if ($_SESSION['Form'] === $_POST) {
        // Evite le traitement si le formulaire est le même que celui déjà soumis
        $messages['info'] = "Le formulaire a déjà été soumis.";
    } else {
        // Traitement du formulaire
        // Modification du nom d'utilisateur
        if (isset($_POST['new_username'])) {
            $newUsername = trim($_POST['new_username']);

            if (!empty($newUsername)) {
                $stmt = $bdd->prepare("SELECT COUNT(*) FROM users WHERE username = :username AND id_users != :id_users");
                $stmt->bindParam(':username', $newUsername);
                $stmt->bindParam(':id_users', $userId);
                $stmt->execute();
                $usernameExists = $stmt->fetchColumn();

                if ($usernameExists) {
                    $messages['error'] = "Ce nom d'utilisateur est déjà utilisé.";
                } else {
                    $stmt = $bdd->prepare("UPDATE users SET username = :new_username WHERE id_users = :id_users");
                    $stmt->bindParam(':new_username', $newUsername);
                    $stmt->bindParam(':id_users', $userId);
                    $stmt->execute();

                    $username = $newUsername; // Mise à jour du nom d'utilisateur affiché
                    $messages['success'] = "Nom d'utilisateur mis à jour avec succès.";
                }
            } else {
                $messages['error'] = "Le nom d'utilisateur ne peut pas être vide.";
            }
        }

        // Modification du mot de passe
        if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_new_password'])) {
            $currentPassword = trim($_POST['current_password']);
            $newPassword = trim($_POST['new_password']);
            $confirmNewPassword = trim($_POST['confirm_new_password']);

            if (!empty($currentPassword) && !empty($newPassword) && !empty($confirmNewPassword)) {
                $stmt = $bdd->prepare("SELECT password FROM users WHERE id_users = :id_users");
                $stmt->bindParam(':id_users', $userId);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($currentPassword, $user['password'])) {
                    if ($newPassword === $confirmNewPassword) {
                        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $stmt = $bdd->prepare("UPDATE users SET password = :new_password WHERE id_users = :id_users");
                        $stmt->bindParam(':new_password', $newPasswordHash);
                        $stmt->bindParam(':id_users', $userId);
                        $stmt->execute();

                        $messages['success'] = "Mot de passe mis à jour avec succès.";
                    } else {
                        $messages['error'] = "Les nouveaux mots de passe ne correspondent pas.";
                    }
                } else {
                    $messages['error'] = "Le mot de passe actuel est incorrect.";
                }
            } else {
                $messages['error'] = "Tous les champs du mot de passe doivent être remplis.";
            }
        }

        // Gestion des utilisateurs pour superadmins
        if ($userRole === 'superadmin') {
            // Modifier le rôle des autres admins
            if (isset($_POST['new_role']) && isset($_POST['id_users'])) {
                $modifyRoleUserId = intval($_POST['id_users']);
                $newRole = trim($_POST['new_role']);

                // Récupère le rôle actuel de l'utilisateur
                $stmt = $bdd->prepare("SELECT role FROM users WHERE id_users = :user_id");
                $stmt->bindParam(':user_id', $modifyRoleUserId);
                $stmt->execute();
                $currentRole = $stmt->fetchColumn();

                // Vérifie si le nouveau rôle est différent de l'actuel
                if ($newRole === $currentRole) {
                    $messages['error'] = "Le rôle sélectionné est déjà le rôle actuel de cet utilisateur.";
                } elseif (!empty($newRole) && in_array($newRole, ['admin', 'user'])) {
                    $stmt = $bdd->prepare("UPDATE users SET role = :new_role WHERE id_users = :user_id");
                    $stmt->bindParam(':new_role', $newRole);
                    $stmt->bindParam(':user_id', $modifyRoleUserId);
                    $stmt->execute();

                    $messages['success'] = "Rôle mis à jour avec succès.";
                } else {
                    $messages['error'] = "Rôle invalide.";
                }
            }

            // Supprimer des utilisateurs
            if (isset($_POST['delete_user_id'])) {
                $deleteUserId = intval($_POST['id_users']);

                if ($deleteUserId != $userId) {
                    $stmt = $bdd->prepare("DELETE FROM users WHERE id_users = :user_id");
                    $stmt->bindParam(':user_id', $deleteUserId);
                    $stmt->execute();

                    $messages['success'] = "Utilisateur supprimé avec succès.";
                } else {
                    $messages['error'] = "Vous ne pouvez pas vous supprimer vous-même.";
                }
            }

            // Ajouter des utilisateurs
            if (isset($_POST['new_user_username']) && isset($_POST['new_user_password']) && isset($_POST['new_user_role'])) {
                $newUserUsername = trim($_POST['new_user_username']);
                $newUserPassword = trim($_POST['new_user_password']);
                $newUserRole = trim($_POST['new_user_role']);

                if (!empty($newUserUsername) && !empty($newUserPassword) && in_array($newUserRole, ['admin', 'user'])) {
                    $stmt = $bdd->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
                    $stmt->bindParam(':username', $newUserUsername);
                    $stmt->execute();
                    $usernameExists = $stmt->fetchColumn();

                    if ($usernameExists) {
                        $messages['error'] = "Ce nom d'utilisateur est déjà utilisé.";
                    } else {
                        $newUserPasswordHash = password_hash($newUserPassword, PASSWORD_DEFAULT);
                        $stmt = $bdd->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
                        $stmt->bindParam(':username', $newUserUsername);
                        $stmt->bindParam(':password', $newUserPasswordHash);
                        $stmt->bindParam(':role', $newUserRole);
                        $stmt->execute();

                        $messages['success'] = "Nouvel utilisateur ajouté avec succès.";
                    }
                } else {
                    $messages['error'] = "Tous les champs doivent être remplis correctement.";
                }
            }
        }
        // Stocke les données du formulaire dans la session pour éviter les soumissions répétées
        $_SESSION['Form'] = $_POST;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php infometa(); ?>
    <meta name="description" content="Administration du Site AD Personal Training" />
    <title>Compte utilisateur</title>
    <style>
        /* Ajoute tes styles ici */
    </style>
</head>
<body>
    <main class="color-main padding-2 flex column gap-1 align-item-center justify-content-center">
        <?php
        // Affiche un message de bienvenue avec le nom d'utilisateur
        afficheTitre(1, "Page de modification du compte : " . htmlspecialchars($username), "texte-center", "");   
        echo "<a href='admin.php' title='retour accueil admin' class=' texte-center action-button'>Retour page d'Administration</a>";
        // Affiche les messages d'erreur et de succès
        if ($messages['error']) {
            echo "<p class='texte-center' style='color: red;'>". htmlspecialchars($messages['error']) ."</p>";
        }
        if ($messages['success']) {
            echo "<p class='texte-center' style='color: green;'>". htmlspecialchars($messages['success']) ."</p>";
        }
        ?>
        <div class="flex width-100 space-between texte-center">
            <section class="flex column "> 
                <h2>Gestion du compte : </h2>
                <!-- Bloc pour modifier le nom d'utilisateur -->
                <div class="flex column">
                    <h3>Modifier le nom d'utilisateur : </h3>
                    <?php 
                    afficheFormComplet("adminCompte.php", "POST", $tabInputsNewUsername, '');
                    ?>                
                </div>
                <!-- Bloc pour modifier le mot de passe -->
                <div class="flex column">
                    <h3>Modifier le mot de passe : </h3>
                    <?php 
                    afficheFormComplet("adminCompte.php", "POST", $tabInputsNewPassword, '');
                    ?> 
                </div>
            </section>
            <!-- Bloc pour gérer les autres utilisateurs -->
            <?php if ($userRole === 'superadmin'): ?>
            <section class="flex column align-item-center justify-content-center"> 
                <h2>Gestion des utilisateurs : </h2>
                <!-- Modifier les rôles des utilisateurs -->
                <?php 
                if (!empty($tabInputsModifRoleUser)) {
                    afficheFormSelect("Modifier le rôle d'un utilisateur :", ["adminCompte.php", "POST", $tabInputsModifRoleUser], "users-moddif");
                }                  

                // Supprimer des utilisateurs
                if (!empty($tabInputsSupprUser )) {
                    afficheFormSelect("Supprimer un utilisateur :", ["adminCompte.php", "POST", $tabInputsSupprUser], "users-suppr");
                }
                
                // Ajouter des utilisateurs 
                if (!empty($tabInputsNewUser)) {
                    afficheFormSelect("Ajouter un utilisateur :", ["adminCompte.php", "POST", $tabInputsNewUser], "users-ajout");
                }
                ?>                    
            </section>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

