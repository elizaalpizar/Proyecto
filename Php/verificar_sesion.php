<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Usuario no autenticado, redirigir al login
    header("Location: ../Público/InicioSesion.html");
    exit();
}

// Si llegamos aquí, el usuario está autenticado
// Los datos del usuario están disponibles en $_SESSION
?>
