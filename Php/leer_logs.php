<?php
echo "<h2>🔍 Leyendo Logs de Error</h2>";

$logFile = 'C:\\xampp\\apache\\logs\\error.log';

if (!file_exists($logFile)) {
    echo "<p style='color:red;'>❌ Archivo de log no encontrado: $logFile</p>";
    exit;
}

echo "<p>📁 Archivo de log: $logFile</p>";
echo "<p>📏 Tamaño: " . filesize($logFile) . " bytes</p>";

// Leer las últimas 200 líneas del log
$lines = file($logFile);
$totalLines = count($lines);
$startLine = max(0, $totalLines - 200);

echo "<p>📊 Mostrando las últimas " . ($totalLines - $startLine) . " líneas de $totalLines totales</p>";

echo "<h3>Últimos mensajes del log:</h3>";
echo "<div style='background: #f5f5f5; padding: 10px; border: 1px solid #ccc; max-height: 600px; overflow-y: auto; font-family: monospace; font-size: 12px;'>";

$foundRegistro = false;
for ($i = $startLine; $i < $totalLines; $i++) {
    $line = trim($lines[$i]);
    if (empty($line)) continue;
    
    // Resaltar líneas relacionadas con Registro.php
    if (strpos($line, 'Registro.php') !== false) {
        echo "<div style='background: #ffffcc; padding: 2px; margin: 1px 0; border-left: 3px solid #ffcc00;'>";
        echo "<strong>🎯 REGISTRO.PHP:</strong> " . htmlspecialchars($line) . "</div>";
        $foundRegistro = true;
    }
    // Resaltar líneas de error
    elseif (strpos($line, 'error') !== false || strpos($line, 'Error') !== false) {
        echo "<div style='background: #ffe6e6; padding: 2px; margin: 1px 0; border-left: 3px solid #ff0000;'>";
        echo "<strong>❌ ERROR:</strong> " . htmlspecialchars($line) . "</div>";
    }
    // Resaltar líneas de éxito
    elseif (strpos($line, 'success') !== false || strpos($line, 'Success') !== false || strpos($line, 'EXITOSO') !== false) {
        echo "<div style='background: #e6ffe6; padding: 2px; margin: 1px 0; border-left: 3px solid #00ff00;'>";
        echo "<strong>✅ ÉXITO:</strong> " . htmlspecialchars($line) . "</div>";
    }
    // Mostrar todas las líneas
    else {
        echo "<div style='padding: 2px; margin: 1px 0;'>" . htmlspecialchars($line) . "</div>";
    }
}

echo "</div>";

if (!$foundRegistro) {
    echo "<p style='color:orange;'>⚠️ No se encontraron mensajes específicos de Registro.php en las últimas líneas</p>";
    echo "<p>Esto podría significar que:</p>";
    echo "<ul>";
    echo "<li>El script no se ejecutó completamente</li>";
    echo "<li>Los logs se están escribiendo en otro lugar</li>";
    echo "<li>Hay un problema con la configuración de logging</li>";
    echo "</ul>";
}

echo "<h3>🔍 Búsqueda específica en todo el archivo:</h3>";
echo "<p>Buscando todas las líneas que contengan 'Registro.php':</p>";

$allRegistroLines = [];
foreach ($lines as $lineNum => $line) {
    if (strpos($line, 'Registro.php') !== false) {
        $allRegistroLines[] = [
            'line' => $lineNum + 1,
            'content' => trim($line)
        ];
    }
}

if (empty($allRegistroLines)) {
    echo "<p style='color:red;'>❌ No se encontraron mensajes de Registro.php en todo el archivo</p>";
} else {
    echo "<p>✅ Se encontraron " . count($allRegistroLines) . " mensajes de Registro.php:</p>";
    echo "<div style='background: #f0f8ff; padding: 10px; border: 1px solid #87ceeb; max-height: 400px; overflow-y: auto;'>";
    foreach ($allRegistroLines as $entry) {
        echo "<div style='margin: 2px 0;'>";
        echo "<strong>Línea {$entry['line']}:</strong> " . htmlspecialchars($entry['content']);
        echo "</div>";
    }
    echo "</div>";
}

echo "<h3>📝 Información adicional:</h3>";
echo "<p>Última modificación del archivo: " . date('Y-m-d H:i:s', filemtime($logFile)) . "</p>";
echo "<p>Archivo escribible: " . (is_writable($logFile) ? '✅ SÍ' : '❌ NO') . "</p>";
?>
