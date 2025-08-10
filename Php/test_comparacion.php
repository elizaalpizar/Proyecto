<?php
echo "<h2>🔍 Test de Comparación de Conexiones</h2>";

// Configuración IDÉNTICA a la que funciona en test_conexion_simple.php
$server = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";

echo "<h3>1. Configuración de Conexión</h3>";
echo "<p><strong>Servidor:</strong> $server</p>";
echo "<p><strong>Base de datos:</strong> $database</p>";
echo "<p><strong>Usuario:</strong> $username</p>";
echo "<p><strong>Contraseña:</strong> " . str_repeat('*', strlen($password)) . "</p>";

// Test 1: String de conexión IDÉNTICO al que funciona
echo "<h3>2. Test 1: String IDÉNTICO al que funciona</h3>";
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

// Test 2: Simular exactamente lo que hace Registro.php
echo "<h3>3. Test 2: Simulando Registro.php</h3>";
$connectionString2 = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";
echo "<p><strong>String de conexión:</strong> $connectionString2</p>";

// Simular el mismo flujo que Registro.php
$conn2 = @odbc_connect($connectionString2, $username, $password);
if ($conn2) {
    echo "<p style='color:green;'>✅ Conexión exitosa simulando Registro.php</p>";
    
    // Probar INSERT (sin ejecutarlo realmente)
    $sql = "INSERT INTO atletas (identificacion, usuario, contrasena, nombre, apellido1, apellido2, correo, telefono) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = odbc_prepare($conn2, $sql);
    
    if ($stmt) {
        echo "<p style='color:green;'>✅ Preparación de INSERT exitosa</p>";
    } else {
        echo "<p style='color:red;'>❌ Error preparando INSERT: " . odbc_errormsg($conn2) . "</p>";
    }
    
    odbc_close($conn2);
} else {
    echo "<p style='color:red;'>❌ Error simulando Registro.php: " . odbc_errormsg() . "</p>";
}

// Test 3: Verificar si hay diferencia en el contexto
echo "<h3>4. Información del Contexto</h3>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Script actual:</strong> " . __FILE__ . "</p>";
echo "<p><strong>Directorio actual:</strong> " . getcwd() . "</p>";

// Test 4: Verificar si hay diferencia en las variables POST
echo "<h3>5. Verificación de Variables POST</h3>";
if (empty($_POST)) {
    echo "<p style='color:orange;'>⚠️ No hay variables POST (esto es normal para este test)</p>";
} else {
    echo "<p style='color:green;'>✅ Hay variables POST disponibles</p>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
}

echo "<hr>";
echo "<h3>🔧 Próximos Pasos:</h3>";
echo "<ol>";
echo "<li>Si Test 1 funciona pero Test 2 falla, hay algo en el contexto</li>";
echo "<li>Si ambos fallan, hay un problema general de conexión</li>";
echo "<li>Si ambos funcionan, el problema está en otro lugar</li>";
echo "</ol>";

echo "<p><a href='../Público/RegistroAtleta.html' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔙 Volver al Registro</a></p>";
?>
