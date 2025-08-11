<?php
session_start();

function verificarSesionAdmin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: ../Público/SeleccionTipoUsuario.html");
        exit();
    }
    return true;
}

function obtenerInfoAdmin() {
    if (verificarSesionAdmin()) {
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

function cerrarSesionAdmin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_nombre']);
    unset($_SESSION['admin_apellido']);
    unset($_SESSION['admin_correo']);
    unset($_SESSION['admin_rol']);
    unset($_SESSION['admin_logged_in']);
    
    session_destroy();
    
    header("Location: ../Público/SeleccionTipoUsuario.html");
    exit();
}

function verificarPermisos($rolRequerido = 'admin') {
    if (!verificarSesionAdmin()) {
        return false;
    }
    
    $infoAdmin = obtenerInfoAdmin();
    if (!$infoAdmin) {
        return false;
    }
    
    if ($infoAdmin['rol'] === 'super_admin' || $infoAdmin['rol'] === $rolRequerido) {
        return true;
    }
    
    return false;
}
?>
