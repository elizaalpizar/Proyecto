<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  http_response_code(401);
  echo json_encode(['error' => 'No autorizado']);
  exit();
}

$sql = "SELECT codigo, nombre, costo FROM servicios ORDER BY nombre";
$stmt = odbc_exec($conn, $sql);

if (!$stmt) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al consultar: ' . odbc_errormsg()]);
  exit();
}

$rows = [];
while ($row = odbc_fetch_array($stmt)) {
  $rows[] = [
    'codigo' => $row['codigo'],
    'nombre' => $row['nombre'],
    'costo' => isset($row['costo']) ? $row['costo'] : null,
  ];
}

odbc_free_result($stmt);
cerrarConexion($conn);

echo json_encode($rows);
?>
