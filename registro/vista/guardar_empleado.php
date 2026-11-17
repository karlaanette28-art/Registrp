<?php
// 1. Conectamos a la base de datos de XAMPP
require_once "../modelo/conexion.php";

// 2. Verificamos que los datos vengan por POST desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Limpiamos y recibimos los datos de cada input
    $id_empleado  = mysqli_real_escape_string($conexion, $_POST['id_empleado'] ?? '');
    $rfc          = mysqli_real_escape_string($conexion, $_POST['rfc'] ?? '');
    $curp         = mysqli_real_escape_string($conexion, $_POST['curp'] ?? '');
    $nombre       = mysqli_real_escape_string($conexion, $_POST['nombre'] ?? '');
    $f_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento'] ?? '');
    $escolaridad  = mysqli_real_escape_string($conexion, $_POST['escolaridad'] ?? '');
    $estado_civil = mysqli_real_escape_string($conexion, $_POST['estado_civil'] ?? '');
    $colonia      = mysqli_real_escape_string($conexion, $_POST['colonia'] ?? '');

    if (!empty($id_empleado)) {
        // 4. Insertamos en la tabla empleados de MySQL (si ya existe el ID, lo actualiza)
        $sql = "INSERT INTO empleados (id_empleado, rfc, curp, nombre, fecha_nacimiento, escolaridad, estado_civil, colonia) 
                VALUES ('$id_empleado', '$rfc', '$curp', '$nombre', '$f_nacimiento', '$escolaridad', '$estado_civil', '$colonia')
                ON DUPLICATE KEY UPDATE rfc='$rfc', curp='$curp', nombre='$nombre', fecha_nacimiento='$f_nacimiento', escolaridad='$escolaridad', estado_civil='$estado_civil', colonia='$colonia'";
                
        if (mysqli_query($conexion, $sql)) {
            // 5. (Opcional pero recomendado) Guardar una copia exacta en el archivo CSV para que Excel también lo tenga
            $ruta_csv = "../Empleados.csv";
            $nuevo_registro = [$id_empleado, $rfc, $curp, $nombre, $f_nacimiento, $escolaridad, $estado_civil, $colonia];
            $fp = fopen($ruta_csv, 'a');
            fputcsv($fp, $nuevo_registro);
            fclose($fp);

            // Redirigir de regreso a tu tabla general con éxito
            header("Location: ../vista/bases de registro.php?guardado=ok");
            exit();
        } else {
            echo "Error al guardar en la base de datos: " . mysqli_error($conexion);
        }
    } else {
        echo "El ID de empleado es obligatorio.";
    }
}
?>