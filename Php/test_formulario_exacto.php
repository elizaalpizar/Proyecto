<?php
echo "<h2>🔍 Test de Formulario EXACTO</h2>";

// Simular EXACTAMENTE el contexto del formulario
echo "<h3>1. Simulando Contexto del Formulario</h3>";

// Simular que venimos de un POST real
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['identificacion'] = '123456789';
$_POST['usuario'] = 'testuser';
$_POST['password'] = 'testpass123';
$_POST['nombre'] = 'Test';
$_POST['apellido1'] = 'User';
$_POST['apellido2'] = 'Test';
$_POST['correo'] = 'test@test.com';
$_POST['telefono'] = '12345678';

echo "<p>✅ Contexto del formulario simulado</p>";
echo "<p><strong>Método:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><strong>Variables POST:</strong> " . count($_POST) . " variables</p>";

echo "<h3>2. Procesando Datos (EXACTAMENTE como Registro.php)</h3>";

$identificacion = trim($_POST['identificacion'] ?? '');
$usuario        = trim($_POST['usuario'] ?? '');
$password       = $_POST['password'] ?? '';
$nombre         = trim($_POST['nombre'] ?? '');
$apellido1      = trim($_POST['apellido1'] ?? '');
$apellido2      = trim($_POST['apellido2'] ?? '');
$correo         = trim($_POST['correo'] ?? '');
$telefono       = trim($_POST['telefono'] ?? '');

echo "<p>✅ Datos procesados</p>";

echo "<h3>3. Validaciones (EXACTAMENTE como Registro.php)</h3>";

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

echo "<p>✅ Todas las validaciones pasaron</p>";

echo "<h3>4. Hash de Contraseña (EXACTAMENTE como Registro.php)</h3>";

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
echo "<p>✅ Hash generado: " . substr($passwordHash, 0, 20) . "...</p>";

echo "<h3>5. Configuración de Conexión (EXACTAMENTE como Registro.php)</h3>";

$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";
$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";

echo "<p><strong>Servidor:</strong> $server</p>";
echo "<p><strong>Base de datos:</strong> $database</p>";
echo "<p><strong>Usuario:</strong> $username</p>";
echo "<p><strong>String de conexión:</strong> $connectionString</p>";

echo "<h3>6. Test de Conexión (EXACTAMENTE como Registro.php)</h3>";

// Usar la MISMA línea exacta que Registro.php
$conn = odbc_connect($connectionString, $username, $password);

if (!$conn) {
    $error = odbc_errormsg();
    echo "<p style='color:red;'>❌ Error de conexión: $error</p>";
    
    // Información adicional
    echo "<h4>🔍 Información del Error:</h4>";
    echo "<p><strong>Error completo:</strong> $error</p>";
    echo "<p><strong>Script actual:</strong> " . __FILE__ . "</p>";
    echo "<p><strong>Directorio actual:</strong> " . getcwd() . "</p>";
    echo "<p><strong>Variables POST:</strong> " . (empty($_POST) ? 'Vacías' : 'Disponibles') . "</p>";
    
    // Verificar si hay diferencias en el contexto
    echo "<h4>🔍 Verificación del Contexto:</h4>";
    echo "<p><strong>PHP_SAPI:</strong> " . php_sapi_name() . "</p>";
    echo "<p><strong>User Agent:</strong> " . ($_SERVER['HTTP_USER_AGENT'] ?? 'N/A') . "</p>";
    echo "<p><strong>Remote Addr:</strong> " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "</p>";
    
} else {
    echo "<p style='color:green;'>✅ ¡Conexión exitosa!</p>";
    
    // Probar la consulta INSERT
    $sql = "
      INSERT INTO atletas
        (identificacion, usuario, contrasena, nombre,
         apellido1, apellido2, correo, telefono)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    echo "<h3>7. Preparación de Consulta INSERT</h3>";
    echo "<p><strong>SQL:</strong> $sql</p>";
    
    $stmt = odbc_prepare($conn, $sql);
    if ($stmt) {
        echo "<p style='color:green;'>✅ Preparación de INSERT exitosa</p>";
        
        // Probar la ejecución (sin insertar realmente)
        echo "<h3>8. Test de Ejecución (sin insertar)</h3>";
        echo "<p>✅ Todo funcionando correctamente</p>";
        
    } else {
        echo "<p style='color:red;'>❌ Error preparando INSERT: " . odbc_errormsg($conn) . "</p>";
    }
    
    odbc_close($conn);
}

echo "<hr>";
echo "<h3>🔧 Análisis:</h3>";
echo "<p>Si la conexión falla aquí pero funciona en otros scripts, hay algo específico en el contexto del formulario.</p>";

echo "<p><a href='../Público/RegistroAtleta.html' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔙 Volver al Registro</a></p>";
?>
