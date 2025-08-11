<?php
session_start();

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

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validar que se recibieron los datos
if (empty($username) || empty($password)) {
    odbc_close($conn);
    echo "<script>
        alert('Por favor, complete todos los campos.');
        window.location.href = '../Público/InicioSesion.html';
    </script>";
    exit();
}

$sql = "SELECT identificacion, usuario, contrasena, nombre, apellido1, apellido2, correo, telefono 
        FROM atletas 
        WHERE usuario = ?";

$stmt = odbc_prepare($conn, $sql);
if (!$stmt) {
    odbc_close($conn);
    die("Error en la preparación de la consulta: " . odbc_errormsg());
}

odbc_execute($stmt, array($username));
$row = odbc_fetch_array($stmt);

if ($row && password_verify($password, $row['contrasena'])) {
    $_SESSION['user_id'] = $row['identificacion'];
    $_SESSION['username'] = $row['usuario'];
    $_SESSION['nombre'] = $row['nombre'];
    $_SESSION['apellido1'] = $row['apellido1'];
    $_SESSION['apellido2'] = $row['apellido2'];
    $_SESSION['correo'] = $row['correo'];
    $_SESSION['telefono'] = $row['telefono'];
    $_SESSION['logged_in'] = true;
    
    odbc_close($conn);
    
    header("Location: ../Privado/Reservacion.php");
    exit();
} else {
    odbc_close($conn);
    
    echo "<script>
        alert('Usuario o contraseña incorrectos. Por favor, intente nuevamente.');
        window.location.href = '../Público/InicioSesion.html';
    </script>";
}
?>
