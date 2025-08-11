<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  http_response_code(401);
  echo json_encode(['error' => 'No autorizado']);
  exit();
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$estado = strtolower(trim($_POST['estado'] ?? ''));

if ($id <= 0 || !in_array($estado, ['facturada', 'pendiente'], true)) {
  http_response_code(400);
  echo json_encode(['error' => 'Datos inválidos']);
  cerrarConexion($conn);
  exit();
}

$stmt = odbc_prepare($conn, "UPDATE reservaciones SET estado = ? WHERE id = ?");
$ok = odbc_execute($stmt, [$estado, $id]);

if ($ok) {
  echo json_encode(['mensaje' => 'Estado actualizado']);
} else {
  http_response_code(500);
  echo json_encode(['error' => 'Error al actualizar: ' . odbc_errormsg($conn)]);
}

cerrarConexion($conn);
?>


