<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../Público/InicioSesion.html");
    exit();
}

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = limpiarDatos($_POST['nombre']);
    $apellido1 = limpiarDatos($_POST['apellido1']);
    $apellido2 = limpiarDatos($_POST['apellido2']);
    $correo = limpiarDatos($_POST['correo']);
    $telefono = limpiarDatos($_POST['telefono']);
    $nueva_contrasena = isset($_POST['nueva_contrasena']) ? limpiarDatos($_POST['nueva_contrasena']) : '';
    
    if (empty($nombre) || empty($apellido1) || empty($correo) || empty($telefono)) {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
        $tipo_mensaje = 'error';
    } else {
        
        if (!empty($nueva_contrasena)) {
            $hash_contrasena = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $query = "UPDATE atletas SET nombre = ?, apellido1 = ?, apellido2 = ?, correo = ?, telefono = ?, contrasena = ? WHERE identificacion = ?";
            $params = array($nombre, $apellido1, $apellido2, $correo, $telefono, $hash_contrasena, $_SESSION['atleta_id']);
        } else {
            $query = "UPDATE atletas SET nombre = ?, apellido1 = ?, apellido2 = ?, correo = ?, telefono = ? WHERE identificacion = ?";
            $params = array($nombre, $apellido1, $apellido2, $correo, $telefono, $_SESSION['atleta_id']);
        }
        
        $stmt = odbc_prepare($conn, $query);
        
        if ($stmt) {
            $result = odbc_execute($stmt, $params);
            
            if ($result) {
                $_SESSION['atleta_nombre'] = $nombre;
                $_SESSION['atleta_apellido1'] = $apellido1;
                $_SESSION['atleta_apellido2'] = $apellido2;
                $_SESSION['atleta_correo'] = $correo;
                $_SESSION['atleta_telefono'] = $telefono;
                
                $mensaje = "¡Perfil actualizado exitosamente!";
                $tipo_mensaje = 'success';
                
            } else {
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

$redirect_url = "../Público/mi_perfil.html";
if ($mensaje) {
    $redirect_url .= "?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje);
}

header("Location: " . $redirect_url);
exit();
?>
