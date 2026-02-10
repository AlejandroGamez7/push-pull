<?php
 
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db = getenv('MYSQLDATABASE');

// Si no existen las variables de entorno (estamos en local/XAMPP), usamos los valores por defecto
if (!$host) {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'push&pull'; // <--- CAMBIA ESTO SI TU BASE DE DATOS TIENE OTRO NOMBRE EN LOCAL
}

//Conexión
$conn = new mysqli($host, $user, $pass, $db);

//Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
 
 
 
?>