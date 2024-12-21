<?php
include ("config/config.php");

$imageUrl = $chemin_absolu_site.'/Image/img_acceuil.jpg';

// Récupération des textes de la bdd
$paragraphe1 = obtenirDonnees("contenu", "textes", 'id_textes= "texte_acceuil_1" ', "", 'fetch');
$paragraphe2 = obtenirDonnees("contenu", "textes", 'id_textes= "texte_acceuil_2" ', "", 'fetch');

$recupImageAcceuil2 = obtenirDonnees("chemin_images, id_images", "images", 'id_images = "image_acceuil_2"', '', 'fetch');
$recupImageAcceuil1 = obtenirDonnees("chemin_images, id_images", "images", 'id_images = "image_acceuil_1"', '', 'fetch');
?>
<!doctype html>
<html lang="fr">

<head>
    <?php infometa() ?>
    <meta name="keywords" content="Arnaud Deschamp, coaching sportif, AD Personal Training, bien-être, fitness, entraînement personnel">
    <meta name="description" content="Transformez votre forme physique avec AD Personal Training, fondé par Arnaud Deschamp. Profitez d'un accompagnement sportif personnalisé et adapté à vos objectifs." />
    <title>Bienvenue chez AD Personal Training</title>
</head>

<body>
    <?php afficherHeader(); ?>
    <main >
        <div class="width-100 bloc-acceuil-1 height-page">
            <div class="content-block text-white texte-center flex column space-between align-item-center padding-2">
                <?php 
                afficheTitre(1, "Arnaud DESCHAMP", "texte-center text-white", "");
                ?>
                <p><?php echo nl2br(htmlspecialchars($paragraphe1['contenu'])); ?></p>
                <a href="presentation.php" class="action-button">En savoir plus sur moi</a>
            </div>
            <?php 
                echo afficherImage('Image/Img_BDD/' . $recupImageAcceuil1['chemin_images'], $recupImageAcceuil1['id_images'], 'image-fond', ''); 
            ?>
        </div>
        



        <!-- <div class="width-100 flex row color-main space-between padding-2 padding-right-0 gap-1">
            <div class="flex column width-50 ">
                <?php 
                afficheTitre(1, "Arnaud DESCHAMP <br> Personal Training ", "", "");
                ?>
                <p><?php echo nl2br(htmlspecialchars($paragraphe2['contenu'])); ?></p>
                <div id="redirection" class="flex row align-item-center gap-1 ">
                    <a href="#blockRS" title="retour accueil" class="texte-center action-button">Contact</a>
                    <a href="engagement.php" title="retour accueil" class="texte-center action-button">Engagement</a>
                </div>
            </div>
            <?php 
            echo afficherImage('Image/Img_BDD/' . $recupImageAcceuil2['chemin_images'], $recupImageAcceuil2['id_images'], 'bordure-radius-20 bordure-radius-no-right width-33', 'img-acceuil-2'); 
            ?>
        </div> -->

        <div class="flex row space-between align-item-center modif-flex-mobile padding-2 padding-right-0 gap-1 ">
            <div class="width-67 ">
                <?php 
                afficheTitre(2, "Arnaud DESCHAMP <br> Personal Training ", "", "");
                ?>
                <p><?php echo nl2br(htmlspecialchars($paragraphe2['contenu'])); ?></p>
                <div id="redirection" class="flex row align-item-center gap-1 ">
                    <a href="#blockRS" title="retour accueil" class="texte-center action-button">Contact</a>
                    <a href="engagement.php" title="retour accueil" class="texte-center action-button">Engagement</a>
                </div>
            </div>
            <?php 
            echo afficherImage('Image/Img_BDD/' . $recupImageAcceuil2['chemin_images'], $recupImageAcceuil2['id_images'], 'bordure-10 bordure-radius-20 bordure-radius-no-right width-33 mobile-only-width-100', 'img-acceuil-2'); 
            ?>
        </div>


        
    </main>
    <?php 
    afficherFooter();
    afficheButtonToTop();
    ?>

    
</body>

</html>