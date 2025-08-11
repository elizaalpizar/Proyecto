<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  http_response_code(401);
  echo 'No autorizado';
  exit();
}

$codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$costo = isset($_POST['costo']) && $_POST['costo'] !== '' ? (float)str_replace(',', '.', $_POST['costo']) : null;

if ($codigo === '' || $nombre === '') {
  http_response_code(400);
  echo 'Código y nombre son obligatorios';
  exit();
}

// Si ya existe, actualizar; si no, insertar
$existsStmt = odbc_prepare($conn, 'SELECT 1 FROM servicios WHERE codigo = ?');
odbc_execute($existsStmt, [$codigo]);
$exists = odbc_fetch_row($existsStmt);
odbc_free_result($existsStmt);

if ($exists) {
  $stmt = odbc_prepare($conn, 'UPDATE servicios SET nombre = ?, costo = ? WHERE codigo = ?');
  $ok = odbc_execute($stmt, [$nombre, $costo, $codigo]);
} else {
  $stmt = odbc_prepare($conn, 'INSERT INTO servicios (codigo, nombre, costo) VALUES (?, ?, ?)');
  $ok = odbc_execute($stmt, [$codigo, $nombre, $costo]);
}

if ($ok) {
  echo 'OK';
} else {
  http_response_code(500);
  echo 'Error: ' . odbc_errormsg();
}

odbc_free_result($stmt);
cerrarConexion($conn);
?>
