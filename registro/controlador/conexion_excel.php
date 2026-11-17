<?php
// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$pass = ''; // Tu contraseña de MySQL si la tienes
$db   = 'registros';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar encabezados para descargar archivo Excel (.csv)
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reporte_empleados_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');
// Para que Excel reconozca los acentos y la Ñ correctamente
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Obtener los datos de la vista recién creada
$result = $conn->query("SELECT * FROM vista_empleados_completos");

// Escribir los nombres de las columnas
if ($row = $result->fetch_assoc()) {
    fputcsv($output, array_keys($row));
    fputcsv($output, $row);
}

// Escribir el resto de las filas
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>