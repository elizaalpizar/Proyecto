<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
  http_response_code(401);
  echo json_encode(['error' => 'No autenticado']);
  exit();
}

$atletaId = $_SESSION['atleta_id'];

$sql = "SELECT id, servicio, fecha, hora, duracion, comentarios, fecha_creacion, estado
        FROM reservaciones
        WHERE id_atleta = ?
        ORDER BY fecha DESC, hora DESC";

$stmt = odbc_prepare($conn, $sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(['error' => 'Error preparando consulta']);
  exit();
}

odbc_execute($stmt, [$atletaId]);

$hoy = new DateTime(date('Y-m-d'));
$res = [];
while ($row = odbc_fetch_array($stmt)) {
  $fecha = new DateTime($row['fecha']);
  $estadoBD = $row['estado'];
  $estadoCalculado = $estadoBD;

  if ($estadoBD === null || $estadoBD === '' || $estadoBD === 'activa') {
    $estadoCalculado = ($fecha < $hoy) ? 'realizada' : 'pendiente';
  }

  $dias = null;
  if ($fecha >= $hoy) {
    $diff = $hoy->diff($fecha);
    $dias = (int)$diff->format('%a');
  }

  $res[] = [
    'id' => $row['id'],
    'servicio' => $row['servicio'],
    'fecha' => $row['fecha'],
    'hora' => $row['hora'],
    'duracion' => $row['duracion'],
    'comentarios' => $row['comentarios'],
    'fecha_creacion' => $row['fecha_creacion'],
    'estado' => $estadoBD,
    'estadoCalculado' => $estadoCalculado,
    'diasRestantes' => $dias,
  ];
}

odbc_free_result($stmt);
cerrarConexion($conn);

echo json_encode($res);
?>
