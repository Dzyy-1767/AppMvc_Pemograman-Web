const toast = document.querySelector('[data-toast]');

if (toast) {
    const sembunyikanToast = () => {
        toast.classList.add('toast--hilang');
        window.setTimeout(() => toast.remove(), 200);
    };

    window.setTimeout(sembunyikanToast, 4000);
}
