<?php
// Vérifier si le formulaire a été soumis en vérifiant la présence de "tabName" dans la requête POST
if (!isset($_POST["tabName"])) {
    // Redirection vers la page d'administration si le formulaire n'a pas été soumis
    header('Location: admin.php');
    exit(); // Terminer l'exécution du script après la redirection
}

// Inclusion du fichier de configuration pour la connexion à la base de données
include("config/config.php");


// Activer le buffering de sortie pour gérer les en-têtes HTTP
ob_start();

// Vérifier si l'utilisateur est connecté, sinon rediriger
isUserLoggedIn();

// Récupération de l'identifiant de l'élément à modifier
$id_value = $_POST["id_" . $_POST["tabName"]];

// Préparation de la condition pour la requête SQL en fonction du type de l'identifiant
$condition = is_numeric($id_value) ? "id_" . $_POST["tabName"] . " = $id_value" : "id_" . $_POST["tabName"] . " = '$id_value'";
$type_param = is_numeric($id_value) ? PDO::PARAM_INT : PDO::PARAM_STR;

// Récupération des données actuelles de la base pour l'élément à modifier
$aModif = obtenirDonnees("*", $_POST["tabName"], $condition, '', 'fetch');

// Initialisation de la chaîne de requête SET pour la mise à jour
$requete_SET = "";

// Vérification si la position de l'élément a changé
if ((isset($_POST["pos_init"])) && ($_POST["pos_init"] !== $_POST["pos_" . $_POST["tabName"]])) {
    // Récupération de tous les éléments triés par position
    $tabElements = obtenirDonnees("*", $_POST["tabName"], '', 'pos_' . $_POST["tabName"], 'fetchAll');

    if ($_POST["pos_init"] > $_POST["pos_" . $_POST["tabName"]]) {
        // Si la nouvelle position est plus haute, incrémente les positions des éléments existants
        foreach ($tabElements as $element) {
            if (
                $element['id_' . $_POST["tabName"]] != $id_value &&
                $element['pos_' . $_POST["tabName"]] >= $_POST["pos_" . $_POST["tabName"]] &&
                $element['pos_' . $_POST["tabName"]] < $_POST["pos_init"]
            ) {
                $nouvelle_pos = $element['pos_' . $_POST["tabName"]] + 1;
                $requete_preparee = $bdd->prepare('UPDATE ' . $_POST["tabName"] . ' SET pos_' . $_POST["tabName"] . '=:nouvelle_pos WHERE id_' . $_POST["tabName"] . '=:id_element');
                $requete_preparee->bindValue(':nouvelle_pos', $nouvelle_pos, PDO::PARAM_INT);
                $requete_preparee->bindValue(':id_element', $element['id_' . $_POST["tabName"]], PDO::PARAM_INT);
                $requete_preparee->execute();
            }
        }
    } else {
        // Si la nouvelle position est plus basse, décrémente les positions des éléments existants
        foreach ($tabElements as $element) {
            if (
                $element['id_' . $_POST["tabName"]] != $id_value &&
                $element['pos_' . $_POST["tabName"]] > $_POST["pos_init"] &&
                $element['pos_' . $_POST["tabName"]] <= $_POST["pos_" . $_POST["tabName"]]
            ) {
                $nouvelle_pos = $element['pos_' . $_POST["tabName"]] - 1;
                $requete_preparee = $bdd->prepare('UPDATE ' . $_POST["tabName"] . ' SET pos_' . $_POST["tabName"] . '=:nouvelle_pos WHERE id_' . $_POST["tabName"] . '=:id_element');
                $requete_preparee->bindValue(':nouvelle_pos', $nouvelle_pos, PDO::PARAM_INT);
                $requete_preparee->bindValue(':id_element', $element['id_' . $_POST["tabName"]], PDO::PARAM_INT);
                $requete_preparee->execute();
            }
        }
    }
}

