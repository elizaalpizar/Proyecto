<?php
session_start();
require_once 'conexion_json.php';
ob_start();

header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  http_response_code(401);
  $payload = json_encode(['error' => 'No autorizado']);
  ob_clean();
  echo $payload;
  exit();
}

$codigo = limpiarDatos($_POST['codigo'] ?? '');
if ($codigo === '') {
  http_response_code(400);
  $payload = json_encode(['error' => 'Código requerido']);
  ob_clean();
  echo $payload;
  cerrarConexion($conn);
  exit();
}

$stmt = odbc_prepare($conn, "DELETE FROM servicios WHERE codigo = ?");
$ok = odbc_execute($stmt, [$codigo]);

if ($ok && odbc_num_rows($stmt) >= 0) {
  $payload = json_encode(['mensaje' => 'Servicio eliminado']);
  ob_clean();
  echo $payload;
} else {
  http_response_code(500);
  $payload = json_encode(['error' => 'No se pudo eliminar: ' . odbc_errormsg($conn)]);
  ob_clean();
  echo $payload;
}

cerrarConexion($conn);
?>


