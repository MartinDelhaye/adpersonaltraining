<?php
include("config/config.php");

isUserLoggedIn();

$tabAvantage = obtenirDonnees("*", "avantage", '', 'pos_avantage', 'fetchAll');
$tabEngagement = obtenirDonnees("*", "engagement", '', 'pos_engagement', 'fetchAll');
$tabTemoignage = obtenirDonnees("*", "temoignage", '', 'pos_temoignage', 'fetchAll');
$tabReseauxSociaux = obtenirDonnees("*", "reseaux_sociaux", '', 'pos_reseaux_sociaux', 'fetchAll');
$tabTextes = obtenirDonnees("*", "textes", '', '', 'fetchAll');
$tabImages = obtenirDonnees("*", "images", '', '', 'fetchAll');

$tabInputSelectAvantage = prepareTabInputSelect($tabAvantage,'avantage');
$tabInputSelectEngagement = prepareTabInputSelect($tabEngagement,'engagement');
$tabInputSelectTemoignage = prepareTabInputSelect($tabTemoignage,'temoignage');
$tabInputSelectReseauxSociaux = prepareTabInputSelect($tabReseauxSociaux,'reseaux_sociaux');
$tabInputSelectTextes = prepareTabInputSelect($tabTextes,'textes');
$tabInputSelectImages = prepareTabInputSelect($tabImages,'images');


$tabInputAjoueAvantage = prepareTabInput("avantage");
$tabInputAjoueEngagement = prepareTabInput("engagement");
$tabInputAjoueTemoignage = prepareTabInput("temoignage");
$tabInputAjoueReseauxSociaux = prepareTabInput("reseaux_sociaux"); 

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- <?php infometa(); ?> -->
    <!-- Description -->
    <meta name="description" content="Administration du Site AD Personal Training" />
    <title>ADMIN</title>
</head>

