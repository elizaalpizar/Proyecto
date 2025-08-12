document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formRegistro');
  const mensajeError = document.getElementById('mensajeError');

  // Obtener todos los inputs
  const inputs = {
    identificacion: document.getElementById('identificacion'),
    usuario: document.getElementById('usuario'),
    password: document.getElementById('password'),
    nombre: document.getElementById('nombre'),
    apellido1: document.getElementById('apellido1'),
    apellido2: document.getElementById('apellido2'),
    correo: document.getElementById('correo'),
    telefono: document.getElementById('telefono')
  };

  // Función para mostrar preview en tiempo real
  function mostrarPreview() {
    const nombreCompleto = `${inputs.nombre.value} ${inputs.apellido1.value} ${inputs.apellido2.value}`.trim();
    const datosPreview = {
      'Identificación': inputs.identificacion.value || 'No ingresado',
      'Usuario': inputs.usuario.value || 'No ingresado',
      'Nombre Completo': nombreCompleto || 'No ingresado',
      'Correo': inputs.correo.value || 'No ingresado',
      'Teléfono': inputs.telefono.value || 'No ingresado'
    };

    // Crear o actualizar el preview
    let previewDiv = document.getElementById('datosPreview');
    if (!previewDiv) {
      previewDiv = document.createElement('div');
      previewDiv.id = 'datosPreview';
      previewDiv.className = 'datos-preview';
      previewDiv.innerHTML = '<h3>Vista Previa de Datos:</h3>';
      form.parentNode.insertBefore(previewDiv, form.nextSibling);
    }

    // Actualizar contenido del preview
    let previewHTML = '<h3>Vista Previa de Datos:</h3><div class="preview-grid">';
    Object.entries(datosPreview).forEach(([label, valor]) => {
      const valorMostrado = valor === 'No ingresado' ? `<span class="no-dato">${valor}</span>` : valor;
      previewHTML += `
        <div class="preview-item">
          <strong>${label}:</strong> ${valorMostrado}
        </div>
      `;
    });
    previewHTML += '</div>';
    previewDiv.innerHTML = previewHTML;
  }

  // Función para validar en tiempo real
  function validarEnTiempoReal(input, tipo) {
    const valor = input.value.trim();
    let esValido = true;
    let mensaje = '';

    // Remover clases de validación anteriores
    input.classList.remove('valido', 'invalido');

    switch (tipo) {
      case 'identificacion':
        if (valor && !/^\d{9,12}$/.test(valor)) {
          esValido = false;
          mensaje = 'Debe tener entre 9 y 12 dígitos numéricos';
        }
        break;
      case 'usuario':
        if (valor && (valor.length < 4 || /\s/.test(valor))) {
          esValido = false;
          mensaje = 'Mínimo 4 caracteres, sin espacios';
        }
        break;
      case 'password':
        if (valor && (valor.length < 6 || !/[A-Za-z]/.test(valor) || !/\d/.test(valor))) {
          esValido = false;
          mensaje = 'Mínimo 6 caracteres, con letras y números';
        }
        break;
      case 'nombre':
      case 'apellido1':
      case 'apellido2':
        if (valor && !/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(valor)) {
          esValido = false;
          mensaje = 'Solo letras permitidas';
        }
        break;
      case 'correo':
        if (valor && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor)) {
          esValido = false;
          mensaje = 'Formato de correo inválido';
        }
        break;
      case 'telefono':
        if (valor && !/^\d{8}$/.test(valor)) {
          esValido = false;
          mensaje = 'Debe tener exactamente 8 dígitos';
        }
        break;
    }

    // Aplicar clases de validación
    if (valor) {
      input.classList.add(esValido ? 'valido' : 'invalido');
    }

    // Mostrar/ocultar mensaje de error
    let errorSpan = input.parentNode.querySelector('.error-mensaje');
    if (!errorSpan) {
      errorSpan = document.createElement('span');
      errorSpan.className = 'error-mensaje';
      input.parentNode.appendChild(errorSpan);
    }

    if (!esValido && valor) {
      errorSpan.textContent = mensaje;
      errorSpan.style.display = 'block';
    } else {
      errorSpan.style.display = 'none';
    }

    // Actualizar preview
    mostrarPreview();
  }

  // Agregar event listeners para validación en tiempo real
  Object.entries(inputs).forEach(([tipo, input]) => {
    input.addEventListener('input', () => validarEnTiempoReal(input, tipo));
    input.addEventListener('blur', () => validarEnTiempoReal(input, tipo));
  });

  // Validación del formulario al enviar
  form.addEventListener('submit', function (e) {
    mensajeError.textContent = '';
    let errores = [];

    const id = inputs.identificacion.value.trim();
    const usuario = inputs.usuario.value.trim();
    const password = inputs.password.value.trim();
    const nombre = inputs.nombre.value.trim();
    const apellido1 = inputs.apellido1.value.trim();
    const apellido2 = inputs.apellido2.value.trim();
    const correo = inputs.correo.value.trim();
    const telefono = inputs.telefono.value.trim();

    if (!/^\d{9,12}$/.test(id)) {
      errores.push('La identificación debe tener entre 9 y 12 dígitos numéricos.');
    }

    if (usuario.length < 4 || /\s/.test(usuario)) {
      errores.push('El usuario debe tener al menos 4 caracteres y no contener espacios.');
    }

    if (password.length < 6 || !/[A-Za-z]/.test(password) || !/\d/.test(password)) {
      errores.push('La contraseña debe tener al menos 6 caracteres, incluyendo letras y números.');
    }

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

    const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!correoRegex.test(correo)) {
      errores.push('El correo electrónico no tiene un formato válido.');
    }

    if (!/^\d{8}$/.test(telefono)) {
      errores.push('El teléfono debe tener exactamente 8 dígitos numéricos.');
    }

    if (errores.length > 0) {
      e.preventDefault();
      mensajeError.innerHTML = errores.map(err => `<p>${err}</p>`).join('');
    }
  });

  // Mostrar preview inicial
  mostrarPreview();
});
