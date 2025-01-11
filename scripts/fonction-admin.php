<?php 
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
    echo '<article id="' . $id . '" class="' . $class . ' bg-color-main">';
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