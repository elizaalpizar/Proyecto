# Página de Contacto - Energym

## Descripción
Esta página permite a los atletas realizar consultas y evaluar la experiencia del sitio web a través de un formulario de contacto completamente funcional.

## Características Implementadas

### ✅ Formulario de Contacto
- **Nombre completo**: Validación de solo letras, tildes y espacios
- **Email**: Validación de formato de correo electrónico
- **Evaluación de experiencia**: Sistema de calificación del 1 al 5
- **Mensaje**: Campo de texto con validación de longitud mínima

### ✅ Validación
- **Cliente (JavaScript)**: Validación en tiempo real antes del envío
- **Servidor (PHP)**: Validación y sanitización de datos
- **Mensajes de error**: Visualización clara de errores de validación

### ✅ Envío de Correo
- **Destinatario**: `eliza.alpizar2401@gmail.com`
- **Formato**: Texto plano con información estructurada
- **Headers**: Configuración profesional con Reply-To configurado

### ✅ Experiencia de Usuario
- **Indicador de carga**: Muestra estado durante el envío
- **Notificaciones**: Mensajes de éxito/error con auto-ocultado
- **Responsive**: Diseño adaptativo para todos los dispositivos

## Configuración del Servidor

### Requisitos
- PHP 7.0 o superior
- Función `mail()` habilitada en el servidor
- Servidor web (Apache, Nginx, etc.)

### Configuración de Correo
El formulario utiliza la función `mail()` nativa de PHP. Para que funcione correctamente:

1. **Servidor local**: Configurar un servidor SMTP local (XAMPP, WAMP, etc.)
2. **Servidor web**: Verificar que la función `mail()` esté habilitada
3. **Hosting**: La mayoría de proveedores de hosting tienen esta función habilitada

### Configuración Alternativa (SMTP)
Si prefieres usar SMTP en lugar de `mail()`, puedes modificar `Contactenos.php` para usar PHPMailer:

```php
// Instalar PHPMailer: composer require phpmailer/phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'tu-email@gmail.com';
$mail->Password = 'tu-contraseña-de-aplicación';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
```

## Estructura de Archivos

```
Público/Contactenos.html          # Página principal del formulario
Php/Contactenos.php              # Procesamiento del formulario
JS/ValidaciónContactenos.js      # Validación del lado cliente
Css/Contactenos.css              # Estilos y diseño
```

## Uso

1. **Acceso**: Navegar a `/Público/Contactenos.html`
2. **Llenado**: Completar todos los campos del formulario
3. **Validación**: El sistema valida en tiempo real
4. **Envío**: Al hacer clic en "Enviar mensaje"
5. **Confirmación**: Se muestra notificación de éxito/error

## Seguridad

- **Sanitización**: Todos los datos se limpian antes del procesamiento
- **Validación**: Verificación tanto en cliente como servidor
- **Headers**: Configuración segura para prevenir inyección
- **Logs**: Registro de errores para monitoreo

## Personalización

### Cambiar Correo Destino
Editar la variable `$to` en `Php/Contactenos.php`:
```php
$to = 'nuevo-email@dominio.com';
```

### Modificar Validaciones
Editar las funciones de validación en `JS/ValidaciónContactenos.js`

### Cambiar Estilos
Modificar `Css/Contactenos.css` para personalizar la apariencia

## Solución de Problemas

### El correo no se envía
1. Verificar que la función `mail()` esté habilitada
2. Revisar logs de error del servidor
3. Verificar configuración SMTP si se usa

### Validación no funciona
1. Verificar que el archivo JavaScript esté cargado
2. Revisar consola del navegador para errores
3. Confirmar que los IDs del HTML coincidan con JavaScript

### Estilos no se aplican
1. Verificar ruta del archivo CSS
2. Limpiar caché del navegador
3. Confirmar que el archivo CSS esté accesible

## Soporte
Para problemas técnicos, contactar al administrador del sistema o revisar los logs del servidor.

