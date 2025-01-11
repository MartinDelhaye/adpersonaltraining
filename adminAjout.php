<?php
include("config/config.php");
include("scripts/fonction-admin.php");

isUserLoggedIn();

// Vérifier si le formulaire a été soumis
if (!isset($_POST["tabName"])) {
    // Redirection après la modification
    header('Location: admin.php');
    exit();
}

// Debugging information: Remove or comment out in production
print_r($_POST);
echo "<br>";
print_r($_FILES);
echo "<br>";

// Récupération des données de la table
$tabElements = obtenirDonnees("*", $_POST["tabName"], '', 'pos_'.$_POST["tabName"], 'fetchAll');

// Préparation des variables pour la requête SQL
$requete_Colonne = "";
$requete_VALUES = "";

// Vérification si la position a changé
if ($_POST["pos_init"] !== $_POST["pos_".$_POST["tabName"]]) {
    if ($_POST["pos_init"] > $_POST["pos_".$_POST["tabName"]]) {
        foreach ($tabElements as $element) {
            if ($element['pos_'.$_POST["tabName"]] >= $_POST["pos_".$_POST["tabName"]] &&
                $element['pos_'.$_POST["tabName"]] < $_POST["pos_init"]) {
                
                $nouvelle_pos = $element['pos_'.$_POST["tabName"]] + 1;
                $requete_preparee = $bdd->prepare('UPDATE '.$_POST["tabName"].' SET pos_'.$_POST["tabName"].'=:nouvelle_pos WHERE id_'.$_POST["tabName"].'=:id_element');
                $requete_preparee->bindValue(':nouvelle_pos', $nouvelle_pos, PDO::PARAM_INT);
                $requete_preparee->bindValue(':id_element', $element['id_'.$_POST["tabName"]], PDO::PARAM_INT);
                $requete_preparee->execute();
            }
        }
    } else {
        foreach ($tabElements as $element) {
            if ($element['pos_'.$_POST["tabName"]] > $_POST["pos_init"] &&
                $element['pos_'.$_POST["tabName"]] <= $_POST["pos_".$_POST["tabName"]]) {
                
                $nouvelle_pos = $element['pos_'.$_POST["tabName"]] - 1;
                $requete_preparee = $bdd->prepare('UPDATE '.$_POST["tabName"].' SET pos_'.$_POST["tabName"].'=:nouvelle_pos WHERE id_'.$_POST["tabName"].'=:id_element');
                $requete_preparee->bindValue(':nouvelle_pos', $nouvelle_pos, PDO::PARAM_INT);
                $requete_preparee->bindValue(':id_element', $element['id_'.$_POST["tabName"]], PDO::PARAM_INT);
                $requete_preparee->execute();
            }
        }
    }
}

// Préparation des variables pour la requête SQL et téléchargement de l'image si besoin
if ($_POST["tabName"] == "avantage" || $_POST["tabName"] == "engagement") {
    $requete_Colonne .= "titre_".$_POST["tabName"].", texte_" . $_POST["tabName"].", pos_" . $_POST["tabName"];
    $requete_VALUES .= ":titre_".$_POST["tabName"].", :texte_" . $_POST["tabName"].", :pos_" . $_POST["tabName"];
} elseif ($_POST["tabName"] == "temoignage") {
    $requete_Colonne .= "nom_".$_POST["tabName"].", prenom_".$_POST["tabName"].", texte_" . $_POST["tabName"].", metier_" . $_POST["tabName"].", age_" . $_POST["tabName"].", pos_" . $_POST["tabName"];
    $requete_VALUES .= ":nom_".$_POST["tabName"].", :prenom_".$_POST["tabName"].", :texte_" . $_POST["tabName"].", :metier_" . $_POST["tabName"].", :age_" . $_POST["tabName"].", :pos_" . $_POST["tabName"];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["img_".$_POST["tabName"]])) {
        if ($_FILES["img_".$_POST["tabName"]]["error"] === UPLOAD_ERR_OK) {
            $requete_Colonne .= ", img_".$_POST["tabName"];
            $requete_VALUES .= ", :img_".$_POST["tabName"];

            $url_BDD = downloadImageAndGetURL($_SERVER, $_POST, $_FILES, $_POST["tabName"]);
        } else {
            echo "L'image n'a pas pu être téléchargée 1";
        }
    } else {
        echo "L'image n'est pas dans _FILES <br>";
    }
} elseif ($_POST["tabName"] == "reseaux_sociaux") {
    $requete_Colonne .= "nom_".$_POST["tabName"].", lien_".$_POST["tabName"].", pos_" . $_POST["tabName"];
    $requete_VALUES .= ":nom_".$_POST["tabName"].", :lien_".$_POST["tabName"].", :pos_" . $_POST["tabName"];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["logo_".$_POST["tabName"]])) {
        if ($_FILES["logo_".$_POST["tabName"]]["error"] === UPLOAD_ERR_OK) {
            $requete_Colonne .= ", logo_".$_POST["tabName"];
            $requete_VALUES .= ", :logo_".$_POST["tabName"];

            $url_BDD = downloadImageAndGetURL($_SERVER, $_POST, $_FILES, $_POST["tabName"]);
        } else {
            echo "L'image n'a pas pu être téléchargée 1";
        }
    } else {
        echo "L'image n'est pas dans _FILES <br>";
    }
}

