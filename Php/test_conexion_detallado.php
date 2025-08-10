<?php
echo "<h2>🔍 Diagnóstico de Conexión SQL Server</h2>";

// Configuración
$server   = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";

echo "<h3>📋 Configuración:</h3>";
echo "<p><strong>Servidor:</strong> $server</p>";
echo "<p><strong>Base de datos:</strong> $database</p>";
echo "<p><strong>Usuario:</strong> $username</p>";
echo "<p><strong>Contraseña:</strong> " . str_repeat('*', strlen($password)) . "</p>";

// Probar diferentes strings de conexión
$connectionStrings = [
    "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;",
    "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;Trusted_Connection=no;",
    "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;UID=$username;PWD=$password;",
    "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;UID=$username;PWD=$password;Trusted_Connection=no;"
];

echo "<h3>🧪 Probando diferentes strings de conexión:</h3>";

foreach ($connectionStrings as $index => $connString) {
    echo "<h4>Intento " . ($index + 1) . ":</h4>";
    echo "<p><strong>String:</strong> " . htmlspecialchars($connString) . "</p>";
    
    try {
        $conn = odbc_connect($connString, $username, $password);
        if ($conn) {
            echo "<p style='color:green;'>✅ Conexión exitosa!</p>";
            odbc_close($conn);
        } else {
            $error = odbc_errormsg();
            echo "<p style='color:red;'>❌ Error: $error</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ Excepción: " . $e->getMessage() . "</p>";
    }
    echo "<hr>";
}

// Verificar drivers ODBC disponibles
echo "<h3>🔧 Drivers ODBC disponibles:</h3>";
$drivers = odbc_drivers();
if ($drivers) {
    echo "<ul>";
    foreach ($drivers as $driver) {
        echo "<li>" . htmlspecialchars($driver) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:red;'>❌ No se encontraron drivers ODBC</p>";
}

echo "<h3>💡 Sugerencias:</h3>";
echo "<ul>";
echo "<li>Verifica que las credenciales sean exactas (mayúsculas/minúsculas)</li>";
echo "<li>Confirma que el usuario 'sa' esté habilitado en SQL Server</li>";
echo "<li>Verifica que el puerto 14330 esté abierto</li>";
echo "<li>Intenta con autenticación de Windows si es posible</li>";
echo "</ul>";
?>
