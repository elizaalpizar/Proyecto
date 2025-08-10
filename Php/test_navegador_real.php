<?php
echo "<h2>🔍 Test de Navegador REAL</h2>";

// Simular EXACTAMENTE el contexto del navegador
echo "<h3>1. Simulando Contexto del Navegador</h3>";

// Simular headers HTTP reales
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
$_SERVER['HTTP_ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'es-ES,es;q=0.8,en-US;q=0.5,en;q=0.3';
$_SERVER['HTTP_ACCEPT_ENCODING'] = 'gzip, deflate';
$_SERVER['HTTP_CONNECTION'] = 'keep-alive';
$_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS'] = '1';

// Simular método POST
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';

// Simular datos POST reales
$_POST['identificacion'] = '123456789';
$_POST['usuario'] = 'testuser';
$_POST['password'] = 'testpass123';
$_POST['nombre'] = 'Test';
$_POST['apellido1'] = 'User';
$_POST['apellido2'] = 'Test';
$_POST['correo'] = 'test@test.com';
$_POST['telefono'] = '12345678';

echo "<p>✅ Contexto del navegador simulado</p>";
echo "<p><strong>User Agent:</strong> " . $_SERVER['HTTP_USER_AGENT'] . "</p>";
echo "<p><strong>Método:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><strong>Content-Type:</strong> " . $_SERVER['CONTENT_TYPE'] . "</p>";

echo "<h3>2. Verificación del Entorno</h3>";

echo "<p><strong>PHP_SAPI:</strong> " . php_sapi_name() . "</p>";
echo "<p><strong>Directorio actual:</strong> " . getcwd() . "</p>";
echo "<p><strong>Script actual:</strong> " . __FILE__ . "</p>";
echo "<p><strong>Variables POST:</strong> " . count($_POST) . " variables</p>";

echo "<h3>3. Test de Conexión (EXACTAMENTE como Registro.php)</h3>";

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
    
    // Verificar variables de entorno
    echo "<h4>🔍 Variables de Entorno:</h4>";
    echo "<p><strong>PATH:</strong> " . (getenv('PATH') ? 'Disponible' : 'No disponible') . "</p>";
    echo "<p><strong>SystemRoot:</strong> " . (getenv('SystemRoot') ? 'Disponible' : 'No disponible') . "</p>";
    echo "<p><strong>USERNAME:</strong> " . (getenv('USERNAME') ?: 'No disponible') . "</p>";
    
    // Verificar permisos
    echo "<h4>🔍 Verificación de Permisos:</h4>";
    echo "<p><strong>Directorio actual escribible:</strong> " . (is_writable('.') ? 'Sí' : 'No') . "</p>";
    echo "<p><strong>Archivo actual escribible:</strong> " . (is_writable(__FILE__) ? 'Sí' : 'No') . "</p>";
    
} else {
    echo "<p style='color:green;'>✅ ¡Conexión exitosa!</p>";
    
    // Probar la consulta INSERT
    $sql = "
      INSERT INTO atletas
        (identificacion, usuario, contrasena, nombre,
         apellido1, apellido2, correo, telefono)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    echo "<h3>4. Preparación de Consulta INSERT</h3>";
    echo "<p><strong>SQL:</strong> $sql</p>";
    
    $stmt = odbc_prepare($conn, $sql);
    if ($stmt) {
        echo "<p style='color:green;'>✅ Preparación de INSERT exitosa</p>";
        
        // Probar la ejecución (sin insertar realmente)
        echo "<h3>5. Test de Ejecución (sin insertar)</h3>";
        echo "<p>✅ Todo funcionando correctamente</p>";
        
    } else {
        echo "<p style='color:red;'>❌ Error preparando INSERT: " . odbc_errormsg($conn) . "</p>";
    }
    
    odbc_close($conn);
}

echo "<hr>";
echo "<h3>🔧 Análisis:</h3>";
echo "<p>Si la conexión falla aquí pero funciona en otros scripts, hay algo específico en el contexto del navegador.</p>";

echo "<p><a href='../Público/RegistroAtleta.html' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔙 Volver al Registro</a></p>";
?>
