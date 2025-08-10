<?php
$destinatario = 'eliza.alpizar2401@gmail.com';

$nombre  = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$mensaje = trim($_POST['message']);
$rating  = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

if (!$nombre || !$email || !$mensaje || !$rating) {
    header('Location: ../Público/404.html');
    exit;
}

$asunto = 'Consulta desde Contáctenos';
$body   = "Nombre: {$nombre}\n"
        . "Email: {$email}\n"
        . "Valoración: {$rating}\n\n"
        . "Mensaje:\n{$mensaje}\n";

$headers  = "From: no-reply@tu-dominio.com\r\n";
$headers .= "Reply-To: {$email}\r\n";

// 6. Envío
if (mail($destinatario, $asunto, $body, $headers)) {
    header('Location: ../Público/PaginaPrincipal.html');
} else {
    header('Location: ../Público/404.html');
}