// Préparation de la chaîne de mise à jour SET en fonction du type de la table
if ($_POST["tabName"] == "avantage" || $_POST["tabName"] == "engagement") {
    $requete_SET .= "titre_" . $_POST["tabName"] . "=:titre_" . $_POST["tabName"] . ", texte_" . $_POST["tabName"] . "=:texte_" . $_POST["tabName"] . ", pos_" . $_POST["tabName"] . "=:pos_" . $_POST["tabName"];
} elseif ($_POST["tabName"] == "temoignage") {
    $requete_SET .= "nom_" . $_POST["tabName"] . "=:nom_" . $_POST["tabName"] . ", prenom_" . $_POST["tabName"] . "=:prenom_" . $_POST["tabName"] . ", texte_" . $_POST["tabName"] . "=:texte_" . $_POST["tabName"] . ", metier_" . $_POST["tabName"] . "=:metier_" . $_POST["tabName"] . ", age_" . $_POST["tabName"] . "=:age_" . $_POST["tabName"] . ", pos_" . $_POST["tabName"] . "=:pos_" . $_POST["tabName"];

    // Gestion du téléchargement de l'image pour le témoignage
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["img_" . $_POST["tabName"]])) {
        if ($_FILES["img_" . $_POST["tabName"]]["error"] === UPLOAD_ERR_OK) {
            $requete_SET .= ", img_temoignage=:img_temoignage";
            // Suppression du fichier d'image existant s'il y en a un
            if (file_exists($chemin_absolu_site . "/Image/Temoignage/" . $aModif["img_temoignage"])) {
                unlink($chemin_absolu_site . "/Image/Temoignage/" . $aModif["img_temoignage"]);
            }
            // Téléchargement de la nouvelle image et obtention de l'URL
            $url_BDD = downloadImageAndGetURL($_SERVER, $_POST, $_FILES, $_POST["tabName"]);
        }
    }
} elseif ($_POST["tabName"] == "reseaux_sociaux") {
    $requete_SET .= "nom_" . $_POST["tabName"] . "=:nom_" . $_POST["tabName"] . ", lien_" . $_POST["tabName"] . "=:lien_" . $_POST["tabName"] . ", pos_" . $_POST["tabName"] . "=:pos_" . $_POST["tabName"];

    // Gestion du téléchargement du logo pour les réseaux sociaux
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["logo_" . $_POST["tabName"]])) {
        if ($_FILES["logo_" . $_POST["tabName"]]["error"] === UPLOAD_ERR_OK) {
            $requete_SET .= ", logo_reseaux_sociaux=:logo_reseaux_sociaux";
            // Suppression du fichier d'image existant s'il y en a un
            if (file_exists($chemin_absolu_site . "/Image/Reseaux_Sociaux/" . $aModif["logo_reseaux_sociaux"])) {
                unlink($chemin_absolu_site . "/Image/Reseaux_Sociaux/" . $aModif["logo_reseaux_sociaux"]);
            }
            // Téléchargement de la nouvelle image et obtention de l'URL
            $url_BDD = downloadImageAndGetURL($_SERVER, $_POST, $_FILES, $_POST["tabName"]);
        }
    }
} elseif ($_POST["tabName"] == "images") {
    $requete_SET .= "chemin_images=:chemin_images";

    // Gestion du téléchargement de l'image pour le témoignage
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["chemin_" . $_POST["tabName"]])) {
        if ($_FILES["chemin_" . $_POST["tabName"]]["error"] === UPLOAD_ERR_OK) {
            $requete_SET .= ", chemin_images=:chemin_images";
            // Suppression du fichier d'image existant s'il y en a un
            if (file_exists($chemin_absolu_site . "/Image/Img_BDD/" . $aModif["chemin_images"])) {
                unlink($chemin_absolu_site . "/Image/Img_BDD/" . $aModif["chemin_images"]);
            }
            // Téléchargement de la nouvelle image et obtention de l'URL
            $url_BDD = downloadImageAndGetURL($_SERVER, $_POST, $_FILES, $_POST["tabName"]);
        }
    }
}elseif ($_POST["tabName"] == "textes") {
    $requete_SET .= "contenu=:contenu";
}

// Préparation de la requête SQL pour mettre à jour l'élément
$requete_preparee = $bdd->prepare('UPDATE ' . $_POST["tabName"] . ' SET ' . $requete_SET . ' WHERE id_' . $_POST["tabName"] . '=:id_' . $_POST["tabName"] .';');

