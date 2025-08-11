<?php
session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario = limpiarDatos($_POST['usuario']);
    $contrasena = limpiarDatos($_POST['contrasena']);
    
    if (empty($usuario) || empty($contrasena)) {
        $error = "Por favor, complete todos los campos.";
    } else {
        
        $query = "SELECT identificacion, usuario, contrasena, nombre, apellido1, apellido2, correo, telefono 
                  FROM atletas 
                  WHERE usuario = ?";
        
        $stmt = odbc_prepare($conn, $query);
        
        if ($stmt) {
            odbc_execute($stmt, array($usuario));
            
            if (odbc_fetch_row($stmt)) {
                $hash_almacenado = odbc_result($stmt, 'contrasena');
                
                if (password_verify($contrasena, $hash_almacenado)) {
                    $_SESSION['atleta_id'] = odbc_result($stmt, 'identificacion');
                    $_SESSION['atleta_usuario'] = odbc_result($stmt, 'usuario');
                    $_SESSION['atleta_nombre'] = odbc_result($stmt, 'nombre');
                    $_SESSION['atleta_apellido1'] = odbc_result($stmt, 'apellido1');
                    $_SESSION['atleta_apellido2'] = odbc_result($stmt, 'apellido2');
                    $_SESSION['atleta_correo'] = odbc_result($stmt, 'correo');
                    $_SESSION['atleta_telefono'] = odbc_result($stmt, 'telefono');
                    $_SESSION['logueado'] = true;
                    
                    header("Location: ../Público/dashboard_atleta.html");
                    exit();
                    
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }
            
            odbc_free_result($stmt);
        } else {
            $error = "Error en la consulta de la base de datos.";
        }
    }
    
    cerrarConexion($conn);
}

if (isset($error)) {
    $_SESSION['error_login'] = $error;
    header("Location: ../Público/InicioSesion.html?error=" . urlencode($error));
    exit();
}
?>
