<?php
include 'conexion.php';

$sql = file_get_contents('setup_opiniones.sql');

if ($conn->multi_query($sql)) {
    echo "<h1>¡Éxito!</h1>";
    echo "<p>La tabla 'opiniones' se ha creado correctamente y se han insertado los datos de prueba.</p>";
    echo "<p><a href='catalogo.php'>Volver al Catálogo</a></p>";
    
    // Consumir todos los resultados para evitar errores de sincronización
    do {
        if ($res = $conn->store_result()) {
            $res->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    
} else {
    echo "<h1>Error</h1>";
    echo "<p>No se pudo crear la tabla: " . $conn->error . "</p>";
}
?>
