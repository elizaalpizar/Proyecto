<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

// Database connection configuration
$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$passwordDB = "19861997.Sr";
$cs = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;UID=$username;PWD=$passwordDB;CharacterSet=UTF8;";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método no permitido.");
}

$identificacion = trim($_POST['identificacion'] ?? '');

if (empty($identificacion)) {
    die("Identificación requerida.");
}

$conn = odbc_connect($cs, '', '');
if (!$conn) {
    die("Error de conexión: " . odbc_errormsg());
}

$sql = "DELETE FROM atletas WHERE identificacion = ?";
$stmt = odbc_prepare($conn, $sql);

if (!$stmt) {
    die("Error preparando consulta: " . odbc_errormsg($conn));
}

$ok = odbc_execute($stmt, [$identificacion]);
if ($ok) {
    echo "<p style='color:green;'>¡Atleta eliminado correctamente!</p>";
    echo "<p><a href='../Admin/CatalogoAtleta.php'>Volver al catálogo</a></p>";
} else {
    echo "<p style='color:red;'>Error al eliminar: " . odbc_errormsg($conn) . "</p>";
}

odbc_close($conn);
?>
