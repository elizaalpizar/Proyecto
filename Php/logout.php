<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir a la página principal
header("Location: ../Público/PaginaPrincipal.html");
exit();
?>
