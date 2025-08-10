<?php
$host     = 'localhost';
$username = 'Eliza';
$password = '';
$dbname   = 'contactos_db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

$stmt = $conn->prepare(
  "INSERT INTO consultas (nombre, email, mensaje, rating)
   VALUES (?, ?, ?, ?)"
);
$stmt->bind_param('sssi', $nombre, $email, $mensaje, $rating);
$stmt->execute();

// cerrar
$stmt->close();
$conn->close();
?>
