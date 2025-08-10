<?php
$server = "server.asralabs.com,14330";
$database = "Proyecto_Progra3";
$username = "sa";
$password = "19861997.Sr";

$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";
$conn = odbc_connect($connectionString, $username, $password);

if (!$conn) {
    echo "<p style='color:red;'>❌ Error de conexión: " . odbc_errormsg() . "</p>";
} else {
    echo "<p style='color:green;'>✅ ¡Conectamos con éxito a SQL Server!</p>";
    odbc_close($conn);
}
?>
