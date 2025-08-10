document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formRegistro');
  const mensajeError = document.getElementById('mensajeError');

  form.addEventListener('submit', function (e) {
    mensajeError.textContent = '';
    let errores = [];

    const id = document.getElementById('identificacion').value.trim();
    const usuario = document.getElementById('usuario').value.trim();
    const password = document.getElementById('password').value.trim();
    const nombre = document.getElementById('nombre').value.trim();
    const apellido1 = document.getElementById('apellido1').value.trim();
    const apellido2 = document.getElementById('apellido2').value.trim();
    const correo = document.getElementById('correo').value.trim();
    const telefono = document.getElementById('telefono').value.trim();

    // Validación de identificación
    if (!/^\d{9,12}$/.test(id)) {
      errores.push('La identificación debe tener entre 9 y 12 dígitos numéricos.');
    }

    // Validación de usuario
    if (usuario.length < 4 || /\s/.test(usuario)) {
      errores.push('El usuario debe tener al menos 4 caracteres y no contener espacios.');
    }

    // Validación de contraseña
    if (password.length < 6 || !/[A-Za-z]/.test(password) || !/\d/.test(password)) {
      errores.push('La contraseña debe tener al menos 6 caracteres, incluyendo letras y números.');
    }

    // Validación de nombre y apellidos
    const soloLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
    if (!soloLetras.test(nombre)) {
      errores.push('El nombre solo debe contener letras.');
    }
    if (!soloLetras.test(apellido1)) {
      errores.push('El primer apellido solo debe contener letras.');
    }
    if (!soloLetras.test(apellido2)) {
      errores.push('El segundo apellido solo debe contener letras.');
    }

    // Validación de correo
    const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!correoRegex.test(correo)) {
      errores.push('El correo electrónico no tiene un formato válido.');
    }

    // Validación de teléfono
    if (!/^\d{8}$/.test(telefono)) {
      errores.push('El teléfono debe tener exactamente 8 dígitos numéricos.');
    }

    // Mostrar errores si existen
    if (errores.length > 0) {
      e.preventDefault();
      mensajeError.innerHTML = errores.map(err => `<p>${err}</p>`).join('');
    }
  });
});
