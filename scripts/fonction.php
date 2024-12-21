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
            <link rel="apple-touch-icon" sizes="180x180" href="Image/favicon_io/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="Image/favicon_io/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="Image/favicon_io/favicon-16x16.png">
            <link rel="manifest" href="Image/favicon_io/site.webmanifest">
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
                                <a href="index.php">Acceuil</a>
                            </li>
                            <li>
                                <a href="presentation.php">Presentation</a>
                            </li>
                            <li>
                                <a href="avantage.php">Avantages</a>
                            </li>
                            <li>
                                <a href="engagement.php">Engagements</a>
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
        <footer class="width-100 flex column space-between align-item-center justify-content-center text-white padding-2">
            <a href="index.php" title="Retour accueil" class="flex justify-content-center">
                ' . afficherImage('Image/Img_BDD/' . $logoData['chemin_images'], $logoData['id_images'], 'logo', '') . '
            </a>';

    echo "<div class='flex gap-1 padding-2 modif-flex-mobile align-item-center'>";

    // Section "Contacter AD Personal Training" avec les réseaux sociaux
    echo '<div class="width-50"> 
                <h3 class="texte-center">Contacter AD Personal Training :</h3> 
                <ul id="blockRS" class="flex row space-between align-items-center justify-content-center gap-1">';

    foreach ($tabRS as $reseauSocial) {
        // Vérifier si le nom du réseau social est "Mail"
        if ($reseauSocial['nom_reseaux_sociaux'] === "Mail") {
            // Générer un lien mailto pour les adresses e-mail
            $href = "mailto:" . $reseauSocial['lien_reseaux_sociaux'];
        } else {
            // Utiliser le lien direct pour les autres réseaux sociaux
            $href = $reseauSocial['lien_reseaux_sociaux'];
        }

        // Générer le HTML pour le lien du réseau social
        echo '<li class=""> <a href="' . $href . '" target="_blank">'
            . afficherImage(
                'Image/Reseaux_Sociaux/' . $reseauSocial['logo_reseaux_sociaux'],
                $reseauSocial['nom_reseaux_sociaux'],
                'logoRS icon'
            )
            . '</a></li>';
    }

    echo '</ul>';
    echo '</div>';

    // Plan du site - liens vers les pages principales
    echo '<div class="width-50">
                <h3 class="texte-center">Plan du site :</h3>
                <ul class="plan-site-list texte-center flex column gap-1">
                    <li><a href="index.php" title="Présentation">Accueil</a></li>
                    <li><a href="presentation.php" title="Présentation">Présentation</a></li>
                    <li><a href="avantage.php" title="Avantages">Avantages</a></li>
                    <li><a href="engagement.php" title="Engagement">Engagement</a></li>
                    <li><a href="temoignage.php" title="Témoignages">Témoignages</a></li>
                    <li><a href="mentions-legales.php" title="Mentions légales">Mentions légales</a></li>
                </ul>
            </div>';

    echo "</div>"; // Fermeture du div "flex gap-1"
    
    // Affichage des mentions légales et des informations de copyright
    echo '<p class="texte-center copyright">&copy; ' . date('Y') . ' - AD Personal Training </p>
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
    return '<img src="' . $url_image . '" class="' . $class . '" id="' . $id . '" alt="Image de ' . $title_alt . '" title="Image de ' . $title_alt . '" ' . $paramSup . '/>';
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
    echo '<button id="scrollToTopBtn" class="scroll-to-top display-none bordure-radius-50">' . afficherImage('Image/flecheHaut.webp', 'fleche', 'icon', '') . '</button>';
}

// ------------ Fonctions pour les pages admin -------------


/**
 * Affiche le header de la page admin
 *
 * @return void
 */
