
<?php

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_de_datos = "registros"; // Pon el nombre exacto de la base de datos en MySQL

$conexion = mysqli_connect(
    $servidor,
    $usuario,
    $password,
    $base_de_datos 
);

// Verificar conexion
if (!$conexion) {
    die("Error de conexion: " . mysqli_connect_error());
}

// Configurar caracteres
mysqli_set_charset($conexion, "utf8mb4");

?>

