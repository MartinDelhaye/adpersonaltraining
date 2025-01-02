<?php
include("config/config.php");

$tabEngagement = obtenirDonnees("*", "engagement", '', 'pos_engagement', 'fetchAll');

// Récupération des textes de la bdd
$textes = obtenirDonnees("id_textes, contenu", "textes", 'id_textes IN ("Titre presentation", "texte_presentation", "Titre formations", "texte_formations")', "", 'fetchAll');

// Initialiser un tableau pour organiser les données
$data = [];
foreach ($textes as $texte) {
    $data[$texte['id_textes']] = $texte['contenu'];
}

$titre_presentation = $data['Titre presentation'];
$paragraphe_presentation = $data['texte_presentation'];
$titre_formations = $data['Titre formations'];
$paragraphe_formations = $data['texte_formations'];

$recupImagePresentation = obtenirDonnees("chemin_images, id_images", "images", 'id_images = "Image_Presentation"', '', 'fetch');

?>
<!doctype html>
<html lang="fr">

<head>
    <?php infometa() ?>
    <meta name="keywords"
        content="Arnaud Deschamp, présentation, coach sportif, passion pour le sport, histoire, AD Personal Training">
    <meta name="description"
        content="Découvrez l'histoire d'Arnaud Deschamp, fondateur d'AD Personal Training, une entreprise dédiée au coaching sportif sur mesure depuis 2020." />
    <title>Qui Je suis ? - AD Personal Training</title>
</head>

<body>
    <?php
    afficherHeader();
    ?>
    <main>
        <section
            class="flex row space-between align-item-center modif-flex-mobile padding-2 padding-right-0 gap-1 height-page">
            <div class="width-67">
                <?php
                afficheTitre(1, htmlspecialchars($titre_presentation), "", "");
                echo "<p>" . nl2br(htmlspecialchars($paragraphe_presentation)) . "</p>";
                ?>
            </div>
            <?php
            echo afficherImage('Image/Img_BDD/' . $recupImagePresentation['chemin_images'], $recupImagePresentation['id_images'], 'bordure-10 bordure-radius-20 bordure-radius-no-right width-33 mobile-only-width-100', '');
            ?>
        </section>
        <section class="flex column justify-content-center bg-color-second text-white padding-2 height-page">
            <?php
            afficheTitre(2, htmlspecialchars($titre_formations), "", "");
            echo "<p>" . nl2br(htmlspecialchars($paragraphe_formations)) . "</p>";
            ?>
        </section>
    </main>
    <?php
    afficherFooter();
    afficheButtonToTop();
    ?>
</body>

</html>