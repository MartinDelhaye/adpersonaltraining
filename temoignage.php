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
            
                        <div class="width-67 mobile-only-width-100">
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
            
                            <p><?php echo $tabTemoignage[$i]["texte_reformuler_temoignage"]; ?></p>
            
                            <br>
                            <button id='texte_temoignage_boutton_<?php echo $i; ?>' class='display align-item-center justify-content-center justify-content padding-2 texte_temoignage_buttons'>
                                <?php echo afficherImage("Image/flecheBas.webp", "fleche", "icon", ""); ?>
                            </button>
                        </div>
                    </div>
            
                    <p id='texte_temoignage_bloc_<?php echo $i; ?>' class='texte_temoignage_blocs padding-2'>
                        <?php echo nl2br(htmlspecialchars($tabTemoignage[$i]["texte_temoignage"])); ?>
                    </p>
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