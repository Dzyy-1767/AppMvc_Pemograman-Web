document.documentElement.classList.add('js');

const toast = document.querySelector('[data-toast]');

if (toast) {
    const sembunyikanToast = () => {
        toast.classList.add('toast--hilang');
        window.setTimeout(() => toast.remove(), 200);
    };

    window.setTimeout(sembunyikanToast, 4000);
}

const tombolMenu = document.querySelector('.menu-toggle');
const navigasi = document.querySelector('.nav-panel');
const tautanMenu = document.querySelectorAll('.nav-link');
const overlayMenu = document.querySelector('[data-menu-overlay]');
const batasTablet = window.matchMedia('(min-width: 600px)');

if (tombolMenu && navigasi && overlayMenu) {
    const tutupMenu = (kembalikanFokus = false) => {
        document.body.classList.remove('menu-terbuka');
        tombolMenu.setAttribute('aria-expanded', 'false');
        tombolMenu.setAttribute('aria-label', 'Buka menu');
        if (kembalikanFokus) {
            tombolMenu.focus();
        }
    };

    const ubahMenu = () => {
        const terbuka = tombolMenu.getAttribute('aria-expanded') !== 'true';
        document.body.classList.toggle('menu-terbuka', terbuka);
        tombolMenu.setAttribute('aria-expanded', String(terbuka));
        tombolMenu.setAttribute('aria-label', terbuka ? 'Tutup menu' : 'Buka menu');
    };

    tombolMenu.addEventListener('click', ubahMenu);
    overlayMenu.addEventListener('click', () => tutupMenu());
    tautanMenu.forEach((tautan) => tautan.addEventListener('click', () => tutupMenu()));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && tombolMenu.getAttribute('aria-expanded') === 'true') {
            tutupMenu(true);
        }
    });

    document.addEventListener('click', (event) => {
        if (
            tombolMenu.getAttribute('aria-expanded') === 'true' &&
            !navigasi.contains(event.target) &&
            !tombolMenu.contains(event.target)
        ) {
            tutupMenu();
        }
    });

    const bersihkanMenuMobile = () => {
        if (batasTablet.matches) {
            tutupMenu();
        }
    };

    batasTablet.addEventListener('change', bersihkanMenuMobile);
}
