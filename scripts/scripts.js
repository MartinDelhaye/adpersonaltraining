/**
 * Bascule une classe sur un élément.
 * @param {Element} element - L'élément cible.
 * @param {string} className - Le nom de la classe à basculer.
 * @param {boolean} [force] - Si défini, ajoute ou supprime la classe selon sa valeur.
 */
function toggleClass(element, className, force) {
    if (element) {
        if (force !== undefined) {
            element.classList.toggle(className, force);
        } else {
            element.classList.toggle(className);
        }
    }
}

/**
 * Ajuste le padding supérieur du premier élément du contenu principal en fonction de la hauteur du header.
 */
function ajusterPaddingTop() {
    const header = document.querySelector('header');
    const premierElementContenu = document.querySelector('main > *:first-child');

    if (header && premierElementContenu) {
        premierElementContenu.style.paddingTop = `${header.offsetHeight}px`;
    }
}

/**
 * Vérifie si les éléments avec la classe `.anim-appear` sont visibles dans la fenêtre.
 * Ajoute la classe `show` pour activer leur animation si nécessaire.
 */
function checkVisibilityAnimAppear() {
    const elements = document.querySelectorAll('.anim-appear');
    const windowHeight = window.innerHeight;

    elements.forEach(element => {
        const rect = element.getBoundingClientRect();
        const offset = 100; // Décalage pour déclencher l'animation avant la fin de la zone visible

        if (rect.top < windowHeight - offset && rect.bottom > offset) {
            toggleClass(element, 'show', true);
        }
    });
}

/**
 * Affiche une confirmation avant l'action d'un formulaire.
 * @returns {boolean} - True si l'utilisateur confirme, false sinon.
 */
function confirmForm() {
    return confirm("Êtes-vous sûr de vouloir faire cette action ? \n(Cette action peut être irréversible)");
}

/**
 * Gère le menu de navigation pour mobile (menu burger).
 */
function handleMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            const isExpanded = navMenu.classList.contains('show');
            toggleClass(navMenu, 'show', !isExpanded); 
            menuToggle.innerHTML = isExpanded ? '&#9776;' : '&times;';
        });
        navMenu.addEventListener('click', () => {
            const isExpanded = toggleClass(navMenu, 'show');
            menuToggle.innerHTML = isExpanded ? '&times;' : '&#9776;';
        })
    }
}

/**
 * Gère l'affichage du bouton "Remonter en haut de la page".
 */
function setupScrollToTopButton() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');
    if (scrollToTopBtn) {
        const pointTop = 200;
        window.addEventListener('scroll', () => {
            const shouldShow = document.body.scrollTop > pointTop || document.documentElement.scrollTop > pointTop;
            toggleClass(scrollToTopBtn, 'show', shouldShow);
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0 });
        });
    }
}

/**
 * Fonction principale exécutée au chargement de la page.
 */
function windowLoad() {
    ajusterPaddingTop();
    window.addEventListener('resize', ajusterPaddingTop);

    const currentPage = window.location.pathname;
    if (currentPage.includes('admin.php')) {
        const scrollTop = sessionStorage.getItem(`scrollTop-${currentPage}`);
        if (scrollTop !== null) {
            window.scrollTo(0, parseInt(scrollTop, 10));
        }
        window.addEventListener('scroll', () => {
            sessionStorage.setItem(`scrollTop-${currentPage}`, window.scrollY);
        });
    }

    setupScrollToTopButton();
    handleMobileMenu();
    checkVisibilityAnimAppear();
    window.addEventListener('scroll', checkVisibilityAnimAppear);

    const formNoReturn = document.querySelectorAll('.formNoReturn');
    if (formNoReturn) {
        formNoReturn.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                if (confirmForm()) {
                    form.submit();
                }
            });
        });
    }
}

window.addEventListener('load', windowLoad);
