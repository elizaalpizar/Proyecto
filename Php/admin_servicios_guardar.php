<?php
session_start();
require_once 'conexion_json.php';
ob_start();

header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  http_response_code(401);
  echo json_encode(['error' => 'No autorizado']);
  exit();
}

$codigo     = limpiarDatos($_POST['codigo'] ?? '');
$nombre     = limpiarDatos($_POST['nombre'] ?? '');
$tipo       = limpiarDatos($_POST['tipo'] ?? '');
$instructor = limpiarDatos($_POST['instructor'] ?? '');
$costoRaw   = trim($_POST['costo'] ?? '');

if ($codigo === '' || $nombre === '') {
  http_response_code(400);
  $payload = json_encode(['error' => 'Código y nombre son obligatorios']);
  ob_clean();
  echo $payload;
  cerrarConexion($conn);
  exit();
}

$costo = null;
if ($costoRaw !== '') {
  $costo = str_replace(',', '.', $costoRaw);
  if (!is_numeric($costo)) {
    http_response_code(400);
    $payload = json_encode(['error' => 'Costo inválido']);
    ob_clean();
    echo $payload;
    cerrarConexion($conn);
    exit();
  }
}

function getColumnsInfo($conn) {
  $cols = [];
  $sql = "SELECT COLUMN_NAME, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'servicios'";
  $st = odbc_exec($conn, $sql);
  while ($st && ($row = odbc_fetch_array($st))) {
    $name = strtolower($row['COLUMN_NAME']);
    $cols[$name] = [
      'nullable' => (strtoupper($row['IS_NULLABLE']) === 'YES')
    ];
  }
  return $cols;
}

$info = getColumnsInfo($conn);

function hasCol($info, $name) { return array_key_exists(strtolower($name), $info); }

$colCodigo = null; foreach (['codigo','id','codigo_servicio','cod_servicio'] as $c) { if (hasCol($info,$c)) { $colCodigo = $c; break; } }
$colNombre = null; foreach (['nombre','nombre_zona','zona','nombre_servicio'] as $c) { if (hasCol($info,$c)) { $colNombre = $c; break; } }

$tipoColumns = array_values(array_filter(['tipo','tipo_zona','tipo_servicio'], function($c) use ($info){ return hasCol($info,$c); }));

$colInstructor = null; foreach (['instructor','instructor_a_cargo','instructor_cargo'] as $c) { if (hasCol($info,$c)) { $colInstructor = $c; break; } }
$colCosto      = null; foreach (['costo','precio','monto'] as $c) { if (hasCol($info,$c)) { $colCosto = $c; break; } }

if ($colCodigo === null || $colNombre === null) {
  http_response_code(500);
  $payload = json_encode(['error' => 'Esquema inesperado en tabla servicios (faltan columnas clave)']);
  ob_clean();
  echo $payload;
  cerrarConexion($conn);
  exit();
}

$setParts = [];
$paramsUpd = [];
if ($colNombre)     { $setParts[] = "$colNombre = ?";     $paramsUpd[] = $nombre; }
if (!empty($tipoColumns)) {
  foreach ($tipoColumns as $tc) { $setParts[] = "$tc = ?"; $paramsUpd[] = $tipo; }
}
if ($colInstructor) { $setParts[] = "$colInstructor = ?"; $paramsUpd[] = $instructor; }
if ($colCosto)      { $setParts[] = "$colCosto = ?";      $paramsUpd[] = $costo; }

$sqlUpdate = "UPDATE servicios SET " . implode(', ', $setParts) . " WHERE $colCodigo = ?";
$paramsUpd[] = $codigo;

$update = odbc_prepare($conn, $sqlUpdate);
$okUpd  = $update ? @odbc_execute($update, $paramsUpd) : false;

if ($okUpd && odbc_num_rows($update) > 0) {
  $payload = json_encode(['mensaje' => 'Servicio actualizado']);
  ob_clean();
  echo $payload;
  cerrarConexion($conn);
  exit();
}

$cols = [$colCodigo, $colNombre];
$vals = [$codigo, $nombre];
if (!empty($tipoColumns)) {
  foreach ($tipoColumns as $tc) { $cols[] = $tc; $vals[] = $tipo; }
}
if ($colInstructor) { $cols[] = $colInstructor; $vals[] = $instructor; }
if ($colCosto)      { $cols[] = $colCosto;      $vals[] = $costo; }

$placeholders = implode(', ', array_fill(0, count($cols), '?'));
$sqlInsert = "INSERT INTO servicios (" . implode(', ', $cols) . ") VALUES ($placeholders)";
$insert = odbc_prepare($conn, $sqlInsert);
$okIns  = $insert ? @odbc_execute($insert, $vals) : false;

if ($okIns) {
  $payload = json_encode(['mensaje' => 'Servicio creado']);
  ob_clean();
  echo $payload;
} else {
  http_response_code(500);
  $payload = json_encode(['error' => 'Error al guardar: ' . odbc_errormsg($conn)]);
  ob_clean();
  echo $payload;
}

cerrarConexion($conn);
?>


