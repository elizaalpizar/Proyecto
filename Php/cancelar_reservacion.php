<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
  header('Location: ../Público/InicioSesion.html');
  exit();
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
  header('Location: ../Público/mis_reservaciones.html?mensaje=' . urlencode('ID inválido') . '&tipo=error');
  exit();
}

$sql = "SELECT fecha FROM reservaciones WHERE id = ? AND id_atleta = ?";
$stmt = odbc_prepare($conn, $sql);
odbc_execute($stmt, [$id, $_SESSION['atleta_id']]);
$row = odbc_fetch_array($stmt);

if (!$row) {
  odbc_free_result($stmt);
  cerrarConexion($conn);
  header('Location: ../Público/mis_reservaciones.html?mensaje=' . urlencode('Reservación no encontrada') . '&tipo=error');
  exit();
}

$fecha = strtotime($row['fecha']);
$hoy = strtotime(date('Y-m-d'));

if ($fecha < $hoy) {
  odbc_free_result($stmt);
  cerrarConexion($conn);
  header('Location: ../Público/mis_reservaciones.html?mensaje=' . urlencode('No se puede cancelar una reservación pasada') . '&tipo=error');
  exit();
}

odbc_free_result($stmt);

$upd = odbc_prepare($conn, "UPDATE reservaciones SET estado = 'cancelada' WHERE id = ? AND id_atleta = ?");
$res = odbc_execute($upd, [$id, $_SESSION['atleta_id']]);

cerrarConexion($conn);

if ($res) {
  header('Location: ../Público/mis_reservaciones.html?mensaje=' . urlencode('Reservación cancelada') . '&tipo=success');
} else {
  header('Location: ../Público/mis_reservaciones.html?mensaje=' . urlencode('Error al cancelar: ' . odbc_errormsg()) . '&tipo=error');
}
exit();
?>
