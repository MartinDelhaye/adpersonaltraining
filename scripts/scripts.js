// Ajuste le padding-top du contenu principal en fonction de la hauteur du header
function ajusterPaddingTop() {
    const header = document.querySelector('header');
    const premierElementContenu = document.querySelector('main > *:first-child');

    if (header && premierElementContenu) {
        premierElementContenu.style.paddingTop = `${header.offsetHeight}px`;
    }
}


// Gère l'affichage des témoignages (ouverture/fermeture)
function toggleDivVisibility(divId, buttonId) {
    const div = document.getElementById(divId);
    const button = document.getElementById(buttonId);

    let isHidden = true; // Variable pour savoir si le contenu est caché ou visible
    button.addEventListener('click', () => {
        if (isHidden) {
            // Affiche le contenu
            div.classList.add('show');
            div.style.height = div.scrollHeight + 'px'; 
            setTimeout(() => {
                div.style.height = 'auto'; 
            }, 200);
        } else {
            // Cache le contenu
            div.style.height = div.scrollHeight + 'px'; 
            setTimeout(() => {
                div.classList.remove('show');
                div.style.height = '0'; 
            }, 200); 
        }

        // Changer l'icône du bouton (ajouter/retirer le suffixe '-actif')
        const icon = button.querySelector('img.icon');
        if (icon) {
            // const currentSource = icon.src;
            // icon.src = currentSource.includes('-actif') ? 
            //     currentSource.replace('-actif', '') : 
            //     currentSource.replace(/(\.[\w\d_-]+)$/i, '-actif$1');
            icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        }

        isHidden = !isHidden; // Inverse l'état de visibilité
    });
}


// ------------- Fonctions Callback -------------


// Vérifie la visibilité des éléments pour activer leur animation
function checkVisibilityAnimAppear() {
    const elements = document.querySelectorAll('.anim-appear');
    const windowHeight = window.innerHeight;

    if (elements) {
        elements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const offset = 100;  // Décalage pour déclencher l'animation avant la fin de la zone visible

            if (rect.top < windowHeight - offset && rect.bottom > offset) {
                element.classList.add('show');
            }
        });
    }
}

// Affiche une confirmation avant l'action d'un formulaire
function confirmForm() {
    return confirm("Êtes-vous sûr de vouloir faire cette action ? \n(Cette action peut être irréversible)");
}


// ------------- Fonctions de Chargement de Page -------------
// Fonction au chargement de la page
function windowLoad() {

    // Ajuste le padding en fonction de la hauteur du header
    ajusterPaddingTop();

    // Met à jour le padding lors du redimensionnement de la fenêtre
    window.addEventListener('resize', ajusterPaddingTop);

    // Vérifie si l'utilisateur est sur la page "admin.php"
    const currentPage = window.location.pathname;

    if (currentPage.includes('admin.php')) {
        // Restaurer la position du scroll depuis le sessionStorage
        const scrollTop = sessionStorage.getItem(`scrollTop-${currentPage}`);
        if (scrollTop !== null) {
            window.scrollTo(0, parseInt(scrollTop, 10));
        }

        // Enregistre la position du scroll à chaque défilement
        window.addEventListener('scroll', () => {
            sessionStorage.setItem(`scrollTop-${currentPage}`, window.scrollY);
        });
    }

    // Gère l'affichage des témoignages
    const buttons_temoignage = document.querySelectorAll('button.texte_temoignage_buttons');
    const divs_temoignage = document.querySelectorAll('.texte_temoignage_blocs');

    if (buttons_temoignage.length > 0 && divs_temoignage.length > 0) {
        buttons_temoignage.forEach((button, index) => {
            toggleDivVisibility('texte_temoignage_bloc_' + index, 'texte_temoignage_boutton_' + index);
        });
    }

    // Gère l'affichage du bouton pour remonter en haut de la page
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');

    if (scrollToTopBtn) {
        // Affiche le bouton lorsqu'on fait défiler la page
        window.onscroll = function() {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        };

        // Remonte en haut de la page lorsqu'on clique sur le bouton
        scrollToTopBtn.onclick = function() {
            window.scrollTo({ top: 0 });
        };
    } 

    // Gère la navigation sur mobile (menu burger)
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (menuToggle) {
        // Fonction pour ouvrir ou fermer le menu
        function toggleMenu() {
            // Met à jour le contenu du bouton en fonction de l'état du menu (ouvert/fermé)
            menuToggle.innerHTML = navMenu.classList.toggle('show') ? '&times;' : '&#9776;';
        }
        // Ajoute un événement de clic pour ouvrir et fermer le menu
        menuToggle.addEventListener('click', toggleMenu);
    }
    
    // Vérifie la visibilité des éléments animés au chargement et lors du défilement
    checkVisibilityAnimAppear();
    window.addEventListener('scroll', checkVisibilityAnimAppear);

    // Gère les formulaires qui nécessitent une confirmation avant soumission
    let formNoReturn = document.querySelectorAll('.formNoReturn');

    if (formNoReturn) {
        formNoReturn.forEach(form => {
            form.addEventListener('submit', function(event) {
                // Empêche l'envoi immédiat du formulaire
                event.preventDefault();

                // Affiche une confirmation
                const confirmation = confirm("Êtes-vous sûr de vouloir faire cette action ? \n(Cette action peut être irréversible)");

                // Si l'utilisateur confirme, soumet le formulaire
                if (confirmation) {
                    form.submit();
                }
            });
        });
    }
}


// Ajouter un écouteur d'événement pour le chargement de la page
window.addEventListener('load', windowLoad);
