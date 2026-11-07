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

const sembunyikanSisiKartu = (sisi, sembunyi) => {
    sisi.setAttribute('aria-hidden', String(sembunyi));
    if ('inert' in sisi) {
        sisi.inert = sembunyi;
    }

    sisi.querySelectorAll('a, button, input, select, textarea, [tabindex]').forEach((elemen) => {
        if (sembunyi) {
            if (elemen.dataset.tabindexDisimpan !== 'true') {
                elemen.dataset.tabindexAwal = elemen.getAttribute('tabindex') ?? '';
                elemen.dataset.tabindexDisimpan = 'true';
            }
            elemen.setAttribute('tabindex', '-1');
        } else if (elemen.dataset.tabindexDisimpan === 'true') {
            if (elemen.dataset.tabindexAwal === '') {
                elemen.removeAttribute('tabindex');
            } else {
                elemen.setAttribute('tabindex', elemen.dataset.tabindexAwal);
            }
            delete elemen.dataset.tabindexAwal;
            delete elemen.dataset.tabindexDisimpan;
        }
    });
};

const tutupKartu = (kartu, kembalikanFokus = false) => {
    const pemicu = kartu.querySelector('[data-kartu-pemicu]');
    const sisiDepan = kartu.querySelector('[data-kartu-depan]');
    const sisiBelakang = kartu.querySelector('[data-kartu-belakang]');
    const tombolKembali = kartu.querySelector('[data-kartu-kembali]');

    kartu.classList.remove('kartu--balik');
    pemicu.setAttribute('aria-expanded', 'false');
    sembunyikanSisiKartu(sisiDepan, false);
    sembunyikanSisiKartu(sisiBelakang, true);
    tombolKembali.hidden = false;

    if (kembalikanFokus) {
        pemicu.focus();
    }
};

const bukaKartu = (kartu) => {
    const kartuTerbuka = document.querySelector('[data-kartu-flip].kartu--balik');
    if (kartuTerbuka && kartuTerbuka !== kartu) {
        tutupKartu(kartuTerbuka);
    }

    const pemicu = kartu.querySelector('[data-kartu-pemicu]');
    const sisiDepan = kartu.querySelector('[data-kartu-depan]');
    const sisiBelakang = kartu.querySelector('[data-kartu-belakang]');
    const tombolKembali = kartu.querySelector('[data-kartu-kembali]');

    kartu.classList.add('kartu--balik');
    pemicu.setAttribute('aria-expanded', 'true');
    sembunyikanSisiKartu(sisiDepan, true);
    sembunyikanSisiKartu(sisiBelakang, false);
    tombolKembali.hidden = false;
    sisiBelakang.querySelector('a, button')?.focus();
};

document.querySelectorAll('[data-daftar-kartu]').forEach((daftar) => {
    daftar.querySelectorAll('[data-kartu-flip]').forEach((kartu) => {
        const pemicu = kartu.querySelector('[data-kartu-pemicu]');
        pemicu.setAttribute('role', 'button');
        pemicu.setAttribute('tabindex', '0');
        pemicu.setAttribute('aria-expanded', 'false');
        sembunyikanSisiKartu(kartu.querySelector('[data-kartu-depan]'), false);
        sembunyikanSisiKartu(kartu.querySelector('[data-kartu-belakang]'), true);
        kartu.querySelector('[data-kartu-kembali]').hidden = false;
    });

    daftar.addEventListener('click', (event) => {
        const pemicu = event.target.closest('[data-kartu-pemicu]');
        if (pemicu && daftar.contains(pemicu)) {
            bukaKartu(pemicu.closest('[data-kartu-flip]'));
            return;
        }

        const tombolKembali = event.target.closest('[data-kartu-kembali]');
        if (tombolKembali && daftar.contains(tombolKembali)) {
            tutupKartu(tombolKembali.closest('[data-kartu-flip]'), true);
        }
    });

    daftar.addEventListener('keydown', (event) => {
        const pemicu = event.target.closest('[data-kartu-pemicu]');
        if (pemicu && (event.key === 'Enter' || event.key === ' ')) {
            event.preventDefault();
            bukaKartu(pemicu.closest('[data-kartu-flip]'));
        }
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        const kartuTerbuka = document.querySelector('[data-kartu-flip].kartu--balik');
        if (kartuTerbuka) {
            event.preventDefault();
            tutupKartu(kartuTerbuka, true);
        }
    }
});

document.addEventListener('click', (event) => {
    const kartuTerbuka = document.querySelector('[data-kartu-flip].kartu--balik');
    if (kartuTerbuka && !kartuTerbuka.contains(event.target)) {
        tutupKartu(kartuTerbuka);
    }
});
