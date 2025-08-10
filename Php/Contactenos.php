<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // o ajusta si usas PHPMailer manual

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'eliza.alpizar2401@gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'eliza.alpizar2401@gmail.com'; // tu cuenta Gmail
    $mail->Password   = 'Bianka04072015*';   // usa contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('tu_correo@gmail.com', 'Formulario Atletas');
    $mail->addAddress('eliza.alpizar2401@gmail.com'); 

    // Contenido del correo
    $mail->Subject = 'Nueva consulta desde el formulario';
    $mail->Body    = "Nombre: {$_POST['name']}\nEmail: {$_POST['email']}\nMensaje:\n{$_POST['message']}\nValoración: {$_POST['rating']}";

    $mail->send();
    echo 'Consulta enviada correctamente.';
} catch (Exception $e) {
    echo "Error al enviar: {$mail->ErrorInfo}";
}
?>