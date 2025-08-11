<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  http_response_code(401);
  echo 'No autorizado';
  exit();
}

$codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
if ($codigo === '') {
  http_response_code(400);
  echo 'Código requerido';
  exit();
}

$stmt = odbc_prepare($conn, 'DELETE FROM servicios WHERE codigo = ?');
$ok = odbc_execute($stmt, [$codigo]);

if ($ok) {
  echo 'OK';
} else {
  http_response_code(500);
  echo 'Error: ' . odbc_errormsg();
}

odbc_free_result($stmt);
cerrarConexion($conn);
?>
