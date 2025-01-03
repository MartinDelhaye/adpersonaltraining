<?php
include("config/config.php");

$Titre_engagement = htmlspecialchars(obtenirDonnees("contenu", "textes", 'id_textes= "Titre engagement" ', "", 'fetch')['contenu']);
$tabEngagement = obtenirDonnees("*", "engagement", '', 'pos_engagement', 'fetchAll');

$recupImageEngagement = obtenirDonnees("chemin_images, id_images", "images", 'id_images = "Image Engagement"', '', 'fetch');
?>
<!doctype html>
<html lang="fr">

<head>
    <?php infometa() ?>
    <meta name="keywords" content="engagement, Arnaud Deschamp, confiance, résultats, accompagnement personnalisé">
    <meta name="description" content="Chez AD Personal Training, nous nous engageons à vous offrir un accompagnement sportif de qualité pour atteindre vos objectifs." />
    <title>Mes Engagements - AD Personal Training</title>
</head>

<body>
    <?php
    afficherHeader(); 
    ?>
    <main>
        <section class="width-100 filtre-noir height-semiPage position-relative flex column justify-content-center">
            <?php afficheTitre(1, $Titre_engagement, "texte-center text-white titre-engagement width-100", "");?>
            <img src="Image/Img_BDD/<?php echo $recupImageEngagement['chemin_images']; ?>" class="image-fond" alt="Image de <?php echo $recupImageEngagement['id_images']; ?>" title="Image de <?php echo $recupImageEngagement['id_images']; ?>"/>
        </section>
        <?php
        if (!empty($tabEngagement)):
            afficheInfo($tabEngagement, 'engagement', 'flex column', 'anim-appear bloc_info info_engagement flex row justify-content-center texte-center padding-2');
        endif;
        ?>
    </main>
    <?php 
    afficherFooter();
    afficheButtonToTop();
    ?>

</body>
</html>