document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formulario');

    form.addEventListener('submit', (event) => {
        let valid = true;

        form.querySelectorAll('select').forEach(select => {
            if (select.value === '') {
                valid = false;
                select.style.border = '2px solid red';
            } else {
                select.style.border = '';
            }
        });

        if (!valid) {
            event.preventDefault();
            alert('Por favor, responda todas las preguntas antes de enviar el formulario.');
        }
    });
});
