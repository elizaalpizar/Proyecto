<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../Público/InicioSesion.html");
    exit();
}

// Si llegamos aquí, el usuario está autenticado
// Los datos del usuario están disponibles en $_SESSION
?>
