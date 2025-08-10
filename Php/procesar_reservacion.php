<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../Público/InicioSesion.html");
    exit();
}

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Privado/Reservacion.html");
    exit();
}

// Configuración de conexión a SQL Server
$server = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";

$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";
$conn = odbc_connect($connectionString, $username, $password);

if (!$conn) {
    die("Error de conexión: " . odbc_errormsg());
}

// Obtener datos del formulario
$id_atleta = $_SESSION['user_id'];
$servicio = $_POST['servicio'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$duracion = $_POST['duracion'];
$comentarios = isset($_POST['comentarios']) ? $_POST['comentarios'] : '';
$fecha_creacion = date('Y-m-d H:i:s');

// Validar que la fecha no sea anterior a hoy
if (strtotime($fecha) < strtotime(date('Y-m-d'))) {
    echo "<script>
        alert('No se puede reservar para fechas pasadas.');
        window.location.href = '../Privado/Reservacion.php';
    </script>";
    exit();
}

// Verificar si ya existe una reservación para esa fecha y hora
$sql_check = "SELECT * FROM reservaciones WHERE fecha = ? AND hora = ? AND servicio = ?";
$stmt_check = odbc_prepare($conn, $sql_check);
odbc_execute($stmt_check, array($fecha, $hora, $servicio));

if (odbc_fetch_array($stmt_check)) {
    echo "<script>
        alert('Ya existe una reservación para esa fecha, hora y servicio. Por favor, seleccione otro horario.');
        window.location.href = '../Privado/Reservacion.php';
    </script>";
    exit();
}

// Insertar la reservación
$sql_insert = "INSERT INTO reservaciones (id_atleta, servicio, fecha, hora, duracion, comentarios, fecha_creacion, estado) 
               VALUES (?, ?, ?, ?, ?, ?, ?, 'activa')";

$stmt_insert = odbc_prepare($conn, $sql_insert);
if (!$stmt_insert) {
    die("Error en la preparación de la consulta: " . odbc_errormsg());
}

$result = odbc_execute($stmt_insert, array($id_atleta, $servicio, $fecha, $hora, $duracion, $comentarios, $fecha_creacion));

if ($result) {
    echo "<script>
        alert('¡Reservación realizada con éxito!');
        window.location.href = '../Privado/Reservacion.php';
    </script>";
} else {
    echo "<script>
        alert('Error al procesar la reservación: " . odbc_errormsg() . "');
        window.location.href = '../Privado/Reservacion.php';
    </script>";
}

odbc_close($conn);
?>
