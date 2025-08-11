<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../Público/RegistroAtleta.html');
  exit();
}

function resp($msg, $tipo = 'error') {
  header('Location: ../Público/RegistroAtleta.html?mensaje=' . urlencode($msg) . '&tipo=' . urlencode($tipo));
  exit();
}

$identificacion = limpiarDatos($_POST['identificacion'] ?? '');
$usuario        = limpiarDatos($_POST['usuario'] ?? '');
$password       = $_POST['password'] ?? '';
$nombre         = limpiarDatos($_POST['nombre'] ?? '');
$apellido1      = limpiarDatos($_POST['apellido1'] ?? '');
$apellido2      = limpiarDatos($_POST['apellido2'] ?? '');
$correo         = limpiarDatos($_POST['correo'] ?? '');
$telefono       = limpiarDatos($_POST['telefono'] ?? '');

// Validaciones mínimas del lado servidor
$errores = [];
if (!preg_match('/^\d{9,12}$/', $identificacion)) $errores[] = 'Identificación inválida';
if (strlen($usuario) < 4) $errores[] = 'Usuario muy corto';
if (strlen($password) < 6) $errores[] = 'Contraseña muy corta';
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = 'Correo inválido';
if (!preg_match('/^\d{8}$/', $telefono)) $errores[] = 'Teléfono inválido';
if ($nombre === '' || $apellido1 === '' || $apellido2 === '') $errores[] = 'Complete nombre y apellidos';

if ($errores) {
  resp(implode('. ', $errores), 'error');
}

// Verificar duplicados (identificación y usuario)
$chk = odbc_prepare($conn, 'SELECT identificacion, usuario FROM atletas WHERE identificacion = ? OR usuario = ?');
odbc_execute($chk, [$identificacion, $usuario]);
$dupId = false; $dupUser = false;
while ($row = odbc_fetch_array($chk)) {
  if (isset($row['identificacion']) && $row['identificacion'] == $identificacion) $dupId = true;
  if (isset($row['usuario']) && strtolower($row['usuario']) === strtolower($usuario)) $dupUser = true;
}
odbc_free_result($chk);

if ($dupId && $dupUser) resp('Ya existe un atleta con esa identificación y usuario.');
if ($dupId) resp('Ya existe un atleta con esa identificación.');
if ($dupUser) resp('El nombre de usuario ya está en uso.');

// Insertar
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = 'INSERT INTO atletas (identificacion, usuario, contrasena, nombre, apellido1, apellido2, correo, telefono) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
$stmt = odbc_prepare($conn, $sql);
$ok = odbc_execute($stmt, [$identificacion, $usuario, $hash, $nombre, $apellido1, $apellido2, $correo, $telefono]);

if ($ok) {
  cerrarConexion($conn);
  header('Location: ../Público/InicioSesion.html?mensaje=' . urlencode('Registro exitoso. Inicia sesión para continuar.') . '&tipo=success');
  exit();
}

$err = odbc_errormsg($conn);
cerrarConexion($conn);
resp('Error al registrar: ' . $err, 'error');
?>
