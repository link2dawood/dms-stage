document.addEventListener('DOMContentLoaded', () => {
    const closeBtn = document.querySelector('.sab-close');
    if (!closeBtn) return;

    closeBtn.addEventListener('click', () => {
        document.cookie = 'sab_closed=1; path=/; max-age=2592000';
        closeBtn.parentElement.style.display = 'none';
    });
});