function afficherHeaderAdmin()
{
    $recupLogo = obtenirDonnees("chemin_images, id_images", "images", 'id_images = "logo"', '', 'fetch');
    echo ' <header class="width-100 flex space-between align-item-center text-white">
            <a href="index.php" title="retour accueil" class="flex justify-content-center">'
        . afficherImage('Image/Img_BDD/' . $recupLogo['chemin_images'], $recupLogo['id_images'], 'logo', '') .
        '</a>
            <a href="adminCompte.php" title="Retour accueil" class="action-button flex justify-content-center">
                Gérer Compte Admin
            </a>
            <form action="adminDeconnexion.php" method="POST" class="flex align-items-center">
                <button type="submit" class="action-button" title="Déconnexion">
                    Déconnexion
                </button>
            </form>
          </header>';
}

/**
 * Affiche un formulaire de sélection avec un titre, un ID, une classe CSS
 * et des paramètres de formulaire.
 *
 * @param string $titre   Le titre du formulaire à afficher.
 * @param array  $tabForm Un tableau contenant les paramètres du formulaire :
 *                        - [0] : l'URL vers laquelle le formulaire pointe (action).
 *                        - [1] : la méthode de soumission (GET ou POST).
 *                        - [2] : un tableau associatif des inputs du formulaire.
 *                        - [3] : des paramètres supplémentaires (facultatif).
 * @param string $id      L'ID du formulaire (facultatif).
 * @param string $class   La classe CSS du formulaire (facultatif).
 */
function afficheFormSelect($titre, $tabForm, $id = "", $class = "")
{
    echo '<article id="' . $id . '" class="' . $class . ' color-main">';
    afficheTitre(3, $titre, "", "");
    if (isset($tabForm[3])) {
        $paramSup = $tabForm[3];
    } else {
        $paramSup = '';
    }
    afficheFormComplet($tabForm[0], $tabForm[1], $tabForm[2], $paramSup);
    echo '</article>';
}


/**
 * Génère le code HTML pour un élément de formulaire (<input>, <textarea>, <select>).
 * 
 * @param array $tabInput Un tableau contenant les informations de l'input :
 *  - [0] : Le type de l'input (ex: text, password, email, textarea, select).
 *  - [1] : L'ID de l'input.
 *  - [2] : La classe CSS de l'input.
 *  - [3] : Le nom de l'input (attribut "name").
 *  - [4] : Les paramètres supplémentaires de l'input (ex: required, disabled).
 *  - [5] : La valeur de l'input ou les options si c'est un select.
 *  - [6] : Le label de l'input (facultatif).
 * 
 * @return string Le code HTML complet généré pour l'input.
 */

function createInput($tabInput)
{
    $typeInput = htmlspecialchars($tabInput[0]);
    $id = htmlspecialchars($tabInput[1]);
    $class = htmlspecialchars($tabInput[2]);
    $name = htmlspecialchars($tabInput[3]);
    $paramSup = $tabInput[4];
    $value = $tabInput[5];
    $label = htmlspecialchars($tabInput[6]);
    $inputHtml = '';
    $endLabel = "";
    if ($typeInput !== 'reset' && $typeInput !== 'submit' && $typeInput !== 'hidden') {
        $inputHtml .= '<label for="' . $id . '">' . $label;
        $endLabel = "</label>";
    }
    if ($typeInput == 'textarea') {
        $inputHtml .= '<textarea id="' . $id . '" class="' . $class . '" name="' . $name . '" ' . $paramSup . '>' . $value . '</textarea>';
    } else if ($typeInput == 'select') {
        $inputHtml .= '<select id="' . $id . '" class="' . $class . '" name="' . $name . '" ' . $paramSup . '>';
        foreach ($value as $option) {
            $optionValue = htmlspecialchars($option['value']);
            $optionText = htmlspecialchars($option['texte']);
            $selected = ($optionValue == $paramSup) ? 'selected' : '';
            $inputHtml .= '<option value="' . $optionValue . '" ' . $selected . '>' . $optionText . '</option>';
        }
        $inputHtml .= '</select>';
    } else {
        $inputHtml .= '<input type="' . $typeInput . '" id="' . $id . '" class="' . $class . '" name="' . $name . '" ' . $paramSup . ' value="' . $value . '">';
    }
    $inputHtml .= $endLabel;

    return $inputHtml;
}

