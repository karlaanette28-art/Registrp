<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "registros");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Nombre del archivo que se va a descargar
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Reporte_Empleados_Completos.csv');

$salida = fopen('php://output', 'w');

// Hace que Excel reconozca los acentos y la letra Ñ correctamente
fprintf($salida, chr(0xEF).chr(0xBB).chr(0xBF));

// Consulta SQL que Junta las dos tablas automáticamente por el ID del empleado
$query = "SELECT 
    p.id_Registro_personal,
    p.Nombre,
    p.Apellido_Paterno,
    p.Apellido_Materno,
    p.RFC,
    p.CURP,
    p.Fecha_de_Nacimiento,
    p.Escolaridad,
    p.Estado_Civil,
    p.Colonia,
    p.Calle,
    p.Municipio,
    p.Codigo_Postal,
    p.Telefono,
    l.id_Laboral,
    l.Genero,
    l.ISSEMYM,
    l.Clave_ISSEMYM,
    l.ID_Unidad_Org,
    l.Unidad_Org,
    l.Direccion,
    l.ID_de_Plaza,
    l.Tipo_de_Plaza,
    l.Tipo_de_Servicio_Publico,
    l.ID_Puesto,
    l.CCT,
    l.Lugar_de_Trabajo,
    l.Fecha_de_Ingreso_al_GEM,
    l.Fecha_de_Paga
FROM registro_personal p
INNER JOIN laboral l ON p.id_Registro_personal = l.ID_Empleado";

$resultado = $conexion->query($query);

// Escribe los títulos de las columnas en la primera fila
if ($fila = $resultado->fetch_assoc()) {
    fputcsv($salida, array_keys($fila), ";");
    fputcsv($salida, $fila, ";");
}

// Escribe los datos de cada empleado
while ($fila = $resultado->fetch_assoc()) {
    fputcsv($salida, $fila, ";");
}

fclose($salida);
exit();
?>