// Liaison des paramètres pour l'exécution de la requête SQL
$requete_preparee->bindValue(':id_' . $_POST["tabName"], $id_value, $type_param);

if ($_POST["tabName"] == "avantage" || $_POST["tabName"] == "engagement") {
    $requete_preparee->bindValue(':titre_' . $_POST["tabName"], $_POST["titre_" . $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':texte_' . $_POST["tabName"], $_POST["texte_" . $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':pos_' . $_POST["tabName"], $_POST["pos_" . $_POST["tabName"]], PDO::PARAM_INT);
} elseif ($_POST["tabName"] == "temoignage") {
    $requete_preparee->bindValue(':nom_' . $_POST["tabName"], $_POST["nom_" . $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':prenom_' . $_POST["tabName"], $_POST["prenom_" . $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':texte_' . $_POST["tabName"], $_POST["texte_" . $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':metier_' . $_POST["tabName"], $_POST["metier_" . $_POST["tabName"]], PDO::PARAM_STR);

    // Gestion de l'âge : convertir la valeur vide en NULL
    $age = !empty($_POST["age_" . $_POST["tabName"]]) ? $_POST["age_" . $_POST["tabName"]] : null;
    $requete_preparee->bindValue(':age_' . $_POST["tabName"], $age, $age === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

    $requete_preparee->bindValue(':pos_' . $_POST["tabName"], $_POST["pos_" . $_POST["tabName"]], PDO::PARAM_INT);

    // Liaison de l'image si elle a été téléchargée
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["img_" . $_POST["tabName"]])) {
        if ($_FILES["img_" . $_POST["tabName"]]["error"] === UPLOAD_ERR_OK) {
            $requete_preparee->bindValue(':img_temoignage', $url_BDD, PDO::PARAM_STR);
        }
    }
} elseif ($_POST["tabName"] == "reseaux_sociaux") {
    $requete_preparee->bindValue(':nom_' . $_POST["tabName"], $_POST["nom_" . $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':lien_' . $_POST["tabName"], $_POST["lien_" . $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':pos_' . $_POST["tabName"], $_POST["pos_" . $_POST["tabName"]], PDO::PARAM_INT);

    // Liaison du logo si il a été téléchargé
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["logo_" . $_POST["tabName"]])) {
        if ($_FILES["logo_" . $_POST["tabName"]]["error"] === UPLOAD_ERR_OK) {
            $requete_preparee->bindValue(':logo_reseaux_sociaux', $url_BDD, PDO::PARAM_STR);
        }
    }
} elseif ($_POST["tabName"] == "images") {
    // Liaison de l'image si il a été téléchargé
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["chemin_" . $_POST["tabName"]])) {
        if ($_FILES["chemin_" . $_POST["tabName"]]["error"] === UPLOAD_ERR_OK) {
            $requete_preparee->bindValue(':chemin_images', $url_BDD, PDO::PARAM_STR);
        }
    }
} elseif ($_POST["tabName"] == "textes") {
    $requete_preparee->bindValue(':contenu', $_POST["contenu"], PDO::PARAM_STR);
}

// Exécution de la requête SQL pour mettre à jour l'élément
try {
    $requete_preparee->execute();
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}

// Mise à jour de l'image de favicon
if(isset($_FILES['faviconFolder']['tmp_name']) && !empty($_FILES['faviconFolder']['tmp_name'][0])) {
    echo "<br> coucou";
    $uploadDirectory = rtrim($chemin_absolu_site."/Image/favicon_io/", '/') . '/';

    // Supprimer les fichiers existants dans le dossier
    array_map('unlink', glob($uploadDirectory . '*'));

    // Parcourir les fichiers téléchargés et les déplacer vers le dossier
    foreach ($_FILES['faviconFolder']['tmp_name'] as $key => $tmpName) {
        $filename = $_FILES['faviconFolder']['name'][$key];
        move_uploaded_file($tmpName, $uploadDirectory . $filename);
    }
}


// Redirection vers la page d'administration après la mise à jour
header('Location: admin.php');
exit();
?>
