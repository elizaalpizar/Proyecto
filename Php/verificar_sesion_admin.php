<?php
session_start();

// Función para verificar si el usuario está logueado como administrador
function verificarSesionAdmin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: ../Público/SeleccionTipoUsuario.html");
        exit();
    }
    return true;
}

// Función para obtener información del administrador logueado
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

// Función para cerrar sesión de administrador
function cerrarSesionAdmin() {
    // Destruir todas las variables de sesión de administrador
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_nombre']);
    unset($_SESSION['admin_apellido']);
    unset($_SESSION['admin_correo']);
    unset($_SESSION['admin_rol']);
    unset($_SESSION['admin_logged_in']);
    
    // Destruir la sesión
    session_destroy();
    
    // Redirigir a la página de selección
    header("Location: ../Público/SeleccionTipoUsuario.html");
    exit();
}

// Función para verificar permisos específicos
function verificarPermisos($rolRequerido = 'admin') {
    if (!verificarSesionAdmin()) {
        return false;
    }
    
    $infoAdmin = obtenerInfoAdmin();
    if (!$infoAdmin) {
        return false;
    }
    
    // Verificar si el rol del administrador coincide con el requerido
    if ($infoAdmin['rol'] === 'super_admin' || $infoAdmin['rol'] === $rolRequerido) {
        return true;
    }
    
    return false;
}
?>
