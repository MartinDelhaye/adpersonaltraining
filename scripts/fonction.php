<?php
/**
 * Fonction qui permet de récupérer des informations dans la base de données
 *
 * @param string $info : liste des champs que l'on souhaite récupérer
 * @param string $table : nom de la table dans laquelle l'on souhaite récupérer les informations
 * @param string $filtre : filtre pour la requête (par exemple, "id_artiste = 1")
 * @param string $trier : champ par lequel on souhaite trier les résultats (par exemple, "nom")
 * @param string $type_fetch : type de fetch (par exemple, "fetchAll" pour récupérer tout, "fetch" pour récupérer un seul résultat)
 *
 * @return array : tableau contenant les résultats de la requête
 */
function obtenirDonnees($info, $table, $filtre = '', $trier = '', $type_fetch = 'fetchAll')
{
    global $bdd;
    try {
        $requete = 'SELECT ' . $info . ' FROM ' . $table;
        if (!empty($filtre)) {
            $requete .= ' WHERE ' . $filtre;
        }
        if (!empty($trier)) {
            $requete .= ' ORDER BY ' . $trier;
        }

        $stmt = $bdd->query($requete);
        return $stmt->$type_fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $requete . "<br>";
        die('Erreur : ' . $e->getMessage());
    }
}


/**
 * Fonction qui écrit les balises meta et les liens 
 * des feuilles de style, des polices, des scripts et 
 * du favicon dans le header de la page.
 */
function infometa()
{
    global $chemin_absolu_site;
    echo '
            <!-- Encodage utf-8 -->
            <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
            <!-- Auteur du site -->
            <meta name="author" content="Delhaye Martin" />
            <!-- Compatibilité navigateur/mobile -->
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- link favicon -->
            <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
            <link rel="manifest" href="site.webmanifest">
            <!-- Link CSS -->
            <link href="CSS/style.css" rel="stylesheet" type="text/css"> 
            <!-- Link JS -->
            <script src="scripts/scripts.js"></script>
            <!-- Link Font -->
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700&display=swap" rel="stylesheet">
';
}


/**
 * Affiche le header du site, composé d'un logo, d'un bouton pour ouvrir le menu,
 * et d'un menu de navigation.
 *
 * @return void
 */
function afficherHeader()
{
    $recupLogo = obtenirDonnees("chemin_images, id_images", "images", 'id_images = "logo"', '', 'fetch');
    echo ' <header class="width-100 flex space-between align-item-center text-white">
                    <a href="index.php" title="retour accueil" class="flex justify-content-center">'
        . afficherImage('Image/Img_BDD/' . $recupLogo['chemin_images'], $recupLogo['id_images'], 'logo', '') . '
                    </a>
                    <button class="menu-toggle text-center display-none" aria-label="Ouvrir le menu">
                        &#9776;
                    </button>
                    <nav class="flex justify-content-center align-item-center padding-2 nav-menu">
                        <ul class="flex row gap-1 ">
                            <li>
                                <a href="index.php">Accueil</a>
                            </li>
                            <li>
                                <a href="presentation.php">Présentation</a>
                            </li>
                            <li>
                                <a href="avantage.php">FAQ</a>
                            </li>
                            <li>
                                <a href="engagement.php">Savoir-faire</a>
                            </li>
                            <li>
                                <a href="temoignage.php">Témoignages</a>
                            </li>
                        </ul>
                    </nav>
            </header>';
}

/**
 * Affiche le pied de page avec le logo, les liens de réseaux sociaux, 
 * un lien de retour à l'accueil, les mentions légales, et le plan du site.
 */