/**
 * Affiche un formulaire HTML entier, en prenant en paramètre 
 *   - $action : l'attribut action du formulaire
 *   - $method : l'attribut method du formulaire
 *   - $tabInputs : un tableau contenant les informations des inputs à afficher
 *   - $paramSup : des paramètres supplémentaires pour le formulaire
 */
function afficheFormComplet($action, $method, $tabInputs, $paramSup = "")
{
    echo '<form action="' . htmlspecialchars($action) . '" method="' . htmlspecialchars($method) . '" ' . $paramSup . '>';
    foreach ($tabInputs as $input) {
        echo createInput($input) . '<br/>';
    }
    echo '</form>';
}


/**
 * Prepare le tableau d'input pour un formulaire avec un select d'un element d'une table
 * 
 * @param array $tab le tableau d'element de la table
 * @param string $nomTab le nom de la table
 * 
 * @return array le tableau d'input
 */
function prepareTabInputSelect($tab, $nomTab)
{
    $paramSup = 'size=10 required="required"';
    $maxTab = count($tab);
    if ($nomTab == "temoignage") {
        $colonneTexte = ["nom_" . $nomTab, "prenom_" . $nomTab];
    } else if ($nomTab == "textes" || $nomTab == "images") {
        $colonneTexte = ["id_" . $nomTab];
    } else if ($nomTab == "reseaux_sociaux") {
        $colonneTexte = ["nom_" . $nomTab];
    } else if ($nomTab == "users") {
        $colonneTexte = ["username"];
        $paramSup = '';
    } else {
        $colonneTexte = ["titre_" . $nomTab];
    }

    $tabOption = [];
    for ($i = 0; $i < $maxTab; $i++) {
        $texte = "";
        for ($f = 0; $f < count($colonneTexte); $f++) {
            $texte .= " " . $tab[$i][$colonneTexte[$f]];
        }
        ;
        $tabOption[$i]["value"] = $tab[$i]["id_" . $nomTab];
        $tabOption[$i]["texte"] = $texte;

    }

    $tabInput = [
        ["hidden", "hidden", "", 'tabName', '', $nomTab, ""],
        ["select", "select", "", "id_" . $nomTab, $paramSup, $tabOption, ""],
        ["submit", "", "", "", "", "Validez", ""]
    ];

    return $tabInput;
}


/**
 * Télécharge une image et renvoie le nom de fichier de l'image téléchargée.
 *
 * @param array  $serveur   Le tableau superglobal $_SERVER, utilisé pour obtenir certaines informations.
 * @param array  $post      Le tableau superglobal $_POST, contenant les données soumises via le formulaire.
 * @param array  $files     Le tableau superglobal $_FILES, contenant les fichiers téléchargés.
 * @param string $tabname   Le nom de la table correspondant à l'image (ex: "temoignage", "reseaux_sociaux").
 *
 * @return string Le nom de l'image téléchargée.
 *
 * @throws Exception Si le téléchargement du fichier échoue ou si le fichier est invalide.
 */

