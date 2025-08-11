<?php
session_start();

$server = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";

$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";
$conn = odbc_connect($connectionString, $username, $password);

if (!$conn) {
    die("Error de conexión: " . odbc_errormsg());
}

$admin_username = trim($_POST['username'] ?? '');
$admin_password = $_POST['password'] ?? '';

if (empty($admin_username) || empty($admin_password)) {
    odbc_close($conn);
    echo "<script>
        alert('Por favor, complete todos los campos.');
        window.location.href = '../Admin/InicioSesionAdmin.html';
    </script>";
    exit();
}

$sql = "SELECT id_admin, usuario, contrasena, nombre, apellido, correo, rol 
        FROM administradores 
        WHERE usuario = ?";

$stmt = odbc_prepare($conn, $sql);
if (!$stmt) {
    odbc_close($conn);
    die("Error en la preparación de la consulta: " . odbc_errormsg());
}

odbc_execute($stmt, array($admin_username));
$row = odbc_fetch_array($stmt);

if ($row && password_verify($admin_password, $row['contrasena'])) {
    $_SESSION['admin_id'] = $row['id_admin'];
    $_SESSION['admin_username'] = $row['usuario'];
    $_SESSION['admin_nombre'] = $row['nombre'];
    $_SESSION['admin_apellido'] = $row['apellido'];
    $_SESSION['admin_correo'] = $row['correo'];
    $_SESSION['admin_rol'] = $row['rol'];
    $_SESSION['admin_logged_in'] = true;
    
    odbc_close($conn);
    
    header("Location: ../Admin/Catalogo_menu(CRUD).html");
    exit();
} else {
    odbc_close($conn);
    
    echo "<script>
        alert('Usuario o contraseña incorrectos. Por favor, intente nuevamente.');
        window.location.href = '../Admin/InicioSesionAdmin.html';
    </script>";
}
?>
