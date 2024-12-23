<?php
include("config/config.php");

$Titre_engagement = htmlspecialchars(obtenirDonnees("contenu", "textes", 'id_textes= "Titre engagement" ', "", 'fetch')['contenu']);
$tabAvantage = obtenirDonnees("*", "avantage", '', 'pos_avantage', 'fetchAll');
?>
<!doctype html>
<html lang="fr">

<head>
    <?php infometa() ?>
    <meta name="keywords" content="avantages coaching sportif, AD Personal Training, entraînement sur mesure, motivation sportive">
    <meta name="description" content="Explorez les avantages de travailler avec AD Personal Training : coaching individualisé, suivi précis et résultats garantis." />
    <title>Pourquoi Choisir AD Personal Training ?</title>
</head>

<body>
    <?php 
    afficherHeader(); 
    ?>
    <main>
        <?php
        afficheTitre(1, "FAQ", "texte-center", "");
        if (!empty($tabAvantage)):
            afficheInfo($tabAvantage, 'avantage', 'flex column bloc_info_wrapper', 'anim-appear bloc_info info_avantage flex row align-item-center padding-2');
        endif;
        ?>
    </main>
    <?php 
    afficherFooter();
    afficheButtonToTop();
    ?>

</body>
</html>