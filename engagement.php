<?php
include("config/config.php");

$tabEngagement = obtenirDonnees("*", "engagement", '', 'pos_engagement', 'fetchAll');
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
        <?php
        afficheTitre(1, "Les Engagements de ADPT", "texte-center", "");
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