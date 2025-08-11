<?php
session_start();
require_once 'conexion_json.php';

header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  http_response_code(401);
  echo json_encode(['error' => 'No autorizado']);
  exit();
}

$sql = "SELECT r.id,
               r.id_atleta,
               r.servicio,
               r.fecha,
               r.hora,
               r.estado,
               COALESCE(a.nombre,'') + ' ' + COALESCE(a.apellido1,'') + ' ' + COALESCE(a.apellido2,'') AS atleta_nombre
        FROM reservaciones r
        LEFT JOIN atletas a ON a.identificacion = r.id_atleta
        ORDER BY r.fecha DESC, r.hora DESC";

$stmt = odbc_exec($conn, $sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al listar', 'detalle' => @odbc_errormsg($conn)]);
  cerrarConexion($conn);
  exit();
}

$rows = [];
$hoy = strtotime(date('Y-m-d'));
while ($row = odbc_fetch_array($stmt)) {
  $r = array_change_key_case($row, CASE_LOWER);
  $estado = isset($r['estado']) ? strtolower(trim($r['estado'])) : '';
  if ($estado === '' || $estado === null) {
    $f = isset($r['fecha']) ? strtotime($r['fecha']) : false;
    if ($f && $f < $hoy) { $estado = 'facturada'; } else { $estado = 'pendiente'; }
  }
  $r['estado'] = $estado;
  $rows[] = $r;
}

cerrarConexion($conn);
echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR);
?>


