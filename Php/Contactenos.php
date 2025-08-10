<?php
$destinatario = 'eliza.alpizar2401@gmail.com';
$asunto       = 'Nueva consulta desde Contacto – Sitio Atletas';

$nombre  = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$mensaje = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
$rating  = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

if (!$nombre || !$email || !$mensaje || !$rating) {
  header('Location: ../Público/404.html');
  exit;
}

$body  = "Nombre: $nombre\n";
$body .= "Correo: $email\n";
$body .= "Calificación: $rating\n\n";
$body .= "Mensaje:\n$mensaje\n";

$headers  = "From: eliza.alpizar2401@gmail.com\r\n";
$headers .= "Reply-To: $email\r\n";

if (mail($destinatario, $asunto, $body, $headers)) {
  header('Location: ../Público/PaginaPrincipal.html');
} else {
  header('Location: ../Público/404.html');
}
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $to = "eliza.alpizar2401@gmail.com"; 
  $subject = "Consulta de atleta: " . $_POST["subject"];
  $message = "Nombre: " . $_POST["name"] . "\n";
  $message .= "Correo: " . $_POST["email"] . "\n\n";
  $message .= "Mensaje:\n" . $_POST["message"];

  $headers = "From: " . $_POST["email"];

  if (mail($to, $subject, $message, $headers)) {
    echo "Consulta enviada correctamente.";
  } else {
    echo "Error al enviar la consulta.";
  }
}
?>