function downloadImageAndGetURL($serveur, $post, $files, $tabname)
{
    // Définir le dossier et le préfixe en fonction du nom de la table
    switch ($tabname) {
        case "temoignage":
            $dossier = "Temoignage/";
            $prefixe = "img_";
            $nom = isset($post["nom_" . $tabname]) ? $post["nom_" . $tabname] : '';
            $prenom = isset($post["prenom_" . $tabname]) ? $post["prenom_" . $tabname] : '';
            // Créer un nom de fichier unique avec nom_prenom
            $nom_fichier = strtolower($nom) . '_' . strtolower($prenom);
            break;
        case "reseaux_sociaux":
            $dossier = "Reseaux_Sociaux/";
            $prefixe = "logo_"; // Assurez-vous que le préfixe correspond à ce qui est attendu
            $nom = isset($post["nom_" . $tabname]) ? $post["nom_" . $tabname] : '';
            // Créer un nom de fichier unique avec nom_du_reseau
            $nom_fichier = strtolower($nom);
            break;
        case "images":
            $dossier = "Img_BDD/";
            $prefixe = "chemin_"; // Assurez-vous que le préfixe correspond à ce qui est attendu
            $nom = isset($post["id_" . $tabname]) ? $post["id_" . $tabname] : '';
            // Créer un nom de fichier unique avec nom_du_reseau
            $nom_fichier = strtolower($nom);
            break;
    }

    // Définir le chemin absolu du site
    global $chemin_absolu_site;

    // Assurez-vous que le chemin absolu se termine par un slash
    $chemin_absolu_site = rtrim($chemin_absolu_site, '/') . '/';

    // Récupérer l'extension du fichier
    $file_key = $prefixe . $tabname;
    if (!isset($files[$file_key])) {
        throw new Exception("Fichier non trouvé dans \$_FILES avec la clé : " . $file_key);
    }

    $extension = pathinfo($files[$file_key]["name"], PATHINFO_EXTENSION);

    // Ajouter l'extension au nom de fichier
    $nom_fichier_complet = str_replace(' ', '_', $nom_fichier) . '.' . $extension;

    // Construire le chemin complet pour le téléchargement
    $url_download = $chemin_absolu_site . "Image/" . $dossier . $nom_fichier_complet;

    // Vérifier si le dossier existe, sinon le créer
    $dossier_path = $chemin_absolu_site . "Image/" . $dossier;
    if (!is_dir($dossier_path)) {
        mkdir($dossier_path, 0755, true); // Créer le dossier avec les permissions appropriées
    }

    // Déplacer le fichier téléchargé vers son emplacement final
    if (move_uploaded_file($files[$file_key]["tmp_name"], $url_download)) {
        return $nom_fichier_complet; // Retourner uniquement le nom du fichier avec son extension
    } else {
        throw new Exception("Échec du téléchargement du fichier vers : " . $url_download);
    }
}


/**
 * Fonction qui me permet de préparer la liste d'input pour mes formulaires.
 * En fonction du nom de la table, elle prépare les éléments de formulaire 
 * pour modifier des données.
 * 
 * @param string $tabName Le nom de la table
 * @return array Un tableau contenant les éléments de formulaire
 */
function prepareTabInput($tabName)
{
    switch ($tabName) {
        case "engagement":
        case "avantage":
            $tabTemp = [

                ["text", "titre", "", "titre_" . $tabName, "", "", "Titre : "],
                ["textarea", "texte", "", "texte_" . $tabName, 'rows="20" cols="50"', "", "Texte : "]
            ];
            break;
        case "temoignage":
            $tabTemp = [
                ["text", "nom", "", "nom_temoignage", "", "", "Nom : "],
                ["text", "prenom", "", "prenom_temoignage", "", "", "Prénom : "],
                ["text", "metier", "", "metier_temoignage", "", "", "Métier : "],
                ["number", "", "", "age_temoignage", 'min ="0"', "", "Age : "],
                ["textarea", "texte", "", "texte_reformuler_temoignage", 'rows="50" cols="50"', "", "Texte reformuler : "],
                ["textarea", "texte", "", "texte_temoignage", 'rows="50" cols="50"', "", "Texte : "],
                ["file", "", "", "img_temoignage", "", "", "Importer la photo : "]
            ];
            break;
        case "reseaux_sociaux":
            $tabTemp = [
                ["text", "titre", "", "nom_" . $tabName, "", "", "Nom du réseau : "],
                ["text", "titre", "", "lien_" . $tabName, "", "", "Lien (ou mail) : "],
                ["file", "", "", "logo_" . $tabName, "", "", "Importer le logo : "]
            ];
            break;

    }
    // Ajouter les éléments reset et submit à $tabTemp
    array_unshift($tabTemp, ["reset", "", "", "reset", "", "Effacer", ""]);
    array_unshift($tabTemp, ["hidden", "", "", "tabName", "", $tabName, ""]);



    // Vérification et obtention de la position maximale
    if ($tabName == "temoignage" || $tabName == "avantage" || $tabName == "engagement" || $tabName == "reseaux_sociaux") {
        $nbrPosMaxTab = obtenirDonnees("MAX(pos_" . $tabName . ") AS maxPos", $tabName, '', '', 'fetch');
        $nbrPosMax = $nbrPosMaxTab["maxPos"] + 1;
        array_unshift($tabTemp, ["hidden", "", "", "pos_init", "", htmlspecialchars($nbrPosMax), ""]);
        array_push($tabTemp, ["number", "pos", "", "pos_" . $tabName, "min='1' max=" . htmlspecialchars($nbrPosMax), htmlspecialchars($nbrPosMax), "Position : "]);
    }
    array_push($tabTemp, ["submit", "", "", "", "", "Validez", ""]);

    return $tabTemp;
}

