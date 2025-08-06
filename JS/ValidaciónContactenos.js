document.getElementById('contactForm').addEventListener('submit', function (e) {
  e.preventDefault();

  // Referencias a inputs y mensajes
  const nameField = document.getElementById('name');
  const emailField = document.getElementById('email');
  const messageField = document.getElementById('message');
  const ratingField = document.getElementById('rating');

  const nameError = document.getElementById('nameError');
  const emailError = document.getElementById('emailError');
  const messageError = document.getElementById('messageError');
  const ratingError = document.getElementById('ratingError');

  // Expresiones regulares
  const nameRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
  const emailRegex = /^[^\s@]+@[^\s@]+\.(com)$/i;

  // Limpiar errores previos
  [nameError, emailError, messageError, ratingError].forEach(el => el.textContent = '');

  // Valores
  const nameVal = nameField.value.trim();
  const emailVal = emailField.value.trim();
  const messageVal = messageField.value.trim();
  const ratingVal = ratingField.value;

  // Indicador de validez
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

  // Mostrar alert y redirigir si todo está bien
  if (isValid) {
    const data = {
      name: nameVal,
      email: emailVal,
      message: messageVal,
      rating: ratingVal
    };

    fetch('https://formspree.io/f/xyzabc', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
      .then(resp => {
        if (resp.ok) {
          alert('Tu consulta fue enviada con éxito.');
          window.location.href = '../Público/PaginaPrincipal.html';
        } else {
          throw new Error('Falló el envío');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Error al enviar tu consulta. Intenta de nuevo más tarde.');
      });
  }

});