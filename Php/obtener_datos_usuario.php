<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

$datos_usuario = [
    'identificacion' => $_SESSION['atleta_id'],
    'usuario' => $_SESSION['atleta_usuario'],
    'nombre' => $_SESSION['atleta_nombre'],
    'apellido1' => $_SESSION['atleta_apellido1'],
    'apellido2' => $_SESSION['atleta_apellido2'],
    'correo' => $_SESSION['atleta_correo'],
    'telefono' => $_SESSION['atleta_telefono']
];

header('Content-Type: application/json');
echo json_encode($datos_usuario);
?>
