document.getElementById('contactForm').addEventListener('submit', function (e) {
  const nameField = document.getElementById('name');
  const emailField = document.getElementById('email');
  const messageField = document.getElementById('message');
  const ratingField = document.getElementById('rating');

  const nameError = document.getElementById('nameError');
  const emailError = document.getElementById('emailError');
  const messageError = document.getElementById('messageError');
  const ratingError = document.getElementById('ratingError');

  const nameRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
  const emailRegex = /^[^\s@]+@[^\s@]+\.(com)$/i;

  [nameError, emailError, messageError, ratingError].forEach(el => el.textContent = '');

  const nameVal = nameField.value.trim();
  const emailVal = emailField.value.trim();
  const messageVal = messageField.value.trim();
  const ratingVal = ratingField.value;

  let isValid = true;

  if (!nameVal) {
    nameError.textContent = 'El nombre es obligatorio.';
    isValid = false;
  } else if (!nameRegex.test(nameVal)) {
    nameError.textContent = 'Solo letras, tildes y espacios.';
    isValid = false;
  }

  if (!emailVal) {
    emailError.textContent = 'El correo es obligatorio.';
    isValid = false;
  } else if (!emailRegex.test(emailVal)) {
    emailError.textContent = 'Formato: usuario@dominio.com';
    isValid = false;
  }

  if (!messageVal) {
    messageError.textContent = 'El mensaje no puede estar vacío.';
    isValid = false;
  }

  if (!ratingVal) {
    ratingError.textContent = 'Selecciona una calificación.';
    isValid = false;
  }

  if (!isValid) {
    e.preventDefault();
  }
});