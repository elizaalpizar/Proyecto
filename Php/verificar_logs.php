<?php
echo "<h2>🔍 Verificación de Configuración de Logs</h2>";

echo "<h3>1. Configuración de error_log en PHP</h3>";
echo "error_log() está disponible: " . (function_exists('error_log') ? '✅ SÍ' : '❌ NO') . "<br>";
echo "error_reporting: " . error_reporting() . "<br>";
echo "display_errors: " . (ini_get('display_errors') ? 'ON' : 'OFF') . "<br>";
echo "log_errors: " . (ini_get('log_errors') ? 'ON' : 'OFF') . "<br>";

echo "<h3>2. Ubicación del archivo de log</h3>";
$logFile = ini_get('error_log');
if ($logFile) {
    echo "Archivo de log configurado: $logFile<br>";
    echo "Archivo existe: " . (file_exists($logFile) ? '✅ SÍ' : '❌ NO') . "<br>";
    if (file_exists($logFile)) {
        echo "Archivo escribible: " . (is_writable($logFile) ? '✅ SÍ' : '❌ NO') . "<br>";
        echo "Tamaño: " . filesize($logFile) . " bytes<br>";
    }
} else {
    echo "No hay archivo de log configurado específicamente<br>";
}

echo "<h3>3. Ubicaciones comunes de logs en XAMPP</h3>";
$commonLogPaths = [
    'C:\\xampp\\php\\logs\\php_error_log',
    'C:\\xampp\\apache\\logs\\error.log',
    'C:\\xampp\\apache\\logs\\php_error.log',
    'C:\\WINDOWS\\Temp\\php_errors.log',
    'C:\\xampp\\tmp\\php_errors.log'
];

foreach ($commonLogPaths as $path) {
    echo "Verificando: $path<br>";
    if (file_exists($path)) {
        echo "  ✅ Existe - Tamaño: " . filesize($path) . " bytes<br>";
        echo "  Escribible: " . (is_writable($path) ? '✅ SÍ' : '❌ NO') . "<br>";
    } else {
        echo "  ❌ No existe<br>";
    }
}

echo "<h3>4. Test de escritura de log</h3>";
$testMessage = "=== TEST DE LOG - " . date('Y-m-d H:i:s') . " ===";
if (error_log($testMessage)) {
    echo "✅ Mensaje de test escrito en log<br>";
} else {
    echo "❌ Error escribiendo mensaje de test<br>";
}

echo "<h3>5. Información del sistema</h3>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "OS: " . PHP_OS . "<br>";
echo "Directorio actual: " . getcwd() . "<br>";
echo "Usuario del sistema: " . get_current_user() . "<br>";

echo "<h3>6. Variables de entorno relevantes</h3>";
echo "TEMP: " . (getenv('TEMP') ?: 'No disponible') . "<br>";
echo "TMP: " . (getenv('TMP') ?: 'No disponible') . "<br>";
echo "APPDATA: " . (getenv('APPDATA') ?: 'No disponible') . "<br>";
?>
