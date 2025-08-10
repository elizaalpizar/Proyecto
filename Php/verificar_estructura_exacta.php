<?php
echo "<h2>🔍 Verificación de Estructura Exacta de la Tabla</h2>";

$server = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";

$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";
$conn = odbc_connect($connectionString, $username, $password);

if (!$conn) {
    die("<p style='color:red;'>❌ Error de conexión: " . odbc_errormsg() . "</p>");
}

echo "<p style='color:green;'>✅ Conexión exitosa</p>";

// 1. Verificar la estructura exacta de la tabla
echo "<h3>1. Estructura Exacta de la Tabla 'atletas'</h3>";

$sql_structure = "
    SELECT 
        COLUMN_NAME,
        DATA_TYPE,
        IS_NULLABLE,
        CHARACTER_MAXIMUM_LENGTH,
        ORDINAL_POSITION
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'atletas' 
    ORDER BY ORDINAL_POSITION
";

$result = odbc_exec($conn, $sql_structure);
if (!$result) {
    echo "<p style='color:red;'>❌ Error obteniendo estructura: " . odbc_errormsg($conn) . "</p>";
} else {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'>";
    echo "<th>Posición</th><th>Nombre Columna</th><th>Tipo</th><th>Permite NULL</th><th>Longitud</th>";
    echo "</tr>";
    
    while ($row = odbc_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['ORDINAL_POSITION'] . "</td>";
        echo "<td><strong>" . htmlspecialchars($row['COLUMN_NAME']) . "</strong></td>";
        echo "<td>" . $row['DATA_TYPE'] . "</td>";
        echo "<td>" . $row['IS_NULLABLE'] . "</td>";
        echo "<td>" . ($row['CHARACTER_MAXIMUM_LENGTH'] ?? 'N/A') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    odbc_free_result($result);
}

// 2. Verificar si hay caracteres especiales en los nombres de columnas
echo "<h3>2. Verificación de Caracteres Especiales</h3>";

$sql_chars = "
    SELECT 
        COLUMN_NAME,
        ASCII(SUBSTRING(COLUMN_NAME, 1, 1)) as first_char_ascii,
        ASCII(SUBSTRING(COLUMN_NAME, 2, 1)) as second_char_ascii,
        ASCII(SUBSTRING(COLUMN_NAME, 3, 1)) as third_char_ascii
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'atletas' 
    AND COLUMN_NAME LIKE '%contrase%'
";

$result_chars = odbc_exec($conn, $sql_chars);
if ($result_chars) {
    while ($row = odbc_fetch_array($result_chars)) {
        echo "<p><strong>Columna:</strong> " . $row['COLUMN_NAME'] . "</p>";
        echo "<p><strong>ASCII del primer carácter:</strong> " . $row['first_char_ascii'] . "</p>";
        echo "<p><strong>ASCII del segundo carácter:</strong> " . $row['second_char_ascii'] . "</p>";
        echo "<p><strong>ASCII del tercer carácter:</strong> " . $row['third_char_ascii'] . "</p>";
    }
    odbc_free_result($result_chars);
}

// 3. Probar INSERT con nombres de columnas exactos
echo "<h3>3. Test de INSERT con Nombres Exactos</h3>";

// Obtener el nombre exacto de la columna de contraseña
$sql_password_col = "
    SELECT COLUMN_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'atletas' 
    AND COLUMN_NAME LIKE '%contrase%'
";

$result_pwd = odbc_exec($conn, $sql_password_col);
$password_column_name = '';
if ($result_pwd && $row = odbc_fetch_array($result_pwd)) {
    $password_column_name = $row['COLUMN_NAME'];
    echo "<p><strong>Nombre exacto de la columna de contraseña:</strong> '$password_column_name'</p>";
    odbc_free_result($result_pwd);
}

if ($password_column_name) {
    // Probar con el nombre exacto
    $sql_test = "
        INSERT INTO atletas
          (identificacion, usuario, $password_column_name, nombre,
           apellido1, apellido2, correo, telefono)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    echo "<p><strong>SQL de prueba:</strong> $sql_test</p>";
    
    $stmt = odbc_prepare($conn, $sql_test);
    if ($stmt) {
        echo "<p style='color:green;'>✅ Preparación exitosa con nombre exacto</p>";
    } else {
        echo "<p style='color:red;'>❌ Error preparando: " . odbc_errormsg($conn) . "</p>";
    }
}

// 4. Verificar la codificación de la base de datos
echo "<h3>4. Información de Codificación</h3>";

$sql_collation = "SELECT DATABASEPROPERTYEX('$database', 'Collation') as collation";
$result_coll = odbc_exec($conn, $sql_collation);
if ($result_coll && $row = odbc_fetch_array($result_coll)) {
    echo "<p><strong>Collation de la base de datos:</strong> " . $row['collation'] . "</p>";
    odbc_free_result($result_coll);
}

odbc_close($conn);

echo "<hr>";
echo "<h3>🔧 Próximos Pasos:</h3>";
echo "<ol>";
echo "<li>Revisar si hay caracteres especiales en los nombres de columnas</li>";
echo "<li>Verificar la codificación de la base de datos</li>";
echo "<li>Probar con nombres de columnas exactos</li>";
echo "<li>Considerar renombrar columnas problemáticas</li>";
echo "</ol>";

echo "<p><a href='../Público/RegistroAtleta.html' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔙 Volver al Registro</a></p>";
?>
