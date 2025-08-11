<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT codigo, nombre, costo FROM servicios ORDER BY nombre";
    $stmt = odbc_exec($conn, $sql);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al consultar servicios: ' . odbc_errormsg()]);
        exit();
    }

    $servicios = [];
    while ($row = odbc_fetch_array($stmt)) {
        $servicios[] = [
            'codigo' => $row['codigo'],
            'nombre' => $row['nombre'],
            'costo' => isset($row['costo']) ? $row['costo'] : null,
        ];
    }

    echo json_encode($servicios);
} finally {
    cerrarConexion($conn);
}
?>
