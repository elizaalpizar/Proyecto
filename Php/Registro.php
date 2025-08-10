<?php
// Logging detallado para diagnóstico
error_log("=== INICIO DE REGISTRO.PHP ===");
error_log("Timestamp: " . date('Y-m-d H:i:s'));
error_log("Script: " . __FILE__);
error_log("Método HTTP: " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A'));
error_log("User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'N/A'));
error_log("Referer: " . ($_SERVER['HTTP_REFERER'] ?? 'N/A'));

$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";
$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";

error_log("Configuración de conexión:");
error_log("  Servidor: $server");
error_log("  Base de datos: $database");
error_log("  Usuario: $username");
error_log("  String de conexión: $connectionString");

error_log("=== PROCESANDO VARIABLES POST ===");
$identificacion = trim($_POST['identificacion'] ?? '');
$usuario        = trim($_POST['usuario'] ?? '');
$password       = $_POST['password'] ?? '';
$nombre         = trim($_POST['nombre'] ?? '');
$apellido1      = trim($_POST['apellido1'] ?? '');
$apellido2      = trim($_POST['apellido2'] ?? '');
$correo         = trim($_POST['correo'] ?? '');
$telefono       = trim($_POST['telefono'] ?? '');

error_log("Datos del formulario recibidos:");
error_log("  Identificación: '$identificacion'");
error_log("  Usuario: '$usuario'");
error_log("  Nombre: '$nombre'");
error_log("  Apellido1: '$apellido1'");
error_log("  Apellido2: '$apellido2'");
error_log("  Correo: '$correo'");
error_log("  Teléfono: '$telefono'");

error_log("=== INICIANDO VALIDACIONES ===");
$errors = [];
if (!preg_match('/^[0-9]{9,12}$/', $identificacion)) {
    $errors[] = "Identificación inválida.";
    error_log("❌ Validación falló: Identificación '$identificacion' no cumple el patrón");
}
if (strlen($usuario) < 4) {
    $errors[] = "El usuario debe tener al menos 4 caracteres.";
    error_log("❌ Validación falló: Usuario '$usuario' tiene solo " . strlen($usuario) . " caracteres");
}
if (strlen($password) < 6) {
    $errors[] = "La contraseña debe tener al menos 6 caracteres.";
    error_log("❌ Validación falló: Contraseña tiene solo " . strlen($password) . " caracteres");
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Correo electrónico inválido.";
    error_log("❌ Validación falló: Correo '$correo' no es válido");
}
if (!preg_match('/^[0-9]{8}$/', $telefono)) {
    $errors[] = "Teléfono inválido.";
    error_log("❌ Validación falló: Teléfono '$telefono' no cumple el patrón");
}

error_log("Total de errores encontrados: " . count($errors));

if (count($errors) > 0) {
    error_log("Errores de validación encontrados: " . implode(', ', $errors));
    echo "<div style='color:red;'><ul>";
    foreach ($errors as $e) {
        echo "<li>$e</li>";
    }
    echo "</ul></div>";
    error_log("=== FIN DE REGISTRO.PHP (VALIDACIÓN FALLÓ) ===");
    exit;
}

error_log("✅ Todas las validaciones pasaron correctamente");

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
error_log("Hash de contraseña generado: " . substr($passwordHash, 0, 20) . "...");

error_log("=== INTENTANDO CONEXIÓN ===");
error_log("Antes de odbc_connect() - PHP Version: " . PHP_VERSION);
error_log("Antes de odbc_connect() - Extensión ODBC cargada: " . (extension_loaded('odbc') ? 'SÍ' : 'NO'));

$conn = odbc_connect($connectionString, $username, $password);

error_log("Después de odbc_connect() - Resultado: " . ($conn ? 'EXITOSO' : 'FALLÓ'));
if (!$conn) {
    $errorMsg = odbc_errormsg();
    error_log("Error de conexión: $errorMsg");
    error_log("=== FIN DE REGISTRO.PHP (ERROR) ===");
    die("<p style='color:red;'>Error de conexión: $errorMsg</p>");
}

error_log("Conexión exitosa - ID de conexión: " . ($conn ? 'VÁLIDO' : 'INVÁLIDO'));

$sql = "
  INSERT INTO atletas
    (identificacion, usuario, contrasena, nombre,
     apellido1, apellido2, correo, telefono)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)
";

error_log("SQL a ejecutar: $sql");

$stmt = odbc_prepare($conn, $sql);
if (!$stmt) {
    $errorMsg = odbc_errormsg($conn);
    error_log("Error preparando statement: $errorMsg");
    odbc_close($conn);
    error_log("=== FIN DE REGISTRO.PHP (ERROR) ===");
    die("<p style='color:red;'>Error preparando consulta: $errorMsg</p>");
}

error_log("Statement preparado correctamente");

$params = [
  $identificacion,
  $usuario,
  $passwordHash,
  $nombre,
  $apellido1,
  $apellido2,
  $correo,
  $telefono 
];

error_log("Parámetros preparados para ejecución");

$ok = odbc_execute($stmt, $params);

if ($ok) {
    error_log("Registro insertado exitosamente");
    echo "<p style='color:green;'>¡Registro exitoso!</p>";
} else {
    $error = odbc_errormsg($conn);
    error_log("Error ejecutando INSERT: $error");
    if (strpos($error, 'UNIQUE') !== false) {
        echo "<p style='color:red;'>Ya existe un registro con esos datos.</p>";
    } else {
        echo "<p style='color:red;'>Error al registrar: $error</p>";
    }
}

odbc_close($conn);
error_log("Conexión cerrada");
error_log("=== FIN DE REGISTRO.PHP (EXITOSO) ===");
?>
