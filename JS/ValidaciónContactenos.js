document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('contactForm');
  
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      if (validateForm()) {
        submitForm();
      }
    });
  }
});

function validateForm() {
  const nameField = document.getElementById('name');
  const emailField = document.getElementById('email');
  const messageField = document.getElementById('message');
  const ratingField = document.getElementById('rating');

  const nameError = document.getElementById('nameError');
  const emailError = document.getElementById('emailError');
  const messageError = document.getElementById('messageError');
  const ratingError = document.getElementById('ratingError');

  // Limpiar mensajes de error anteriores
  [nameError, emailError, messageError, ratingError].forEach(el => {
    if (el) el.textContent = '';
  });

  const nameVal = nameField.value.trim();
  const emailVal = emailField.value.trim();
  const messageVal = messageField.value.trim();
  const ratingVal = ratingField.value;

  let isValid = true;

  // Validar nombre
  if (!nameVal) {
    if (nameError) nameError.textContent = 'El nombre es obligatorio.';
    isValid = false;
  } else if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(nameVal)) {
    if (nameError) nameError.textContent = 'Solo letras, tildes y espacios.';
    isValid = false;
  }

  // Validar email
  if (!emailVal) {
    if (emailError) emailError.textContent = 'El correo es obligatorio.';
    isValid = false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
    if (emailError) emailError.textContent = 'Formato de email inválido.';
    isValid = false;
  }

  // Validar mensaje
  if (!messageVal) {
    if (messageError) messageError.textContent = 'El mensaje no puede estar vacío.';
    isValid = false;
  } else if (messageVal.length < 10) {
    if (messageError) messageError.textContent = 'El mensaje debe tener al menos 10 caracteres.';
    isValid = false;
  }

  // Validar rating
  if (!ratingVal) {
    if (ratingError) ratingError.textContent = 'Selecciona una calificación.';
    isValid = false;
  }

  return isValid;
}

function submitForm() {
  const form = document.getElementById('contactForm');
  const formData = new FormData(form);
  
  // Mostrar indicador de carga
  const submitBtn = form.querySelector('.submit-btn');
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
  submitBtn.disabled = true;

  fetch('../Php/Contactenos.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showMessage('success', data.message);
      form.reset();
    } else {
      showMessage('error', data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showMessage('error', 'Error al enviar el formulario. Por favor, inténtalo de nuevo.');
  })
  .finally(() => {
    // Restaurar botón
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
  });
}

function showMessage(type, message) {
  // Crear o actualizar mensaje de notificación
  let notification = document.getElementById('notification');
  
  if (!notification) {
    notification = document.createElement('div');
    notification.id = 'notification';
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 20px;
      border-radius: 5px;
      color: white;
      font-weight: 600;
      z-index: 10000;
      max-width: 400px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    document.body.appendChild(notification);
  }

  notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
  notification.textContent = message;

  // Auto-ocultar después de 5 segundos
  setTimeout(() => {
    if (notification.parentNode) {
      notification.parentNode.removeChild(notification);
    }
  }, 5000);
}