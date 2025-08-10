<?php
echo "<h2>🔍 Prueba Simple de Conexión ODBC</h2>";

// Verificar si ODBC está disponible
echo "<h3>1. Verificación de ODBC en PHP</h3>";
if (extension_loaded('odbc')) {
    echo "<p style='color:green;'>✅ Extensión ODBC está cargada</p>";
    echo "<p><strong>Versión ODBC:</strong> " . phpversion('odbc') . "</p>";
} else {
    echo "<p style='color:red;'>❌ Extensión ODBC NO está cargada</p>";
    exit();
}

// Configuración de conexión
echo "<h3>2. Configuración de Conexión</h3>";
$server = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";

echo "<p><strong>Servidor:</strong> $server</p>";
echo "<p><strong>Base de datos:</strong> $database</p>";
echo "<p><strong>Usuario:</strong> $username</p>";
echo "<p><strong>Contraseña:</strong> " . str_repeat('*', strlen($password)) . "</p>";

// Intentar conexión con diferentes strings de conexión
echo "<h3>3. Pruebas de Conexión</h3>";

// Prueba 1: Driver específico
echo "<h4>Prueba 1: Driver ODBC 17 para SQL Server</h4>";
$connectionString1 = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";
echo "<p><strong>String de conexión:</strong> $connectionString1</p>";

$conn1 = @odbc_connect($connectionString1, $username, $password);
if ($conn1) {
    echo "<p style='color:green;'>✅ Conexión exitosa con Driver 17</p>";
    
    // Probar una consulta simple
    $result = odbc_exec($conn1, "SELECT @@VERSION as version");
    if ($result) {
        $row = odbc_fetch_array($result);
        echo "<p><strong>Versión SQL Server:</strong> " . $row['version'] . "</p>";
        odbc_free_result($result);
    }
    
    odbc_close($conn1);
} else {
    echo "<p style='color:red;'>❌ Error con Driver 17: " . odbc_errormsg() . "</p>";
}

// Prueba 2: Driver genérico
echo "<h4>Prueba 2: Driver ODBC para SQL Server</h4>";
$connectionString2 = "Driver={ODBC Driver for SQL Server};Server=$server;Database=$database;";
echo "<p><strong>String de conexión:</strong> $connectionString2</p>";

$conn2 = @odbc_connect($connectionString2, $username, $password);
if ($conn2) {
    echo "<p style='color:green;'>✅ Conexión exitosa con Driver genérico</p>";
    odbc_close($conn2);
} else {
    echo "<p style='color:red;'>❌ Error con Driver genérico: " . odbc_errormsg() . "</p>";
}

// Prueba 3: Sin especificar driver
echo "<h4>Prueba 3: Sin especificar driver</h4>";
$connectionString3 = "Server=$server;Database=$database;";
echo "<p><strong>String de conexión:</strong> $connectionString3</p>";

$conn3 = @odbc_connect($connectionString3, $username, $password);
if ($conn3) {
    echo "<p style='color:green;'>✅ Conexión exitosa sin especificar driver</p>";
    odbc_close($conn3);
} else {
    echo "<p style='color:red;'>❌ Error sin especificar driver: " . odbc_errormsg() . "</p>";
}

// Verificar información del sistema
echo "<h3>4. Información del Sistema</h3>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>OS:</strong> " . php_uname() . "</p>";

echo "<hr>";
echo "<h3>🔧 Solución Recomendada:</h3>";
echo "<p>Si ninguna de las pruebas funciona, necesitas instalar el <strong>ODBC Driver 17 for SQL Server</strong> en tu Windows.</p>";
echo "<ol>";
echo "<li>Descarga desde Microsoft: <a href='https://docs.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server' target='_blank'>ODBC Driver 17</a></li>";
echo "<li>Instala la versión de 64 bits (x64)</li>";
echo "<li>Reinicia XAMPP después de la instalación</li>";
echo "</ol>";

echo "<p><a href='../Público/RegistroAtleta.html' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔙 Volver al Registro</a></p>";
?>
