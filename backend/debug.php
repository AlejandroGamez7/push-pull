<?php
echo "<h1>Diagnóstico de Archivos</h1>";

// 1. ¿Dónde estoy?
echo "<p><strong>Directorio actual (CWD):</strong> " . getcwd() . "</p>";

// 2. ¿Existe la carpeta?
$ruta_carpeta = 'img/covers';
if (is_dir($ruta_carpeta)) {
    echo "<p style='color:green'>✅ La carpeta '$ruta_carpeta' existe.</p>";
    
    // 3. ¿Qué permisos tiene?
    echo "<p><strong>Permisos:</strong> " . substr(sprintf('%o', fileperms($ruta_carpeta)), -4) . "</p>";
    echo "<p><strong>Dueño (ID):</strong> " . fileowner($ruta_carpeta) . "</p>";
    
    // 4. ¿Qué hay dentro?
    $archivos = scandir($ruta_carpeta);
    echo "<h3>Archivos encontrados en la carpeta:</h3>";
    echo "<ul>";
    foreach ($archivos as $archivo) {
        if ($archivo != "." && $archivo != "..") {
            echo "<li>📄 $archivo (Tamaño: " . filesize($ruta_carpeta . '/' . $archivo) . " bytes)</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p style='color:red'>❌ La carpeta '$ruta_carpeta' NO existe o no es accesible.</p>";
    echo "<p>Intenta subir por FTP o crearla, o revisa el Volumen de Railway.</p>";
}
?>