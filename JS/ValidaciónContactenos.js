document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();

  // Referencias a inputs y mensajes de error
  const nameField    = document.getElementById('name');
  const emailField   = document.getElementById('email');
  const messageField = document.getElementById('message');
  const ratingField  = document.getElementById('rating');

  const nameError    = document.getElementById('nameError');
  const emailError   = document.getElementById('emailError');
  const messageError = document.getElementById('messageError');
  const ratingError  = document.getElementById('ratingError');

  // Expresiones regulares
  const nameRegex  = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
  const emailRegex = /^[^\s@]+@[^\s@]+\.(com)$/i;

  // Limpiar errores previos
  [nameError, emailError, messageError, ratingError].forEach(el => el.textContent = '');

  // Valores a validar
  const nameVal    = nameField.value.trim();
  const emailVal   = emailField.value.trim();
  const messageVal = messageField.value.trim();
  const ratingVal  = ratingField.value;

  // Bandera global de validez
  let isValid = true;

  // Validar Nombre
  if (!nameVal) {
    nameError.textContent = 'El nombre es obligatorio.';
    isValid = false;
  } else if (!nameRegex.test(nameVal)) {
    nameError.textContent = 'Solo letras, tildes y espacios.';
    isValid = false;
  }

  // Validar Email
  if (!emailVal) {
    emailError.textContent = 'El correo es obligatorio.';
    isValid = false;
  } else if (!emailRegex.test(emailVal)) {
    emailError.textContent = 'Formato: usuario@dominio.com';
    isValid = false;
  }

  // Validar Mensaje
  if (!messageVal) {
    messageError.textContent = 'El mensaje no puede estar vacío.';
    isValid = false;
  }

  // Validar Calificación
  if (!ratingVal) {
    ratingError.textContent = 'Selecciona una calificación.';
    isValid = false;
  }

  // Si todo es válido, mostrar alerta y redirigir
  if (isValid) {
    alert('Tu consulta fue enviada con éxito.');
    window.location.href = 'index.html';
  } else {
    // Opcional: enfocar el primer campo con error
    const firstError = document.querySelector('.error-message:not(:empty)');
    if (firstError) {
      const fieldId = firstError.id.replace('Error', '');
      document.getElementById(fieldId).focus();
    }
  }
});