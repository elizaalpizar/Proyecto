<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false, 
        'message' => 'Método no permitido'
    ]);
    exit();
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

$to = 'eliza.alpizar2401@gmail.com'; // Correo de destino
$subject = 'Nueva consulta desde el formulario de contacto - Energym';

$message_body = "Se ha recibido una nueva consulta desde el formulario de contacto:\n\n";
$message_body .= "Nombre: " . $nombre . "\n";
$message_body .= "Email: " . $email . "\n";
$message_body .= "Evaluación: " . $rating . "\n";
$message_body .= "Mensaje:\n" . $mensaje . "\n\n";
$message_body .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
$message_body .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
$message_body .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";

$headers = "From: noreply@energym.com\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "X-Requested-With: XMLHttpRequest\r\n";

try {
    if (mail($to, $subject, $message_body, $headers)) {
        error_log("Formulario de contacto enviado exitosamente desde: " . $email);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.'
        ]);
    } else {
        throw new Exception('La función mail() falló');
    }
} catch (Exception $e) {
    error_log("Error al enviar formulario de contacto: " . $e->getMessage() . " - Email: " . $email);
    
    echo json_encode([
        'success' => false, 
        'message' => 'Error al enviar el mensaje. Por favor, inténtalo de nuevo más tarde o contacta directamente a info@energym.com'
    ]);
}
?>