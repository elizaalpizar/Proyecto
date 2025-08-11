<?php
session_start();
require_once 'conexion_json.php';

header('Content-Type: application/json; charset=UTF-8');

// Solo administradores (respuesta JSON, sin redirecciones)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Asegurar tabla servicios (primera ejecución mínima si no existe)
@odbc_exec($conn, "IF OBJECT_ID('dbo.servicios','U') IS NULL
BEGIN
  CREATE TABLE servicios (
    id INT IDENTITY(1,1) PRIMARY KEY,
    codigo VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    tipo_zona VARCHAR(100) NULL,
    instructor VARCHAR(100) NULL,
    costo DECIMAL(10,2) NULL,
    estado BIT NULL,
    tipo VARCHAR(100) NULL
  );
END");

try {
    // Si 'tipo' está vacío pero existe 'tipo_zona', devolver ese valor como 'tipo'
    $sql = "SELECT codigo, nombre, COALESCE(NULLIF(tipo, ''), tipo_zona) AS tipo, instructor, costo
            FROM dbo.servicios
            ORDER BY id DESC";
    $stmt = odbc_exec($conn, $sql);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al consultar servicios: ' . odbc_errormsg()]);
        exit();
    }

    $servicios = [];
    while ($row = odbc_fetch_array($stmt)) {
        $r = array_change_key_case($row, CASE_LOWER);
        // Normalizar codificación a UTF-8 para evitar que json_encode devuelva null
        foreach ($r as $k => $v) {
            if (is_string($v)) {
                if (function_exists('mb_detect_encoding')) {
                    $enc = mb_detect_encoding($v, 'UTF-8, ISO-8859-1, Windows-1252', true);
                    if ($enc && $enc !== 'UTF-8') {
                        $v = mb_convert_encoding($v, 'UTF-8', $enc);
                    }
                } else {
                    $v = utf8_encode($v);
                }
                $r[$k] = $v;
            }
        }

        $codigo = $r['codigo'] ?? ($r['id'] ?? null);
        $nombre = $r['nombre'] ?? null;
        $tipo   = $r['tipo'] ?? ($r['tipo_zona'] ?? null);
        $instructor = $r['instructor'] ?? null;
        $costo  = $r['costo'] ?? null;
        $servicios[] = [
            'codigo' => $codigo,
            'nombre' => $nombre,
            'tipo' => $tipo,
            'instructor' => $instructor,
            'costo' => $costo,
        ];
    }

    echo json_encode($servicios, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR);
} finally {
    cerrarConexion($conn);
}

