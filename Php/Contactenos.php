<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false, 
        'message' => 'Método no permitido'
    ]);
    exit();
}

// Cargar autoload de Composer si existe (para PHPMailer u otras dependencias)
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}

// Carga manual de PHPMailer si no está cargado y existen los archivos locales (sin Composer)
if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    $phpmailerPath = __DIR__ . '/vendor/phpmailer/phpmailer/src';
    if (file_exists($phpmailerPath . '/PHPMailer.php') && file_exists($phpmailerPath . '/SMTP.php') && file_exists($phpmailerPath . '/Exception.php')) {
        require_once $phpmailerPath . '/Exception.php';
        require_once $phpmailerPath . '/PHPMailer.php';
        require_once $phpmailerPath . '/SMTP.php';
    }
}

// Cargar configuración de correo si existe
$mailConfigFile = __DIR__ . '/mail_config.php';
if (file_exists($mailConfigFile)) {
    require_once $mailConfigFile; // Define variables como $SMTP_ENABLED, $SMTP_HOST, etc.
}

$required_fields = ['name', 'email', 'rating', 'message'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Los siguientes campos son obligatorios: ' . implode(', ', $missing_fields)
    ]);
    exit();
}

$nombre = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$rating = filter_var(trim($_POST['rating']), FILTER_SANITIZE_STRING);
$mensaje = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

if (strlen($nombre) < 2 || strlen($nombre) > 100) {
    echo json_encode([
        'success' => false, 
        'message' => 'El nombre debe tener entre 2 y 100 caracteres'
    ]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false, 
        'message' => 'El formato del email no es válido'
    ]);
    exit();
}

if (strlen($mensaje) < 10 || strlen($mensaje) > 1000) {
    echo json_encode([
        'success' => false, 
        'message' => 'El mensaje debe tener entre 10 y 1000 caracteres'
    ]);
    exit();
}

$fromEmail = isset($MAIL_FROM) && filter_var($MAIL_FROM, FILTER_VALIDATE_EMAIL) ? $MAIL_FROM : 'noreply@energym.com';
$fromName = isset($MAIL_FROM_NAME) ? $MAIL_FROM_NAME : 'Energym';

// Modo: enviar también al usuario además de al administrador
$sendToUser = isset($SEND_TO_USER) ? (bool)$SEND_TO_USER : false;
$adminEmail = isset($MAIL_TO) && filter_var($MAIL_TO, FILTER_VALIDATE_EMAIL) ? $MAIL_TO : 'info@energym.com';

// Contenido para el administrador
$adminSubject = 'Nueva consulta desde el formulario de contacto - Energym';
$adminBody = "Se ha recibido una nueva consulta desde el formulario de contacto:\n\n" .
             "Nombre: {$nombre}\n" .
             "Email: {$email}\n" .
             "Evaluación: {$rating}\n" .
             "Mensaje:\n{$mensaje}\n\n" .
             "Fecha: " . date('Y-m-d H:i:s') . "\n" .
             "IP: " . $_SERVER['REMOTE_ADDR'] . "\n" .
             "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";

// Contenido para el usuario
$userSubject = 'Confirmación: Hemos recibido tu mensaje - Energym';
$userBody = "Hola {$nombre},\n\n" .
            "Gracias por contactarnos. Hemos recibido tu mensaje y te responderemos a la brevedad.\n\n" .
            "Resumen de tu envío:\n" .
            "- Nombre: {$nombre}\n" .
            "- Email: {$email}\n" .
            "- Evaluación: {$rating}\n" .
            "- Mensaje:\n{$mensaje}\n\n" .
            "Fecha: " . date('Y-m-d H:i:s') . "\n" .
            "Energym";

// Headers para respaldo con mail()
$adminHeaders = "From: {$fromName} <{$fromEmail}>\r\n" .
                "Reply-To: {$nombre} <{$email}>\r\n" .
                "MIME-Version: 1.0\r\n" .
                "Content-Type: text/plain; charset=UTF-8\r\n" .
                "X-Mailer: PHP/" . phpversion() . "\r\n" .
                "X-Requested-With: XMLHttpRequest\r\n";

