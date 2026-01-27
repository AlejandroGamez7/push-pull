<?php
 
$host= getenv('MYSQLHOST');
$user= getenv('MYSQLUSER');
$pass= getenv('MYSQLPASSWORD');
$db= getenv('MYSQLDATABASE');

 
//Conexión
$conn = new mysqli($host, $user, $pass, $db);
 
//Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
 
 
?>