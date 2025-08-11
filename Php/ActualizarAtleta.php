<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$passwordDB = "19861997.Sr";
$cs = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;UID=$username;PWD=$passwordDB;CharacterSet=UTF8;";

$id_old      = trim($_POST['identificacion_old']  ?? '');
$id_new      = trim($_POST['identificacion_new']  ?? '');
$usuario     = trim($_POST['usuario']             ?? '');
$passwordRaw = $_POST['password']                 ?? '';
$nombre      = trim($_POST['nombre']              ?? '');
$apellido1   = trim($_POST['apellido1']           ?? '');
$apellido2   = trim($_POST['apellido2']           ?? '');
$correo      = trim($_POST['correo']              ?? '');
$telefono    = trim($_POST['telefono']            ?? '');

$errors = [];
if (!preg_match('/^[0-9]{9,12}$/', $id_new)) {
    $errors[] = "Identificación inválida.";
}
if (strlen($usuario) < 4) {
    $errors[] = "El usuario debe tener al menos 4 caracteres.";
}
if ($passwordRaw !== '' && strlen($passwordRaw) < 6) {
    $errors[] = "La nueva contraseña debe tener al menos 6 caracteres.";
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Correo electrónico inválido.";
}
if (!preg_match('/^[0-9]{8}$/', $telefono)) {
    $errors[] = "Teléfono inválido.";
}

if ($errors) {
    echo "<ul style='color:red;'><li>" . implode('</li><li>', $errors) . "</li></ul>";
    exit;
}

if ($passwordRaw !== '') {
    $pwdHash   = password_hash($passwordRaw, PASSWORD_DEFAULT);
    $pwdClause = ", contrasena = ?";
} else {
    $pwdClause = "";
}

$conn = odbc_connect($cs, '', '');
if (!$conn) {
    die("Error de conexión: " . odbc_errormsg());
}

$sql = "UPDATE atletas SET
          identificacion = ?,
          usuario        = ?,
          nombre         = ?,
          apellido1      = ?,
          apellido2      = ?,
          correo         = ?,
          telefono       = ?
        $pwdClause
        WHERE identificacion = ?";

$stmt = odbc_prepare($conn, $sql);
if (!$stmt) {
    die("Error preparando consulta: " . odbc_errormsg($conn));
}

$params = [
  $id_new,
  $usuario,
  $nombre,
  $apellido1,
  $apellido2,
  $correo,
  $telefono
];

if ($passwordRaw !== '') {
  $params[] = $pwdHash;
}

$params[] = $id_old;

$ok = odbc_execute($stmt, $params);
if ($ok) {
    echo "<p style='color:green;'>¡Datos actualizados correctamente!</p>";
    echo "<p><a href='../Admin/CatalogoAtleta.php'>Volver al catálogo</a></p>";
} else {
    echo "<p style='color:red;'>Error al actualizar: " . odbc_errormsg($conn) . "</p>";
}

odbc_close($conn);
?>