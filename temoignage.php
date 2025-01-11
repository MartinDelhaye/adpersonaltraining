<?php
include("config/config.php");

$Titre_tecmoignage = htmlspecialchars(obtenirDonnees("contenu", "textes", 'id_textes= "Titre temoignage" ', "", 'fetch')['contenu']);
$tabTemoignage = obtenirDonnees("prenom_temoignage, nom_temoignage, metier_temoignage, age_temoignage, texte_temoignage, img_temoignage", "temoignage", '', 'pos_temoignage', 'fetchAll');
?>
<!doctype html>
<html lang="fr">

<head>
    <?php infometa() ?>
    <meta name="keywords" content="témoignages, avis clients, Arnaud Deschamp, transformations, coaching sportif">
    <meta name="description" content=" Découvrez les expériences de mes clients et comment AD Personal Training a transformé leur vie grâce à un coaching sur mesure." />
    <title>Ce Que Mes Clients Disent - AD Personal Training</title>
</head>

<body>
    <?php
    afficherHeader(); 
    ?>
    <main>
        <?php
        afficheTitre(1, $Titre_tecmoignage, "texte-center", "");   
        if (!empty($tabTemoignage)):
        ?>
            <section>
            <?php for ($i = 0; $i < count($tabTemoignage); $i++):?>
                <article class='bloc_info'>
                    <div class='padding-2 margin-site temoignage flex row align-item-center justify-content-center gap-1'>
                        <?php echo afficherImage(
                            "Image/Temoignage/" . $tabTemoignage[$i]["img_temoignage"],
                            $tabTemoignage[$i]["nom_temoignage"] . " " . $tabTemoignage[$i]["prenom_temoignage"],
                            "bordure-radius-50 width-33 img-temoignage",
                            ""
                        ); ?>
            
                        <div class="width-67 mobile-only-width-90">
                            <?php afficheTitre(2, $tabTemoignage[$i]["prenom_temoignage"] . " " . $tabTemoignage[$i]["nom_temoignage"], "", ""); ?>
            
                            <?php
                            $sous_titre = "";
                            if (isset($tabTemoignage[$i]["metier_temoignage"])) {
                                $sous_titre .= $tabTemoignage[$i]["metier_temoignage"];
                            }
            
                            if (isset($tabTemoignage[$i]["age_temoignage"])) {
                                if (!empty($sous_titre)) {
                                    $sous_titre .= " - ";
                                }
                                $sous_titre .= $tabTemoignage[$i]["age_temoignage"] . " ans";
                            }
                            ?>
                            <?php afficheTitre(3, $sous_titre, "", ""); ?>
            
                            <p class="texte-justify"><?php echo $tabTemoignage[$i]["texte_temoignage"]; ?></p>
                        </div>
                    </div>
                </article>
            <?php endfor;             
        endif;
        ?>
    </main>
    <?php 
    afficherFooter();
    afficheButtonToTop();
    ?>

</body>
</html>