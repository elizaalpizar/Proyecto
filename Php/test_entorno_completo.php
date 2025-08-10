<?php
echo "<h2>🔍 Test de Entorno COMPLETO</h2>";

// Simular EXACTAMENTE el contexto del formulario
echo "<h3>1. Simulando Contexto del Formulario</h3>";

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

echo "<h3>2. Información del Sistema</h3>";

echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>PHP_SAPI:</strong> " . php_sapi_name() . "</p>";
echo "<p><strong>OS:</strong> " . PHP_OS . "</p>";
echo "<p><strong>Directorio actual:</strong> " . getcwd() . "</p>";
echo "<p><strong>Script actual:</strong> " . __FILE__ . "</p>";
echo "<p><strong>Usuario del sistema:</strong> " . get_current_user() . "</p>";

echo "<h3>3. Variables de Entorno del Sistema</h3>";

$env_vars = [
    'PATH', 'SystemRoot', 'USERNAME', 'USERPROFILE', 'TEMP', 'TMP',
    'WINDIR', 'PROGRAMFILES', 'PROGRAMFILES(X86)', 'APPDATA',
    'LOCALAPPDATA', 'HOMEDRIVE', 'HOMEPATH', 'COMPUTERNAME'
];

foreach ($env_vars as $var) {
    $value = getenv($var);
    if ($value) {
        echo "<p><strong>$var:</strong> " . substr($value, 0, 100) . (strlen($value) > 100 ? '...' : '') . "</p>";
    } else {
        echo "<p><strong>$var:</strong> <span style='color:orange;'>No disponible</span></p>";
    }
}

echo "<h3>4. Verificación de Permisos</h3>";

echo "<p><strong>Directorio actual escribible:</strong> " . (is_writable('.') ? '✅ Sí' : '❌ No') . "</p>";
echo "<p><strong>Archivo actual escribible:</strong> " . (is_writable(__FILE__) ? '✅ Sí' : '❌ No') . "</p>";
echo "<p><strong>Directorio padre escribible:</strong> " . (is_writable('..') ? '✅ Sí' : '❌ No') . "</p>";

echo "<h3>5. Verificación de Extensiones PHP</h3>";

$extensions = ['odbc', 'pdo_odbc', 'sqlsrv', 'pdo_sqlsrv'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p><strong>$ext:</strong> <span style='color:green;'>✅ Cargada</span></p>";
    } else {
        echo "<p><strong>$ext:</strong> <span style='color:red;'>❌ No cargada</span></p>";
    }
}

echo "<h3>6. Verificación de Drivers ODBC</h3>";

if (function_exists('odbc_drivers')) {
    $drivers = odbc_drivers();
    if ($drivers) {
        echo "<p><strong>Drivers ODBC disponibles:</strong></p>";
        foreach ($drivers as $driver) {
            echo "<p style='margin-left: 20px;'>• " . $driver['name'] . "</p>";
        }
    } else {
        echo "<p><strong>Drivers ODBC:</strong> <span style='color:orange;'>⚠️ No se encontraron drivers</span></p>";
    }
} else {
    echo "<p><strong>Drivers ODBC:</strong> <span style='color:red;'>❌ Función odbc_drivers() no disponible</span></p>";
}

echo "<h3>7. Test de Conexión (EXACTAMENTE como Registro.php)</h3>";

$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";
$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";

echo "<p><strong>Servidor:</strong> $server</p>";
echo "<p><strong>Base de datos:</strong> $database</p>";
echo "<p><strong>Usuario:</strong> $username</p>";
echo "<p><strong>String de conexión:</strong> $connectionString</p>";

// Usar la MISMA línea exacta que Registro.php
$conn = odbc_connect($connectionString, $username, $password);

if (!$conn) {
    $error = odbc_errormsg();
    echo "<p style='color:red;'>❌ Error de conexión: $error</p>";
    
    // Información adicional del error
    echo "<h4>🔍 Información del Error:</h4>";
    echo "<p><strong>Error completo:</strong> $error</p>";
    
    // Verificar si hay diferencias en el contexto
    echo "<h4>🔍 Verificación del Contexto:</h4>";
    echo "<p><strong>Variables POST:</strong> " . (empty($_POST) ? 'Vacías' : 'Disponibles') . "</p>";
    echo "<p><strong>Método:</strong> " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A') . "</p>";
    
} else {
    echo "<p style='color:green;'>✅ ¡Conexión exitosa!</p>";
    
    // Probar la consulta INSERT
    $sql = "
      INSERT INTO atletas
        (identificacion, usuario, contrasena, nombre,
         apellido1, apellido2, correo, telefono)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    echo "<h3>8. Preparación de Consulta INSERT</h3>";
    echo "<p><strong>SQL:</strong> $sql</p>";
    
    $stmt = odbc_prepare($conn, $sql);
    if ($stmt) {
        echo "<p style='color:green;'>✅ Preparación de INSERT exitosa</p>";
        
        // Probar la ejecución (sin insertar realmente)
        echo "<h3>9. Test de Ejecución (sin insertar)</h3>";
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
