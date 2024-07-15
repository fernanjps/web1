document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');

    registerForm.addEventListener('submit', function(event) {
        const nombre = registerForm.nombre.value.trim();
        const apellido = registerForm.apellido.value.trim();
        const telefono = registerForm.telefono.value.trim();
        const correo = registerForm.correo.value.trim();
        const contraseña = registerForm.contraseña.value.trim();
        const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const telefonoRegex = /^[0-9]+$/;

        if (!nombre || !apellido || !telefono || !correo || !contraseña) {
            alert('Por favor, complete todos los campos.');
            event.preventDefault();
            return;
        }

        if (!correoRegex.test(correo)) {
            alert('Por favor, ingrese un correo electrónico válido.');
            event.preventDefault();
            return;
        }

        if (!telefonoRegex.test(telefono)) {
            alert('Por favor, ingrese un número de teléfono válido.');
            event.preventDefault();
            return;
        }

        if (contraseña.length < 6) {
            alert('La contraseña debe tener al menos 6 caracteres.');
            event.preventDefault();
            return;
        }
    });
});
