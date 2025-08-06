document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();

  // Referencias a campos y sus contenedores de error
  const nameField    = document.getElementById('name');
  const emailField   = document.getElementById('email');
  const messageField = document.getElementById('message');
  const ratingField  = document.getElementById('rating');

  const nameError    = document.getElementById('nameError');
  const emailError   = document.getElementById('emailError');
  const messageError = document.getElementById('messageError');
  const ratingError  = document.getElementById('ratingError');

  // Expresiones regulares
  const nameRegex    = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
  const emailRegex   = /^[^\s@]+@[^\s@]+\.(com)$/i;

  // Limpiar errores anteriores
  [nameError, emailError, messageError, ratingError].forEach(el => {
    el.textContent = '';
  });

  // Validación nombre
  const nameVal = nameField.value.trim();
  if (!nameVal) {
    nameError.textContent = 'El nombre es obligatorio.';
    nameField.focus();
    return;
  }
  if (!nameRegex.test(nameVal)) {
    nameError.textContent = 'Solo letras, tildes y espacios.';
    nameField.focus();
    return;
  }

  // Validación email
  const emailVal = emailField.value.trim();
  if (!emailVal) {
    emailError.textContent = 'El correo es obligatorio.';
    emailField.focus();
    return;
  }
  if (!emailRegex.test(emailVal)) {
    emailError.textContent = 'Formato: usuario@dominio.com';
    emailField.focus();
    return;
  }

  // Validación mensaje
  const messageVal = messageField.value.trim();
  if (!messageVal) {
    messageError.textContent = 'El mensaje no puede estar vacío.';
    messageField.focus();
    return;
  }

  // Validación calificación
  if (!ratingField.value) {
    ratingError.textContent = 'Selecciona una calificación.';
    ratingField.focus();
    return;
  }

  // Si todo está bien, confirmar y redirigir
  alert('Tu consulta fue enviada con éxito.');
  window.location.href = 'index.html';
});