<?php
// Vérifier si le formulaire a été soumis
if (!isset($_POST["tabName"])) {
    // Redirection après la modification
    header('Location: admin.php');
    exit();
}

// Inclusion du fichier de configuration et vérification de la connexion de l'utilisateur
include("config/config.php");
isUserLoggedIn();


// Récupération de l'identifiant en fonction du nom de la table et création de la condition de filtre
$id_value = $_POST["id_".$_POST["tabName"]];
$condition = is_numeric($id_value) ? "id_".$_POST["tabName"]." = $id_value" : "id_".$_POST["tabName"]." = '$id_value'";

// Obtention des données de la base en fonction de la condition
$aModif = obtenirDonnees("*", $_POST["tabName"], $condition, '', 'fetch');


// Vérification et obtention de la position maximale si elle existe dans les données
if (isset($aModif["pos_" . $_POST["tabName"]])) {
    $nbPosMax = obtenirDonnees("MAX(pos_" . $_POST["tabName"] . ") AS maxPos", $_POST["tabName"], '', '', 'fetch');
}

// Création des éléments du formulaire en fonction du nom de la table
switch ($_POST["tabName"]) {
    case "engagement": 
    case "avantage":
        $tabTemp = [
            ["text", "titre", "", "titre_".$_POST["tabName"], "required", $aModif["titre_" . $_POST["tabName"]], "Titre : "],
            ["textarea", "texte", "", "texte_".$_POST["tabName"], 'rows="20" cols="50" required', $aModif["texte_" . $_POST["tabName"]], "Texte : "]            
        ];
        break;
    case "temoignage":
        $tabTemp = [
            ["text", "nom", "", "nom_temoignage", "required", $aModif["nom_" . $_POST["tabName"]], "Nom : "],
            ["text", "prenom", "", "prenom_temoignage", "required", $aModif["prenom_" . $_POST["tabName"]], "Prénom : "],
            ["text", "metier", "", "metier_temoignage", "", $aModif["metier_" . $_POST["tabName"]], "Métier : "],
            ["number", "", "", "age_temoignage", 'min ="0"', $aModif["age_" . $_POST["tabName"]], "Age : "],
            ["textarea", "texte", "", "texte_temoignage", 'rows="50" cols="50" required', $aModif["texte_" . $_POST["tabName"]], "Texte : "],
            ["file", "", "", "img_temoignage", "", "", "Nouvelle photo : "]           
        ];
        break;
    case "reseaux_sociaux":
        $tabTemp = [
            ["text", "nom", "", "nom_reseaux_sociaux", "required", $aModif["nom_" . $_POST["tabName"]], "Nom : "],
            ["text", "lien", "", "lien_reseaux_sociaux", "required", $aModif["lien_" . $_POST["tabName"]], "Lien (ou mail) : "],
            ["file", "", "", "logo_reseaux_sociaux", "", "", "Nouvelle photo : "]           
        ];
        break;
    case "textes":
        $tabTemp = [
            ["textarea", "texte", "", "contenu", 'rows="20" cols="50" required', $aModif["contenu"], "Texte : "]            
        ];
        break;
    case "images":
        $tabTemp = [
            ["file", "", "", "chemin_images", "", "", "Nouvelle image : "]             
        ];
        if ($_POST["id_images"] == 'logo') array_splice($tabTemp, 2, 0, [["file", "faviconFolder", "", "faviconFolder[]", "webkitdirectory multiple", "", "Dossier Favicon : "]]);
        break;
}

// Ajouter les éléments reset et submit à $tabTemp
array_unshift($tabTemp, ["reset", "", "", "reset", "", "Reset les changements", ""]);
array_unshift($tabTemp, ["hidden", "", "", "tabName", "", $_POST["tabName"], ""]);
array_unshift($tabTemp, ["hidden", "", "", "id_".$_POST["tabName"], "", $id_value, ""]);

// Ajouter l'élément pour la position si ce n'est pas le cas de "textes"
if ($_POST["tabName"] !== "textes" && $_POST["tabName"] !== "images") {
    array_unshift($tabTemp, ["hidden", "", "", "pos_init", "", $aModif["pos_".$_POST["tabName"]], ""]);
    array_push($tabTemp, ["number", "pos", "", "pos_".$_POST["tabName"], "required min='1' max=" . htmlspecialchars($nbPosMax["maxPos"]), $aModif["pos_" . $_POST["tabName"]], "Position : "]);
}

// Ajouter l'élément submit à la fin de $tabTemp
array_push($tabTemp, ["submit", "", "", "", "", "Validez les modifications", ""]);

// Assigner $tabTemp à $tabInputs
$tabInputs = $tabTemp;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php infometa(); ?>
    <meta name="description" content="Modification données de la BDD du Site AD Personal Training" />
    <title>Page de Modification d'information de la BDD</title>
</head>
<body>
    <?php
        afficherHeaderAdmin();
    ?>
    <main class="flex column align-item-center justify-content-center">
        <?php
        afficheTitre(1, "Modification : ", "texte-center ", "");

        // Affichage d'une image associée au type de la table
        if ($_POST["tabName"] == "temoignage") {
            echo afficherImage("Image/Temoignage/".$aModif["img_temoignage"], $aModif["nom_temoignage"]." ".$aModif["prenom_temoignage"], "bordure-radius-50 width-33 img-temoignage", "", "");
        } else if ($_POST["tabName"] == "reseaux_sociaux") {
            echo afficherImage("Image/Reseaux_Sociaux/".$aModif["logo_reseaux_sociaux"], $aModif["nom_reseaux_sociaux"], "width-33 img-temoignage", "", "");
        } else if ($_POST["tabName"] == "images") {
            echo afficherImage("Image/Img_BDD/".$aModif["chemin_images"], $aModif["id_images"], "width-33 img-temoignage", "", "");
        }
        // Affichage du formulaire complet
        afficheFormComplet("adminModifier.php", "POST", $tabInputs, 'enctype="multipart/form-data" class="flex column align-item-center justify-content-center color-main formNoReturn" ');
        ?>
        <a href="admin.php" title="retour accueil admin" class=" texte-center action-button">Retour page d'Administration</a>
    </main>
</body>
</html>
