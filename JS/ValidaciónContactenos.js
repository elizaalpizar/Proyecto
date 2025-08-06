document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const name    = document.getElementById('name');
  const email   = document.getElementById('email');
  const message = document.getElementById('message');
  const rating  = document.getElementById('rating');
  const formMessage = document.getElementById('formMessage');
  
  // Limpia mensajes anteriores
  formMessage.textContent = '';
  
  // Validaciones básicas
  if (!name.value.trim()) {
    formMessage.textContent = 'El nombre es obligatorio.';
    name.focus();
    return;
  }
  if (!email.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
    formMessage.textContent = 'Ingresa un correo válido.';
    email.focus();
    return;
  }
  if (!message.value.trim()) {
    formMessage.textContent = 'El mensaje no puede estar vacío.';
    message.focus();
    return;
  }
  if (!rating.value) {
    formMessage.textContent = 'Selecciona una calificación.';
    rating.focus();
    return;
  }
  
  // Si todo está bien, envía el formulario
  this.submit();
});