/**
 * Vérifie si l'utilisateur est connecté et dispose des droits d'accès administratifs.
 *
 * - Si l'utilisateur n'est pas connecté, il est redirigé vers la page de connexion.
 * - Si l'utilisateur est connecté, mais qu'il n'a pas le rôle "admin", il est également redirigé vers la page de connexion.
 * 
 * Cette fonction utilise les informations de session pour déterminer si l'utilisateur a une session valide.
 */
function isUserLoggedIn()
{
    startSession();
    if (!isset($_SESSION['id_users'])) {
        // Rediriger l'utilisateur non authentifié vers la page de connexion
        header('Location: adminIdentification.php');
        exit();
    } else {
        // Récupère les informations de l'utilisateur depuis la base de données
        $userData = obtenirDonnees("*", "users", "id_users = " . intval($_SESSION['id_users']), "", 'fetch');
        if ($userData['role'] === 'user') {
            // Rediriger l'utilisateur non authentifié vers la page de connexion
            header('Location: adminIdentification.php');
            exit();
        }
    }
}


/**
 * Démarre une session ou reprend une session existante.
 *
 * Spécifie le nom de la session pour éviter les conflits avec d'autres applications.
 * Configure les paramètres du cookie de session pour une durée de vie de 1 heure.
 * Empêche l'accès au cookie via JavaScript.
 * Empêche l'envoi du cookie via des requêtes intersites.
 * Regénère l'ID de session toutes les 30 minutes pour éviter le vol de session.
 * Vérifie l'inactivité et détruit la session si plus de 1 heure d'inactivité.
 * Met à jour le timestamp de la dernière activité.
 */
function startSession()
{
    // Spécifiez le nom de la session pour éviter les conflits avec d'autres applications
    session_name('ADMIN_SESSION');

    // Configuration des paramètres du cookie de session
    $sessionCookieParams = [
        'lifetime' => 3600,           // Durée de vie de la session : 1 heure (3600 secondes)
        'path' => '/',                // Le cookie est accessible sur tout le site
        'domain' => '',               // Le domaine par défaut (pour tout le domaine actuel)
        'secure' => isset($_SERVER['HTTPS']), // Si le site utilise HTTPS, le cookie n'est envoyé que via HTTPS
        'httponly' => true,           // Empêche l'accès au cookie via JavaScript
        'samesite' => 'Strict'        // Le cookie n'est pas envoyé avec les requêtes intersites
    ];

    session_set_cookie_params($sessionCookieParams);

    // Démarrer la session ou reprendre une session existante
    session_start();

    // Regénérer l'ID de session pour éviter le vol de session
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } elseif (time() - $_SESSION['created'] > 1800) { // Regénère l'ID toutes les 30 minutes
        session_regenerate_id(true);    // Regénère l'ID de session et supprime l'ancien
        $_SESSION['created'] = time();  // Met à jour le timestamp de création
    }

    // Vérifier l'inactivité
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
        // Si plus de 1 heure d'inactivité, détruire la session
        session_unset();     // Supprime toutes les variables de session
        session_destroy();   // Détruit la session
        header("Location: identification.php"); // Redirige vers la page de connexion
        exit();
    }

    // Mettre à jour le timestamp de la dernière activité
    $_SESSION['last_activity'] = time();
}
?>