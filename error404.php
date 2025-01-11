<?php
include ("config/config.php");
$recupImageAcceuil1 = [
    "desktop" => obtenirDonnees("chemin_images, id_images", "images", 'id_images = "image_acceuil_1"', '', 'fetch'),
    "mobile" => obtenirDonnees("chemin_images, id_images", "images", 'id_images = "Image Acceuil 1 Mobile"', '', 'fetch'),
];

$titre1 = "Erreur 404 - Page non trouvée";
$paragraphe1 = "Oups ! La page que vous recherchez n'existe pas ou a été déplacée.";
?>

<!doctype html>
<html lang="fr">

<head>
    <?php infometa() ?>
    <meta name="keywords" content="Arnaud Deschamp, coaching sportif, AD Personal Training, bien-être, fitness, entraînement personnel">
    <meta name="description" content="Désolé, la page que vous recherchez est introuvable.">
    <title>Erreur 404 - Page non trouvée</title>
</head>

<body>
    <?php afficherHeader(); ?>
    <main>
        <section class="width-100 filtre-noir height-page position-relative">
            <div class="block-accueil width-50 mobile-only-width-90 text-white texte-center flex column space-between align-item-center padding-2 bordure-radius-20 bordure-radius-no-bottom position-absolute bg-color-second">
                <?php 
                afficheTitre(1, htmlspecialchars($titre1), "texte-center text-white", "");
                ?>
                <p><?php echo nl2br(htmlspecialchars($paragraphe1)); ?></p>
                <a href="index.php" class="action-button">Retour à l'accueil</a>
            </div>
            <picture>
                <source srcset="Image/Img_BDD/<?php echo $recupImageAcceuil1['mobile']['chemin_images']; ?>" media="(max-width: 768px)">
                <source srcset="Image/Img_BDD/<?php echo $recupImageAcceuil1['desktop']['chemin_images']; ?>" media="(min-width: 769px)">
                <img src="Image/Img_BDD/<?php echo $recupImageAcceuil1['desktop']['chemin_images']; ?>" class="image-fond" alt="Image de <?php echo $recupImageAcceuil1['desktop']['id_images']; ?>" title="Image de <?php echo $recupImageAcceuil1['desktop']['id_images']; ?>"/>
            </picture>
        </section>
    </main>
    <?php 
    afficherFooter();
    afficheButtonToTop();
    ?>
</body>

</html>
