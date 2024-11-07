<?php
// Conexión a la base de datos
$servername1 = "localhost";
$username1 = "root";
$password1 = "";
$dbname = "registro";  // Base de datos donde está la tabla 'año'

$conexion = mysqli_connect($servername1, $username1, $password1, $dbname);
mysqli_set_charset($conexion, 'utf8mb4');

if (!$conexion) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}
?>
