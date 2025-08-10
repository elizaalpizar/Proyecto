<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'eliza.alpizar2401@gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'eliza.alpizar2401@gmail.com';
    $mail->Password   = '';   
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