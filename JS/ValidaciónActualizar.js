const form = document.getElementById('formActualizar');
const mensajeError = document.getElementById('mensajeError');

form.addEventListener('submit', e => {
  const errores = [];

  const usuario = form.usuario.value.trim();
  const password = form.password.value;
  const correo = form.correo.value.trim();
  const telefono = form.telefono.value.trim();

  if (usuario.length < 4) {
    errores.push('El usuario debe tener al menos 4 caracteres.');
  }

  if (password && password.length < 6) {
    errores.push('La nueva contraseña debe tener al menos 6 caracteres.');
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
    errores.push('Correo electrónico inválido.');
  }

  if (!/^[0-9]{8}$/.test(telefono)) {
    errores.push('Teléfono inválido (8 dígitos).');
  }

  if (errores.length) {
    e.preventDefault();
    mensajeError.innerHTML = '<ul><li>' + errores.join('</li><li>') + '</li></ul>';
  }
});
