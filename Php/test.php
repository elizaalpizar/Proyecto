<?php
echo "¡PHP está funcionando!<br>";
echo "Fecha actual: " . date('Y-m-d H:i:s') . "<br>";
echo "Versión de PHP: " . phpversion() . "<br>";

// Probar conexión a SQL Server
$server = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";

$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";
$conn = odbc_connect($connectionString, $username, $password);

if (!$conn) {
    echo "❌ Error de conexión: " . odbc_errormsg() . "<br>";
} else {
    echo "✅ ¡Conectamos con éxito a SQL Server!<br>";
    
    // Probar consulta simple
    $query = "SELECT COUNT(*) as total FROM atletas";
    $result = odbc_exec($conn, $query);
    
    if ($result) {
        if (odbc_fetch_row($result)) {
            $total = odbc_result($result, 'total');
            echo "Total de atletas en la base de datos: " . $total . "<br>";
        }
        odbc_free_result($result);
    }
    
    odbc_close($conn);
}
?>
