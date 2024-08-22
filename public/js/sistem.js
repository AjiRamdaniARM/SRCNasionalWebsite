document.addEventListener('DOMContentLoaded', function () {
    const modalHideButtons = document.querySelectorAll('[data-modal-hide="authentication-modal"]');

    modalHideButtons.forEach(button => {
        button.addEventListener('click', function () {
            const modal = document.getElementById('authentication-modal');
            modal.classList.add('hidden');
        });
    });
});

