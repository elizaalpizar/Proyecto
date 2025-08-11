<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../Público/InicioSesion.html");
    exit();
}

$mensaje = '';
$tipo_mensaje = '';

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener y limpiar datos del formulario
    $nombre = limpiarDatos($_POST['nombre']);
    $apellido1 = limpiarDatos($_POST['apellido1']);
    $apellido2 = limpiarDatos($_POST['apellido2']);
    $correo = limpiarDatos($_POST['correo']);
    $telefono = limpiarDatos($_POST['telefono']);
    $nueva_contrasena = isset($_POST['nueva_contrasena']) ? limpiarDatos($_POST['nueva_contrasena']) : '';
    
    // Validar que no estén vacíos los campos obligatorios
    if (empty($nombre) || empty($apellido1) || empty($correo) || empty($telefono)) {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
        $tipo_mensaje = 'error';
    } else {
        
        // Construir la consulta de actualización
        if (!empty($nueva_contrasena)) {
            // Si se proporciona nueva contraseña, hashearla
            $hash_contrasena = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $query = "UPDATE atletas SET nombre = ?, apellido1 = ?, apellido2 = ?, correo = ?, telefono = ?, contrasena = ? WHERE identificacion = ?";
            $params = array($nombre, $apellido1, $apellido2, $correo, $telefono, $hash_contrasena, $_SESSION['atleta_id']);
        } else {
            // Si no hay nueva contraseña, no actualizar ese campo
            $query = "UPDATE atletas SET nombre = ?, apellido1 = ?, apellido2 = ?, correo = ?, telefono = ? WHERE identificacion = ?";
            $params = array($nombre, $apellido1, $apellido2, $correo, $telefono, $_SESSION['atleta_id']);
        }
        
        $stmt = odbc_prepare($conn, $query);
        
        if ($stmt) {
            $result = odbc_execute($stmt, $params);
            
            if ($result) {
                // Actualización exitosa
                // Actualizar la sesión con los nuevos datos
                $_SESSION['atleta_nombre'] = $nombre;
                $_SESSION['atleta_apellido1'] = $apellido1;
                $_SESSION['atleta_apellido2'] = $apellido2;
                $_SESSION['atleta_correo'] = $correo;
                $_SESSION['atleta_telefono'] = $telefono;
                
                $mensaje = "¡Perfil actualizado exitosamente!";
                $tipo_mensaje = 'success';
                
            } else {
                // Error en la ejecución
                $mensaje = "Error al actualizar el perfil en la base de datos: " . odbc_errormsg();
                $tipo_mensaje = 'error';
            }
            
            odbc_free_result($stmt);
        } else {
            $mensaje = "Error en la preparación de la consulta: " . odbc_errormsg();
            $tipo_mensaje = 'error';
        }
    }
    
    cerrarConexion($conn);
}

// Redirigir de vuelta al perfil con el mensaje
$redirect_url = "../Público/mi_perfil.html";
if ($mensaje) {
    $redirect_url .= "?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje);
}

header("Location: " . $redirect_url);
exit();
?>
