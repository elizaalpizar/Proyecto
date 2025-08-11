<?php
session_start();
require_once 'conexion.php';

// Requiere usuario logueado
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
  header('Location: ../Público/InicioSesion.html');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../Público/nueva_reservacion.html');
  exit();
}

// Datos
$idAtleta = $_SESSION['atleta_id'];
$servicio = limpiarDatos($_POST['servicio'] ?? '');
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$duracion = $_POST['duracion'] ?? '';
$comentarios = limpiarDatos($_POST['comentarios'] ?? '');
$fechaCreacion = date('Y-m-d H:i:s');

$mensaje = '';
$tipo = 'success';

// Normalizar formatos para SQL Server
$fechaSQL = $fecha ? date('Y-m-d', strtotime($fecha)) : '';
$horaSQL = $hora ? date('H:i:s', strtotime($hora)) : '';
$duracionNum = is_numeric(str_replace(',', '.', $duracion)) ? (float)str_replace(',', '.', $duracion) : null;

// Validaciones
$errores = [];
if (!$servicio) $errores[] = 'Seleccione un servicio';
if (!$fechaSQL) $errores[] = 'Seleccione una fecha válida';
if (!$horaSQL) $errores[] = 'Seleccione una hora válida';
if ($duracionNum === null) $errores[] = 'Seleccione una duración válida';

// Fecha no pasada
if ($fechaSQL && strtotime($fechaSQL) < strtotime(date('Y-m-d'))) {
  $errores[] = 'No se puede reservar en el pasado';
}

if (count($errores) > 0) {
  $mensaje = implode('. ', $errores);
  $tipo = 'error';
  $url = '../Público/nueva_reservacion.html?mensaje=' . urlencode($mensaje) . '&tipo=' . urlencode($tipo);
  header('Location: ' . $url);
  exit();
}

$sqlCheck = "SELECT 1 FROM reservaciones WHERE fecha = ? AND hora = ? AND servicio = ?";
$stmtCheck = odbc_prepare($conn, $sqlCheck);
odbc_execute($stmtCheck, [$fechaSQL, $horaSQL, $servicio]);
if (odbc_fetch_row($stmtCheck)) {
  odbc_free_result($stmtCheck);
  $mensaje = 'Ya existe una reservación para ese servicio, fecha y hora.';
  $tipo = 'error';
  $url = '../Público/nueva_reservacion.html?mensaje=' . urlencode($mensaje) . '&tipo=' . urlencode($tipo);
  header('Location: ' . $url);
  exit();
}
odbc_free_result($stmtCheck);

// Insertar
$sqlInsert = "INSERT INTO reservaciones (id_atleta, servicio, fecha, hora, duracion, comentarios, fecha_creacion, estado) VALUES (?, ?, ?, ?, ?, ?, ?, 'activa')";
$stmtInsert = odbc_prepare($conn, $sqlInsert);

$result = odbc_execute($stmtInsert, [$idAtleta, $servicio, $fechaSQL, $horaSQL, $duracionNum, $comentarios, $fechaCreacion]);

if ($result) {
  $mensaje = '¡Reservación realizada con éxito!';
  $tipo = 'success';
} else {
  $mensaje = 'Error al procesar la reservación: ' . odbc_errormsg();
  $tipo = 'error';
}

odbc_free_result($stmtInsert);
cerrarConexion($conn);

header('Location: ' . '../Público/nueva_reservacion.html?mensaje=' . urlencode($mensaje) . '&tipo=' . urlencode($tipo));
exit();
?>