function afficherFooter()
{
    // Récupère les informations du logo dans la base de données
    $logoData = obtenirDonnees("chemin_images, id_images", "images", 'id_images = "logo"', '', 'fetch');
    // Récupère les données des réseaux sociaux
    $tabRS = obtenirDonnees("*", "reseaux_sociaux", '', 'pos_reseaux_sociaux', 'fetchAll');

    // Affichage du footer
    echo ' 
        <footer class="width-100 flex column space-between align-item-center justify-content-center text-white padding-2 gap-1">
            <a href="index.php" title="Retour accueil" class="flex justify-content-center">
                ' . afficherImage('Image/Img_BDD/' . $logoData['chemin_images'], $logoData['id_images'], 'logo', '') . '
            </a>';

    // Section "Contacter AD Personal Training" avec les réseaux sociaux
    echo '<div class="width-50"> 
                <h3 class="texte-center">Contacter AD Personal Training :</h3> 
                <ul id="blockRS" class="flex row space-between align-items-center justify-content-center gap-1">';

    foreach ($tabRS as $reseauSocial) {

        // Générer le HTML pour le lien du réseau social
        echo '<li class=""> <a href="' . $reseauSocial['lien_reseaux_sociaux'] . '" target="_blank">'
            . afficherImage(
                'Image/Reseaux_Sociaux/' . $reseauSocial['logo_reseaux_sociaux'],
                $reseauSocial['nom_reseaux_sociaux'].' | '.$reseauSocial['lien_reseaux_sociaux'],
                'logoRS icon'
            )
            . '</a></li>';
    }

    echo '</ul>';
    echo '</div>';
    
    // Affichage des mentions légales et des informations de copyright
    echo '<a href="mentions-legales.php" class="width-100"><p class="texte-center copyright">&copy; ' . date('Y') . ' - AD Personal Training - Mentions Légales</p></a>
    </footer>';
}





/**
 * Affiche une image HTML en fonction de l'URL de l'image ($url_image),
 * du texte de remplacement ($title_alt), de la classe CSS ($class),
 * de l'id HTML ($id) et de paramètres supplémentaires ($paramSup)
 *
 * @param string $url_image URL de l'image
 * @param string $title_alt texte de remplacement
 * @param string $class classe CSS
 * @param string $id id HTML
 * @param string $paramSup paramètres supplémentaires
 * @return string code HTML de l'image
 */
function afficherImage($url_image, $title_alt, $class = "", $id = "", $paramSup = "")
{
    return '<img src="' . $url_image . '" class="' . $class . '" id="' . $id . '" alt="' . $title_alt . '" title="' . $title_alt . '" ' . $paramSup . '/>';
}

/**
 * Affiche un titre HTML en fonction du niveau de titre ($numH) 
 * et du texte du titre ($titre)
 * 
 * @param int $numH niveau du titre (1,2,3,4,5 ou 6)
 * @param string $titre texte du titre
 * @param string $class classe CSS associée au titre
 * @param string $id id HTML associée au titre
 */
function afficheTitre($numH, $titre, $class = "", $id = "")
{
    echo '<h' . $numH . ' class="' . $class . '" id="' . $id . '">' . $titre . '</h' . $numH . '>';
}



/**
 * Affiche les données de la tab avantages et engagements
 * Le tableau est divisé en deux parties : 
 * - une partie fixe qui contient l'entête (nom et prénom)
 * - une partie variable qui contient le corps (tous les détails)
 *
 * @param array $tab Tableau de données à afficher
 * @param string $nomTableBDD Nom de la table de la BDD  ("avantage" ou "engagement")
 * @param string $tableCSSGloal Classe CSS pour le container global
 * @param string $tableCSSFocus Classe CSS pour les éléments de la liste
 */
function afficheInfo($tab, $nomTableBDD, $tableCSSGloal, $tableCSSFocus)
{
    echo '<div class="' . $tableCSSGloal . '">';
    $index = 0;
    foreach ($tab as $focus):
        echo '<div class="' . $tableCSSFocus . '">';
        if ($nomTableBDD == "avantage")
            echo '<p class="position font-bold">' . $index + 1 . '</p>';
        echo '<section>';
        echo '<h2>' . $focus['titre_' . $nomTableBDD] . '</h2>';
        echo '<p>' . $focus['texte_' . $nomTableBDD] . '</p>';
        echo '</section>';
        $index += 1;
        echo '</div>';

    endforeach;
    echo '</div>';
}


/**
 * Affiche un bouton pour remonter en haut de la page
 *
 * Utilise la classe "scroll-to-top" pour positionner le bouton
 * et l'id "scrollToTopBtn" pour le cibler par le JavaScript
 *
 * @return void
 */
function afficheButtonToTop()
{
    echo '<button id="scrollToTopBtn" class="scroll-to-top bordure-radius-50">' . afficherImage('Image/flecheHaut.webp', 'fleche', 'icon', '') . '</button>';
}
?>