<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$destinatario = 'eliza.alpizar2401@gmail.com';
$asunto       = 'Nueva consulta desde Contacto – Sitio Atletas';

$nombre  = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$mensaje = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
$rating  = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

if (!$name || !$email || !$rating || strlen($message) < 20) {
  header('Location: ../Público/404.html');
  exit;
}

$body = "Nombre: $name\nCorreo: $email\nCalificación: $rating\n\n$message";
$hdrs = "From: tu@admin.com\r\nReply-To: $email\r\n";

if (mail($dest, 'Consulta Atleta', $body, $hdrs)) {
  header('Location: ../Público/PaginaPrincipal.html');
} else {
  header('Location: ../Público/404.html');
}
var_dump($_POST);
exit;
?>