<?php
include 'db.php';

$id = $_POST['identificacion'];
$usuario = $_POST['usuario'];
$password = $_POST['password'];
$nombre = $_POST['nombre'];
$apellido1 = $_POST['apellido1'];
$apellido2 = $_POST['apellido2'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];


$sql_check = "SELECT * FROM atletas WHERE id = '$id' OR usuario = '$usuario'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
  echo "<script>alert('Ya existe un atleta con esa identificación o usuario.'); window.history.back();</script>";
  exit();
}


$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql_insert = "INSERT INTO atletas (id, usuario, contraseña, nombre, apellido1, apellido2, email, telefono)
               VALUES ('$id', '$usuario', '$hashed_password', '$nombre', '$apellido1', '$apellido2', '$correo', '$telefono')";

if ($conn->query($sql_insert) === TRUE) {
  echo "<script>alert('Registro exitoso.'); window.location.href='InicioSesion.html';</script>";
} else {
  echo "Error al registrar: " . $conn->error;
}
?>
