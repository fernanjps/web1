document.addEventListener('DOMContentLoaded', () => {
    const formAgregar = document.getElementById('form-agregar');

    formAgregar.addEventListener('submit', (e) => {
        const titulo = document.getElementById('titulo').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        const fechaLimite = document.getElementById('fecha_limite').value.trim();

        if (titulo === '' || descripcion === '' || fechaLimite === '') {
            e.preventDefault();
            alert('Todos los campos son obligatorios.');
        } else if (new Date(fechaLimite) < new Date()) {
            e.preventDefault();
            alert('La fecha límite no puede ser anterior a la fecha actual.');
        }
    });
});
