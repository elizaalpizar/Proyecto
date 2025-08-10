<?php
echo "<h2>🔍 Verificación de la Estructura de la Tabla 'atletas'</h2>";

$server = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";
$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";

$conn = odbc_connect($connectionString, $username, $password);
if (!$conn) {
    die("<p style='color:red;'>❌ Error de conexión: " . odbc_errormsg() . "</p>");
}

echo "<p style='color:green;'>✅ Conexión exitosa a SQL Server</p>";

// 1. Verificar si la tabla existe
echo "<h3>1. Verificación de Existencia de la Tabla</h3>";
$sql_check = "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'atletas'";
$result = odbc_exec($conn, $sql_check);
if ($result) {
    $row = odbc_fetch_array($result);
    if ($row['count'] > 0) {
        echo "<p style='color:green;'>✅ La tabla 'atletas' EXISTE</p>";
    } else {
        echo "<p style='color:red;'>❌ La tabla 'atletas' NO EXISTE</p>";
        odbc_close($conn);
        exit();
    }
    odbc_free_result($result);
}

// 2. Mostrar la estructura completa de la tabla
echo "<h3>2. Estructura Completa de la Tabla 'atletas'</h3>";
$sql_structure = "
    SELECT 
        COLUMN_NAME,
        DATA_TYPE,
        IS_NULLABLE,
        CHARACTER_MAXIMUM_LENGTH,
        NUMERIC_PRECISION,
        NUMERIC_SCALE
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'atletas' 
    ORDER BY ORDINAL_POSITION
";

$result = odbc_exec($conn, $sql_structure);
if ($result) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th>Columna</th><th>Tipo de Dato</th><th>Permite NULL</th><th>Longitud</th><th>Precisión</th><th>Escala</th>";
    echo "</tr>";
    
    while ($row = odbc_fetch_array($result)) {
        echo "<tr>";
        echo "<td><strong>" . $row['COLUMN_NAME'] . "</strong></td>";
        echo "<td>" . $row['DATA_TYPE'] . "</td>";
        echo "<td>" . $row['IS_NULLABLE'] . "</td>";
        echo "<td>" . ($row['CHARACTER_MAXIMUM_LENGTH'] ?: 'N/A') . "</td>";
        echo "<td>" . ($row['NUMERIC_PRECISION'] ?: 'N/A') . "</td>";
        echo "<td>" . ($row['NUMERIC_SCALE'] ?: 'N/A') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    odbc_free_result($result);
}

// 3. Mostrar algunos datos de ejemplo (si existen)
echo "<h3>3. Datos de Ejemplo (si existen)</h3>";
$sql_sample = "SELECT TOP 3 * FROM atletas";
$result = odbc_exec($conn, $sql_sample);
if ($result) {
    $row_count = 0;
    while ($row = odbc_fetch_array($result)) {
        $row_count++;
        echo "<h4>Registro #$row_count:</h4>";
        echo "<pre>" . print_r($row, true) . "</pre>";
    }
    
    if ($row_count == 0) {
        echo "<p style='color:orange;'>⚠️ La tabla 'atletas' está vacía (no hay registros)</p>";
    }
    odbc_free_result($result);
}

// 4. Verificar restricciones y claves
echo "<h3>4. Restricciones y Claves</h3>";
$sql_constraints = "
    SELECT 
        CONSTRAINT_NAME,
        CONSTRAINT_TYPE
    FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
    WHERE TABLE_NAME = 'atletas'
";

$result = odbc_exec($conn, $sql_constraints);
if ($result) {
    $has_constraints = false;
    while ($row = odbc_fetch_array($result)) {
        $has_constraints = true;
        echo "<p><strong>" . $row['CONSTRAINT_TYPE'] . ":</strong> " . $row['CONSTRAINT_NAME'] . "</p>";
    }
    
    if (!$has_constraints) {
        echo "<p style='color:orange;'>⚠️ No se encontraron restricciones en la tabla</p>";
    }
    odbc_free_result($result);
}

odbc_close($conn);

echo "<hr>";
echo "<h3>🔧 Próximos Pasos:</h3>";
echo "<ol>";
echo "<li>Revisar la estructura real de la tabla</li>";
echo "<li>Identificar qué columnas están disponibles</li>";
echo "<li>Ajustar el código PHP para usar las columnas correctas</li>";
echo "<li>Crear la tabla correcta si es necesario</li>";
echo "</ol>";

echo "<p><a href='../Público/RegistroAtleta.html' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔙 Volver al Registro</a></p>";
?>
