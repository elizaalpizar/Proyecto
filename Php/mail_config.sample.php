<?php
// Copie este archivo como mail_config.php y complete los valores según su servidor SMTP.

// Habilitar envío por SMTP con PHPMailer. Requiere instalar dependencias:
//   composer require phpmailer/phpmailer
$SMTP_ENABLED = false; // true para habilitar SMTP

// Credenciales SMTP
$SMTP_HOST = 'smtp.gmail.com';
$SMTP_PORT = 587; // 465 para SSL, 587 para STARTTLS
$SMTP_SECURE = 'tls'; // 'ssl' o 'tls'
$SMTP_USERNAME = 'tu-correo@dominio.com';
$SMTP_PASSWORD = 'tu-contraseña-o-token'; // En Gmail, use contraseña de aplicación

// Remitente por defecto
$MAIL_FROM = 'noreply@energym.com';
$MAIL_FROM_NAME = 'Energym';

// Destinatario (donde llega el mensaje del formulario)
$MAIL_TO = 'destino@dominio.com';
// Fin de configuración

// Si deseas que el correo lo reciba el usuario que completó el formulario (confirmación),
// activa esta opción. Si es false, el correo llega al administrador ($MAIL_TO).
$SEND_TO_USER = false; // true = enviar al usuario, false = enviar al administrador


