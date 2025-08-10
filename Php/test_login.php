<?php
// Archivo de prueba para verificar la funcionalidad del login
session_start();

echo "<h2>Prueba del Sistema de Login</h2>";

// Verificar si hay una sesión activa
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo "<p style='color: green;'>✅ Usuario autenticado correctamente</p>";
    echo "<p><strong>ID:</strong> " . $_SESSION['user_id'] . "</p>";
    echo "<p><strong>Usuario:</strong> " . $_SESSION['username'] . "</p>";
    echo "<p><strong>Nombre:</strong> " . $_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2'] . "</p>";
    echo "<p><strong>Email:</strong> " . $_SESSION['email'] . "</p>";
    echo "<p><strong>Teléfono:</strong> " . $_SESSION['telefono'] . "</p>";
    
    echo "<br><a href='logout.php'>Cerrar Sesión</a>";
} else {
    echo "<p style='color: red;'>❌ No hay sesión activa</p>";
    echo "<p><a href='../Público/InicioSesion.html'>Ir al Login</a></p>";
}

// Mostrar información de la sesión
echo "<h3>Información de la Sesión:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
