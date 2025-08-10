<?php
echo "<h2>🔍 Prueba de Conexión y Estructura de la Base de Datos</h2>";

// Configuración de conexión a SQL Server
$server = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";

$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";
$conn = odbc_connect($connectionString, $username, $password);

if (!$conn) {
    echo "<p style='color:red;'>❌ Error de conexión: " . odbc_errormsg() . "</p>";
    exit();
} else {
    echo "<p style='color:green;'>✅ ¡Conectamos con éxito a SQL Server!</p>";
    echo "<p><strong>Servidor:</strong> $server</p>";
    echo "<p><strong>Base de datos:</strong> $database</p>";
}

// Verificar si la tabla atletas existe
$sql_check_table = "SELECT COUNT(*) as total FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'atletas'";
$result_check = odbc_exec($conn, $sql_check_table);
$row_check = odbc_fetch_array($result_check);

if ($row_check['total'] > 0) {
    echo "<p style='color:green;'>✅ La tabla 'atletas' existe</p>";
    
    // Mostrar estructura de la tabla
    echo "<h3>📋 Estructura de la tabla 'atletas':</h3>";
    $sql_structure = "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE 
                      FROM INFORMATION_SCHEMA.COLUMNS 
                      WHERE TABLE_NAME = 'atletas' 
                      ORDER BY ORDINAL_POSITION";
    
    $result_structure = odbc_exec($conn, $sql_structure);
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo de Dato</th><th>Permite NULL</th></tr>";
    
    while ($row_structure = odbc_fetch_array($result_structure)) {
        echo "<tr>";
        echo "<td>" . $row_structure['COLUMN_NAME'] . "</td>";
        echo "<td>" . $row_structure['DATA_TYPE'] . "</td>";
        echo "<td>" . $row_structure['IS_NULLABLE'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Contar registros en la tabla
    $sql_count = "SELECT COUNT(*) as total FROM atletas";
    $result_count = odbc_exec($conn, $sql_count);
    $row_count = odbc_fetch_array($result_count);
    
    echo "<p><strong>Total de atletas registrados:</strong> " . $row_count['total'] . "</p>";
    
    // Mostrar algunos registros de ejemplo (solo usuario y nombre, sin contraseñas)
    if ($row_count['total'] > 0) {
        echo "<h3>👥 Usuarios disponibles para prueba:</h3>";
        $sql_users = "SELECT TOP 5 usuario, nombre, apellido1, apellido2 FROM atletas";
        $result_users = odbc_exec($conn, $sql_users);
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Usuario</th><th>Nombre Completo</th></tr>";
        
        while ($row_users = odbc_fetch_array($result_users)) {
            echo "<tr>";
            echo "<td>" . $row_users['usuario'] . "</td>";
            echo "<td>" . $row_users['nombre'] . " " . $row_users['apellido1'] . " " . $row_users['apellido2'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} else {
    echo "<p style='color:red;'>❌ La tabla 'atletas' NO existe</p>";
}

// Verificar si la tabla reservaciones existe
$sql_check_reservaciones = "SELECT COUNT(*) as total FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'reservaciones'";
$result_check_res = odbc_exec($conn, $sql_check_reservaciones);
$row_check_res = odbc_fetch_array($result_check_res);

if ($row_check_res['total'] > 0) {
    echo "<p style='color:green;'>✅ La tabla 'reservaciones' existe</p>";
} else {
    echo "<p style='color:orange;'>⚠️ La tabla 'reservaciones' NO existe (necesaria para el sistema completo)</p>";
}

odbc_close($conn);

echo "<hr>";
echo "<h3>🧪 Próximos pasos para probar:</h3>";
echo "<ol>";
echo "<li>Verifica que la conexión sea exitosa ✅</li>";
echo "<li>Confirma que la tabla 'atletas' existe ✅</li>";
echo "<li>Verifica que hay al menos un usuario registrado ✅</li>";
echo "<li>Prueba el login con un usuario existente</li>";
echo "<li>Verifica que redirija a la página de reservación</li>";
echo "</ol>";

echo "<p><a href='../Público/InicioSesion.html' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Probar Login</a></p>";
?>
