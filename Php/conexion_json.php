<?php
@ini_set('display_errors', '0');
@error_reporting(0);

$server = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";

$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";
$conn = @odbc_connect($connectionString, $username, $password);

if (!$conn) {
  http_response_code(500);
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(['error' => 'Error de conexión a la base de datos', 'detalle' => @odbc_errormsg()]);
  exit();
}

if (!function_exists('cerrarConexion')) {
  function cerrarConexion($conn) { @odbc_close($conn); }
}
if (!function_exists('limpiarDatos')) {
  function limpiarDatos($datos) {
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $datos;
  }
}
?>


