<?php
session_start();

function verificarSesion() {
    if (!isset($_SESSION['atleta_logged_in']) || $_SESSION['atleta_logged_in'] !== true) {
        header("Location: ../Público/InicioSesion.html");
        exit();
    }
    return true;
}

function requireAdmin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: ../Admin/InicioSesionAdmin.html");
        exit();
    }
    return true;
}

function obtenerInfoAtleta() {
    if (verificarSesion()) {
        return [
            'identificacion' => $_SESSION['atleta_identificacion'] ?? null,
            'usuario' => $_SESSION['atleta_usuario'] ?? null,
            'nombre' => $_SESSION['atleta_nombre'] ?? null,
            'apellido1' => $_SESSION['atleta_apellido1'] ?? null,
            'apellido2' => $_SESSION['atleta_apellido2'] ?? null,
            'correo' => $_SESSION['atleta_correo'] ?? null,
            'telefono' => $_SESSION['atleta_telefono'] ?? null
        ];
    }
    return null;
}

function obtenerInfoAdmin() {
    if (requireAdmin()) {
        return [
            'id' => $_SESSION['admin_id'] ?? null,
            'usuario' => $_SESSION['admin_username'] ?? null,
            'nombre' => $_SESSION['admin_nombre'] ?? null,
            'apellido' => $_SESSION['admin_apellido'] ?? null,
            'correo' => $_SESSION['admin_correo'] ?? null,
            'rol' => $_SESSION['admin_rol'] ?? null
        ];
    }
    return null;
}

function cerrarSesionAtleta() {
    unset($_SESSION['atleta_logged_in']);
    unset($_SESSION['atleta_identificacion']);
    unset($_SESSION['atleta_usuario']);
    unset($_SESSION['atleta_nombre']);
    unset($_SESSION['atleta_apellido1']);
    unset($_SESSION['atleta_apellido2']);
    unset($_SESSION['atleta_correo']);
    unset($_SESSION['atleta_telefono']);
    
    header("Location: ../Público/InicioSesion.html");
    exit();
}

function cerrarSesionAdmin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_nombre']);
    unset($_SESSION['admin_apellido']);
    unset($_SESSION['admin_correo']);
    unset($_SESSION['admin_rol']);
    unset($_SESSION['admin_logged_in']);
    
    header("Location: ../Admin/InicioSesionAdmin.html");
    exit();
}
?>
