<?php
// fix.php
echo "<h1>Arreglando permisos...</h1>";

$target = 'img/covers';

// Intentamos cambiar el dueño al usuario actual (probablemente www-data)
// Esto puede fallar si no somos root, pero vale la pena intentar.
@chown($target, 'www-data');
@chgrp($target, 'www-data');

// EL IMPORTANTE: Dar permisos 777 (Lectura/Escritura total)
// Usamos chmod recursivo para la carpeta y todo lo de dentro
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target));

foreach($iterator as $item) {
    chmod($item, 0777);
}
// También a la carpeta principal
chmod($target, 0777);

echo "<p style='color:green'>✅ Permisos cambiados a 0777 en $target.</p>";
echo "<p>Ahora intenta abrir la imagen de Rosalía.</p>";
echo "<a href='catalogo.php'>Volver al catálogo</a>";
?>