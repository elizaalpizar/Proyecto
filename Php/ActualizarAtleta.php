<?php
require_once 'Session.php';
requireAtleta();

$id        = $_POST['identificacion'] ?? '';
$usuario   = trim($_POST['usuario'] ?? '');
$password  = $_POST['password'] ?? '';
$nombre    = trim($_POST['nombre'] ?? '');
$apellido1 = trim($_POST['apellido1'] ?? '');
$apellido2 = trim($_POST['apellido2'] ?? '');
$correo    = trim($_POST['correo'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');

$errors = [];
if (strlen($usuario) < 4)      $errors[] = "El usuario debe tener al menos 4 caracteres.";
if ($password && strlen($password) < 6) $errors[] = "La nueva contraseña debe tener al menos 6 caracteres.";
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errors[] = "Correo electrónico inválido.";
if (!preg_match('/^[0-9]{8}$/', $telefono))       $errors[] = "Teléfono inválido.";

if ($errors) {
    echo "<div style='color:red;'><ul><li>".implode('</li><li>', $errors)."</li></ul></div>";
    exit;
}

$conn = odbc_connect("Driver={ODBC Driver 17 for SQL Server};Server=server.asralabs.com,14330;Database=Proyecto_Progra3;UID=sa;PWD=19861997.Sr;CharacterSet=UTF8;", '', '') 
    or die('Error de conexión');

$params   = [$usuario, $nombre, $apellido1, $apellido2, $correo, $telefono];
$fieldSQL = "usuario = ?, nombre = ?, apellido1 = ?, apellido2 = ?, correo = ?, telefono = ?";

if ($password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $fieldSQL .= ", contrasena = ?";
    $params[] = $hash;
}

$params[] = $id;
$sql = "UPDATE atletas SET $fieldSQL WHERE identificacion = ?";

$stmt = odbc_prepare($conn, $sql);
$ok   = odbc_execute($stmt, $params);

if ($ok) {
    echo "<p style='color:green;'>¡Datos actualizados con éxito!</p>";
    echo "<p><a href='../Html/InfoAtleta.php'>Volver a mi perfil</a></p>";
} else {
    echo "<p style='color:red;'>Error al actualizar: ".odbc_errormsg($conn)."</p>";
}

odbc_close($conn);
?>