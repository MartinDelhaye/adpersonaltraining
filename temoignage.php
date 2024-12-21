<?php
include("config/config.php");

$tabTemoignage = obtenirDonnees("*", "temoignage", '', 'pos_temoignage', 'fetchAll');

// Vérification de $tabTemoignage
// print_r($tabTemoignage);
?>
<!doctype html>
<html lang="fr">

<head>
    <?php infometa() ?>
    <meta name="keywords" content="témoignages, avis clients, Arnaud Deschamp, transformations, coaching sportif">
    <meta name="description" content=" Découvrez les expériences de mes clients et comment AD Personal Training a transformé leur vie grâce à un coaching sur mesure." />
    <title>Ce Que Nos Clients Disent - AD Personal Training</title>
</head>

<body>
    <?php
    afficherHeader(); 
    ?>
    <main>
        <?php
        afficheTitre(1, "Témoignages", "texte-center", "");   
        if (!empty($tabTemoignage)):
            echo "<section>";
            for ($i = 0; $i < count($tabTemoignage); $i++) {
                afficheTemoignage($tabTemoignage[$i], $i);
            }
            echo "</section>";
        endif;
        ?>
    </main>
    <?php 
    afficherFooter();
    afficheButtonToTop();
    ?>

</body>
</html>