$userHeaders = "From: {$fromName} <{$fromEmail}>\r\n" .
               "Reply-To: {$fromName} <{$adminEmail}>\r\n" .
               "MIME-Version: 1.0\r\n" .
               "Content-Type: text/plain; charset=UTF-8\r\n" .
               "X-Mailer: PHP/" . phpversion() . "\r\n" .
               "X-Requested-With: XMLHttpRequest\r\n";

// Enviar correo usando PHPMailer (SMTP) si está disponible y habilitado; de lo contrario usar mail()
$mailSent = false;
$errorMessage = '';

try {
    $smtpEnabled = isset($SMTP_ENABLED) ? (bool)$SMTP_ENABLED : false;
    if ($smtpEnabled && class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        // Primero: correo al administrador
        $mailerAdmin = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mailerAdmin->isSMTP();
        $mailerAdmin->Host = isset($SMTP_HOST) ? $SMTP_HOST : '';
        $mailerAdmin->SMTPAuth = true;
        $mailerAdmin->Username = isset($SMTP_USERNAME) ? $SMTP_USERNAME : '';
        $mailerAdmin->Password = isset($SMTP_PASSWORD) ? $SMTP_PASSWORD : '';
        $mailerAdmin->SMTPSecure = isset($SMTP_SECURE) ? $SMTP_SECURE : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mailerAdmin->Port = isset($SMTP_PORT) ? (int)$SMTP_PORT : 587;
        $mailerAdmin->CharSet = 'UTF-8';

        $mailerAdmin->setFrom($fromEmail, $fromName);
        $mailerAdmin->addAddress($adminEmail);
        $mailerAdmin->addReplyTo($email, $nombre);
        $mailerAdmin->isHTML(false);
        $mailerAdmin->Subject = $adminSubject;
        $mailerAdmin->Body = $adminBody;
        $mailSent = $mailerAdmin->send();

        // Segundo: correo de confirmación al usuario (opcional)
        if ($mailSent && $sendToUser) {
            $mailerUser = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mailerUser->isSMTP();
            $mailerUser->Host = isset($SMTP_HOST) ? $SMTP_HOST : '';
            $mailerUser->SMTPAuth = true;
            $mailerUser->Username = isset($SMTP_USERNAME) ? $SMTP_USERNAME : '';
            $mailerUser->Password = isset($SMTP_PASSWORD) ? $SMTP_PASSWORD : '';
            $mailerUser->SMTPSecure = isset($SMTP_SECURE) ? $SMTP_SECURE : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mailerUser->Port = isset($SMTP_PORT) ? (int)$SMTP_PORT : 587;
            $mailerUser->CharSet = 'UTF-8';

            $mailerUser->setFrom($fromEmail, $fromName);
            $mailerUser->addAddress($email, $nombre);
            $mailerUser->addReplyTo($adminEmail, $fromName);
            $mailerUser->isHTML(false);
            $mailerUser->Subject = $userSubject;
            $mailerUser->Body = $userBody;
            $mailerUser->send();
        }
    } else {
        // Respaldo con mail(): enviar a admin y, si aplica, al usuario
        $mailSent = mail($adminEmail, $adminSubject, $adminBody, $adminHeaders);
        if (!$mailSent) {
            throw new Exception('La función mail() falló al enviar al administrador');
        }
        if ($sendToUser) {
            @mail($email, $userSubject, $userBody, $userHeaders);
        }
    }

    if ($mailSent) {
        error_log("Formulario de contacto enviado exitosamente desde: " . $email);
        echo json_encode([
            'success' => true,
            'message' => 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.'
        ]);
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    error_log("Error al enviar formulario de contacto: " . $errorMessage . " - Email: " . $email);
    echo json_encode([
        'success' => false,
        'message' => 'Error al enviar el mensaje. Por favor, inténtalo de nuevo más tarde o contacta directamente a info@energym.com'
    ]);
}
?>