<?php
$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";
$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";

// Procesar variables POST
$identificacion = trim($_POST['identificacion'] ?? '');
$usuario        = trim($_POST['usuario'] ?? '');
$password       = $_POST['password'] ?? '';
$nombre         = trim($_POST['nombre'] ?? '');
$apellido1      = trim($_POST['apellido1'] ?? '');
$apellido2      = trim($_POST['apellido2'] ?? '');
$correo         = trim($_POST['correo'] ?? '');
$telefono       = trim($_POST['telefono'] ?? '');

// Validaciones
$errors = [];
if (!preg_match('/^[0-9]{9,12}$/', $identificacion)) {
    $errors[] = "Identificación inválida.";
}
if (strlen($usuario) < 4) {
    $errors[] = "El usuario debe tener al menos 4 caracteres.";
}
if (strlen($password) < 6) {
    $errors[] = "La contraseña debe tener al menos 6 caracteres.";
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Correo electrónico inválido.";
}
if (!preg_match('/^[0-9]{8}$/', $telefono)) {
    $errors[] = "Teléfono inválido.";
}

if (count($errors) > 0) {
    echo "<div style='color:red;'><ul>";
    foreach ($errors as $e) {
        echo "<li>$e</li>";
    }
    echo "</ul></div>";
    exit;
}

// Hash de contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Conexión a la base de datos
$conn = odbc_connect($connectionString, $username, $password);
if (!$conn) {
    $errorMsg = odbc_errormsg();
    die("<p style='color:red;'>Error de conexión: $errorMsg</p>");
}

// Insertar registro
$sql = "INSERT INTO atletas (identificacion, usuario, contrasena, nombre, apellido1, apellido2, correo, telefono) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = odbc_prepare($conn, $sql);
if (!$stmt) {
    $errorMsg = odbc_errormsg($conn);
    odbc_close($conn);
    die("<p style='color:red;'>Error preparando consulta: $errorMsg</p>");
}

$params = [$identificacion, $usuario, $passwordHash, $nombre, $apellido1, $apellido2, $correo, $telefono];

$ok = odbc_execute($stmt, $params);

if ($ok) {
    echo "<p style='color:green;'>¡Registro exitoso!</p>";
    echo "<p><a href='../Público/InicioSesion.html'>Ir al inicio de sesión</a></p>";
} else {
    $error = odbc_errormsg($conn);
    if (strpos($error, 'UNIQUE') !== false) {
        echo "<p style='color:red;'>Ya existe un registro con esos datos.</p>";
    } else {
        echo "<p style='color:red;'>Error al registrar: $error</p>";
    }
}

odbc_close($conn);
?>
