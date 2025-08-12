<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

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

// Eliminar en una transacción para respetar FKs (reservaciones -> atletas)
@odbc_exec($conn, 'BEGIN TRANSACTION');

// Primero eliminar reservaciones del atleta si existen
$delRes = odbc_prepare($conn, "DELETE FROM reservaciones WHERE id_atleta = ?");
$okRes = $delRes ? @odbc_execute($delRes, [$identificacion]) : false;

// Luego eliminar el atleta
$delAtl = odbc_prepare($conn, "DELETE FROM atletas WHERE identificacion = ?");
$okAtl = $delAtl ? @odbc_execute($delAtl, [$identificacion]) : false;

if ($okRes && $okAtl) {
    @odbc_exec($conn, 'COMMIT TRANSACTION');
    echo "<script>alert('¡Atleta eliminado correctamente! (reservaciones asociadas también fueron eliminadas)'); window.location.href='../Admin/CatalogoAtleta.php';</script>";
    exit;
} else {
    @odbc_exec($conn, 'ROLLBACK TRANSACTION');
    $err = addslashes(odbc_errormsg($conn));
    echo "<script>alert('No se pudo eliminar al atleta. Detalle: $err'); window.location.href='../Admin/CatalogoAtleta.php';</script>";
    exit;
}

odbc_close($conn);
?>