// Préparation de la requête pour insérer les données
$requete_preparee = $bdd->prepare('INSERT INTO '.$_POST["tabName"].'('.$requete_Colonne.') VALUES ('.$requete_VALUES.');');

// Liaison des paramètres pour l'insertion
if ($_POST["tabName"] == "avantage" || $_POST["tabName"] == "engagement") {
    $requete_preparee->bindValue(':titre_'. $_POST["tabName"], $_POST["titre_". $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':texte_'. $_POST["tabName"], $_POST["texte_". $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':pos_'. $_POST["tabName"], $_POST["pos_". $_POST["tabName"]], PDO::PARAM_INT);
} elseif ($_POST["tabName"] == "temoignage") {
    $requete_preparee->bindValue(':nom_'. $_POST["tabName"], $_POST["nom_". $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':prenom_'. $_POST["tabName"], $_POST["prenom_". $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':texte_'. $_POST["tabName"], $_POST["texte_". $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':metier_'. $_POST["tabName"], $_POST["metier_". $_POST["tabName"]], PDO::PARAM_STR);

    // Gestion de l'âge : convertir la valeur vide en NULL
    $age = !empty($_POST["age_". $_POST["tabName"]]) ? $_POST["age_". $_POST["tabName"]] : null;
    $requete_preparee->bindValue(':age_'. $_POST["tabName"], $age, $age === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

    $requete_preparee->bindValue(':pos_'. $_POST["tabName"], $_POST["pos_". $_POST["tabName"]], PDO::PARAM_INT);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["img_".$_POST["tabName"]])) {
        if ($_FILES["img_".$_POST["tabName"]]["error"] === UPLOAD_ERR_OK) {
            $requete_preparee->bindValue(':img_'. $_POST["tabName"], $url_BDD, PDO::PARAM_STR);
        }
    }
} elseif ($_POST["tabName"] == "reseaux_sociaux") {
    $requete_preparee->bindValue(':nom_'. $_POST["tabName"], $_POST["nom_". $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':lien_'. $_POST["tabName"], $_POST["lien_". $_POST["tabName"]], PDO::PARAM_STR);
    $requete_preparee->bindValue(':pos_'. $_POST["tabName"], $_POST["pos_". $_POST["tabName"]], PDO::PARAM_INT);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["logo_".$_POST["tabName"]])) {
        if ($_FILES["logo_".$_POST["tabName"]]["error"] === UPLOAD_ERR_OK) {
            $requete_preparee->bindValue(':logo_'. $_POST["tabName"], $url_BDD, PDO::PARAM_STR);
        }
    }
}

// Exécution de la requête pour insérer les données
$requete_preparee->execute();

// Redirection après l'insertion
header('Location: admin.php');
exit();

?>