<body>
    <?php
    afficherHeaderAdmin();
    ?>
    <main>
        <?php
        afficheTitre(1, "Page d'administration", "texte-center", "");

        ?>
        <!-- ---------------- Suppression ---------------- -->
        <div id="suppr" class="width-100 padding-2">
            <?php afficheTitre(2, "Suppression :", "", ""); ?>
            <section class="flex row gap-1 width-100 form-admin flex flex-wrap justify-content-center">
                <?php
                // ---------------- Supprime un Avantage ----------------
                if (!empty($tabAvantage)) {
                    afficheFormSelect("Supprimer un Avantage :", ["adminSupprimer.php", "POST", $tabInputSelectAvantage,'class="formNoReturn width-100 padding-2 flex column align-item-center formNoReturn"'], "avantage-suppr", "bordure-radius-20");
                }
                // ---------------- Supprime un Engagement ----------------
                if (!empty($tabEngagement)) {
                    afficheFormSelect("Supprimer un Engagement :", ["adminSupprimer.php", "POST", $tabInputSelectEngagement,'class="formNoReturn width-100 padding-2 flex column align-item-center"'], "engagement-suppr", "bordure-radius-20");
                }
                // ---------------- Supprime un Temoignage ----------------
                if (!empty($tabTemoignage)) {
                    afficheFormSelect("Supprimer un Témoignage :", ["adminSupprimer.php", "POST", $tabInputSelectTemoignage,'class="formNoReturn width-100 padding-2 flex column align-item-center"'], "temoignage-suppr", "bordure-radius-20");
                }
                // ---------------- Supprime un Réseau social ----------------
                if (!empty($tabReseauxSociaux)) {
                    afficheFormSelect("Supprimer un Réseau social :", ["adminSupprimer.php", "POST", $tabInputSelectReseauxSociaux,'class="formNoReturn width-100 flex column align-item-center"'], "reseaux_sociaux-suppr", "bordure-radius-20");
                }
                ?>
            <section>
        </div>

        <!-- ---------------- Modification  ---------------- -->
        <div id="modif" class="width-100 padding-2">
            <?php afficheTitre(2, "Modification :", "", ""); ?>
            <section class="flex row gap-1 width-100 form-admin flex flex-wrap justify-content-center">
                <?php
                // ---------------- Modifier un Avantage ----------------
                if (!empty($tabAvantage)) {
                    afficheFormSelect("Modifier un Avantage :", ["adminModification.php", "POST", $tabInputSelectAvantage,'class=" width-100 padding-2 flex column align-item-center formNoReturn"'], "avantage-modif", "bordure-radius-20");
                }
                
                // ---------------- Modifier un Engagement ----------------
                if (!empty($tabEngagement)) {
                    afficheFormSelect("Modifier un Engagement :", ["adminModification.php", "POST", $tabInputSelectEngagement,'class=" width-100 padding-2 flex column align-item-center"'], "engagement-modif", "bordure-radius-20");
                }
                // ---------------- Modifier un Temoignage ----------------
                if (!empty($tabTemoignage)) {
                    afficheFormSelect("Modifier un Témoignage :", ["adminModification.php", "POST", $tabInputSelectTemoignage,'class=" width-100 padding-2 flex column align-item-center"'], "temoignage-modif", "bordure-radius-20");
                }
                // ---------------- Modifier un Réseau social ----------------
                if (!empty($tabReseauxSociaux)) {
                    afficheFormSelect("Modifier un Réseau social :", ["adminModification.php", "POST", $tabInputSelectReseauxSociaux,'class=" width-100 padding-2 flex column align-item-center"'], "reseaux_sociaux-modif", "bordure-radius-20");
                }
                // ---------------- Modifier un Texte ----------------
                if (!empty($tabTextes)) {
                    afficheFormSelect("Modifier un Texte :", ["adminModification.php", "POST", $tabInputSelectTextes,'class=" width-100 padding-2 flex column align-item-center"'], "textes-modif", "bordure-radius-20");
                }
                // ---------------- Modifier un Texte ----------------
                if (!empty($tabImages)) {
                    afficheFormSelect("Modifier une Image :", ["adminModification.php", "POST", $tabInputSelectImages,'class=" width-100 flex column align-item-center"'], "images-modif", "bordure-radius-20");
                }                
                ?>
                <section>
        </div>
        <!-- ---------------- Ajout  ---------------- -->
        <div id="ajout" class="width-100 padding-2">
            <?php afficheTitre(2, "Ajout :", "", ""); ?>
            <section class="flex row gap-1 width-100 form-admin flex flex-wrap justify-content-center">
                <?php
                // ---------------- Ajout un Avantage ----------------
                if (!empty($tabInputAjoueAvantage)) {
                    afficheFormSelect("Ajouter un Avantage :", ["adminAjout.php", "POST", $tabInputAjoueAvantage,'class=" width-100 flex column align-item-center"'], "avantage-ajout", "bordure-radius-20");
                }
                
                // ---------------- Ajout un Engagement ----------------
                if (!empty($tabInputAjoueAvantage)) {
                    afficheFormSelect("Ajouter un Engagement :", ["adminAjout.php", "POST", $tabInputAjoueEngagement,'class=" width-100 flex column align-item-center"'], "engagement-ajout", "bordure-radius-20");
                }
              
                // ---------------- Ajout un Temoignage ----------------
                if (!empty($tabInputAjoueAvantage)) {
                    afficheFormSelect("Ajouter un Témoignage :", ["adminAjout.php", "POST", $tabInputAjoueTemoignage, 'class=" width-100 flex column align-item-center" enctype="multipart/form-data"'], "temoignage-ajout", "bordure-radius-20");
                }

                // ---------------- Ajout un Temoignage ----------------
                if (!empty($tabInputAjoueReseauxSociaux)) {
                    afficheFormSelect("Ajouter un Reseau social :", ["adminAjout.php", "POST", $tabInputAjoueReseauxSociaux, 'class=" width-100 flex column align-item-center" enctype="multipart/form-data"'], "reseaux_sociaux-ajout", "bordure-radius-20");
                }
                
                ?>
                <section>
        </div>
        <?php 
        afficheButtonToTop();
        ?>
    </main>
</body>

</html>