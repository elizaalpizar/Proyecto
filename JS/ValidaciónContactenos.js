document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const nameField    = document.getElementById('name');
  const emailField   = document.getElementById('email');
  const messageField = document.getElementById('message');
  const ratingField  = document.getElementById('rating');
  const feedback     = document.getElementById('formMessage');

  feedback.textContent = '';
  feedback.style.color = 'red';

  // Regex para nombre (solo letras, tildes y espacios)
  const nameRegex  = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
  // Regex para email terminado en .com
  const emailRegex = /^[^\s@]+@[^\s@]+\.(com)$/i;

  if (!nameField.value.trim()) {
    feedback.textContent = 'El nombre es obligatorio.';
    nameField.focus();
    return;
  }
  
  if (!nameRegex.test(nameField.value.trim())) {
    feedback.textContent = 'El nombre solo puede contener letras y espacios.';
    nameField.focus();
    return;
  }

  if (!emailField.value.trim()) {
    feedback.textContent = 'El correo es obligatorio.';
    emailField.focus();
    return;
  }

  if (!emailRegex.test(emailField.value.trim())) {
    feedback.textContent = 'El correo debe tener formato usuario@dominio.com.';
    emailField.focus();
    return;
  }

  if (!messageField.value.trim()) {
    feedback.textContent = 'El mensaje no puede estar vacío.';
    messageField.focus();
    return;
  }

  if (!ratingField.value) {
    feedback.textContent = 'Selecciona una calificación.';
    ratingField.focus();
    return;
  }

  // Si todas las validaciones pasan
  alert('Tu consulta fue enviada con éxito.');
  // Redireccionar a la página principal
  window.location.href = '../Público/PaginaPrincipal.html';
});