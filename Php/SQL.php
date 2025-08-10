<?php
$server = "FELIPE";
$database = "ferreteria";
$username = "felipe";
$password = "Felipe0911";

// Cadena de conexión ODBC
$connectionString = "Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;";
$conn = odbc_connect($connectionString, $username, $password);

if (!$conn) {
    echo "<p style='color:red;'>❌ Error de conexión: " . odbc_errormsg() . "</p>";
} else {
    echo "<p style='color:green;'>✅ ¡Conectamos con éxito a SQL Server!</p>";
    odbc_close($conn);
}
?>
