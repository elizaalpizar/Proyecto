<?php
require_once 'Session.php';
requireAdmin();

$id = $_POST['identificacion'] ?? '';
if (!$id) {
    die("<p style='color:red;'>Identificación faltante.</p>");
}

$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$passwordDB = "19861997.Sr";
$cs = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;UID=$username;PWD=$passwordDB;CharacterSet=UTF8;";

$conn = odbc_connect($cs, '', '') or die('Error de conexión');

$stmt = odbc_prepare($conn, "DELETE FROM atletas WHERE identificacion = ?");
$ok   = odbc_execute($stmt, [$id]);

if ($ok) {
    header('Location: ../Html/CatalogoAtletas.php?msg=eliminado');
} else {
    die("<p style='color:red;'>Error al eliminar: ".odbc_errormsg($conn)."</p>");
}
odbc_close($conn);
?>