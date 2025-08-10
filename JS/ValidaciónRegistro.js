// ValidacionRegistro.js

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formRegistro');
  const mensajeError = document.getElementById('mensajeError');

  form.addEventListener('submit', function (e) {
    mensajeError.textContent = '';

    const id = document.getElementById('identificacion').value.trim();
    const usuario = document.getElementById('usuario').value.trim();
    const password = document.getElementById('password').value.trim();
    const telefono = document.getElementById('telefono').value.trim();

    if (!/^\d{9,12}$/.test(id)) {
      e.preventDefault();
      mensajeError.textContent = 'La identificación debe tener entre 9 y 12 dígitos numéricos.';
      return;
    }

    if (usuario.length < 4) {
      e.preventDefault();
      mensajeError.textContent = 'El usuario debe tener al menos 4 caracteres.';
      return;
    }

    if (password.length < 6) {
      e.preventDefault();
      mensajeError.textContent = 'La contraseña debe tener al menos 6 caracteres.';
      return;
    }

    if (!/^\d{8}$/.test(telefono)) {
      e.preventDefault();
      mensajeError.textContent = 'El teléfono debe tener exactamente 8 dígitos numéricos.';
      return;
    }
  });
});
