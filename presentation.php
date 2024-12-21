<?php
include("config/config.php");

$tabEngagement = obtenirDonnees("*", "engagement", '', 'pos_engagement', 'fetchAll');

// Récupération des textes de la bdd
$paragraphe_presentation = obtenirDonnees("contenu", "textes", 'id_textes= "texte_presentation" ', "", 'fetch');
$paragraphe_formations = obtenirDonnees("contenu", "textes", 'id_textes= "texte_formations" ', "", 'fetch');

$recupImagePresentation = obtenirDonnees("chemin_images, id_images", "images", 'id_images = "Image_Presentation"', '', 'fetch');

?>
<!doctype html>
<html lang="fr">

<head>
    <?php infometa() ?>
    <meta name="keywords" content="Arnaud Deschamp, présentation, coach sportif, passion pour le sport, histoire, AD Personal Training">
    <meta name="description" content="Découvrez l'histoire d'Arnaud Deschamp, fondateur d'AD Personal Training, une entreprise dédiée au coaching sportif sur mesure depuis 2020." />
    <title>Qui Sommes-Nous ? - AD Personal Training</title>
</head>

<body>
    <?php
    afficherHeader(); 
    ?>
    <main>
        <div class="flex row space-between align-item-center modif-flex-mobile padding-2 padding-right-0 gap-1 height-page">
            <section class="width-67">
            <?php
            afficheTitre(2, "Arnaud DESCHAMP", "", "");
            echo "<p>". nl2br(htmlspecialchars($paragraphe_presentation['contenu']))."</p>";
            ?>
            </section>
            <?php 
                echo afficherImage('Image/Img_BDD/' . $recupImagePresentation['chemin_images'], $recupImagePresentation['id_images'], 'bordure-10 bordure-radius-20 bordure-radius-no-right width-33 mobile-only-width-100', '');
            ?>
        </div>

        <div class="flex column space-between color-second text-white padding-2">   
            <?php
            afficheTitre(2, "Formations", "", "");
            echo "<p>". nl2br(htmlspecialchars($paragraphe_formations['contenu']))."</p>";
            ?>
        </div>
    </main>
    <?php 
    afficherFooter();
    afficheButtonToTop();
    ?>

</body>
</html>