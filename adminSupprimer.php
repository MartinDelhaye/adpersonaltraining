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

$aSuppr = obtenirDonnees("*", $_POST["tabName"], "id_" . $_POST["tabName"] . "=" . $_POST["id_" . $_POST["tabName"]], '', 'fetch');

// Récupération des données de la table

if($_POST["tabName"] != "users"){
$filtre = 'pos_'.$_POST["tabName"];
}else{
    $filtre = '';
}

$tabElements = obtenirDonnees("*", $_POST["tabName"], '',$filtre , 'fetchAll');


$requete_preparee = $bdd->prepare('DELETE FROM ' . $_POST["tabName"] . ' WHERE id_'.$_POST["tabName"].' = '.$_POST["id_".$_POST["tabName"]]);


if (isset($aSuppr["img_temoignage"])) {
    if (file_exists($chemin_absolu_site . "/Image/Temoignage/" . $aSuppr["img_temoignage"])) {
        // Supprimer le fichier d'image
        echo "ça supprime une image <br>";
        unlink($chemin_absolu_site.'/Image/Temoignage/'.$aSuppr["img_temoignage"]);
    }

}
if (isset($aSuppr["logo_reseaux_sociaux"])) {
    if (file_exists($chemin_absolu_site . "/Image/Reseaux_Sociaux/" . $aSuppr["logo_reseaux_sociaux"])) {
        // Supprimer le fichier d'image
        echo "ça supprime une image <br>";
        unlink($chemin_absolu_site.'/Image/Reseaux_Sociaux/'.$aSuppr["logo_reseaux_sociaux"]);
    }

}

// Vérification si la position a changé
foreach ($tabElements as $element) {
    if ($element['pos_'.$_POST["tabName"]] >  $aSuppr["pos_".$_POST["tabName"]]){        
        $nouvelle_pos = $element['pos_'.$_POST["tabName"]] - 1;
        $requete_preparee_pos = $bdd->prepare('UPDATE '.$_POST["tabName"].' SET pos_'.$_POST["tabName"].'=:nouvelle_pos WHERE id_'.$_POST["tabName"].'=:id_element');
        $requete_preparee_pos->bindValue(':nouvelle_pos', $nouvelle_pos, PDO::PARAM_INT);
        $requete_preparee_pos->bindValue(':id_element', $element['id_'.$_POST["tabName"]], PDO::PARAM_INT);
        $requete_preparee_pos->execute(); 
    }
}

// Suppression de la bdd
$requete_preparee->execute();

// Redirection vers la page admin.php
header('Location: admin.php');
exit();

?>