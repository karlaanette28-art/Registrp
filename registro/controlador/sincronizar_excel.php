<?php
require_once "../modelo/conexion.php";

// Ruta de tu archivo CSV/Excel principal en la raíz
$ruta_csv = "../registro.csv"; 

if (file_exists($ruta_csv)) {
    if (($gestor = fopen($ruta_csv, "r")) !== FALSE) {
        $fila_num = 0;
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
            // Omitir los encabezados de la fila 0
            if ($fila_num > 0) {
                // Ajusta los índices según las columnas de tu CSV
                $id = mysqli_real_escape_string($conexion, $datos[0] ?? '');
                $paterno = mysqli_real_escape_string($conexion, $datos[1] ?? '');
                $materno = mysqli_real_escape_string($conexion, $datos[2] ?? '');
                $nombre = mysqli_real_escape_string($conexion, $datos[3] ?? '');
                $rfc = mysqli_real_escape_string($conexion, $datos[4] ?? '');

                if (!empty($rfc)) {
                    // Verificar si el registro ya existe en MySQL
                    $sql_verificar = "SELECT * FROM registro_personal WHERE RFC = '$rfc'";
                    $resultado = mysqli_query($conexion, $sql_verificar);

                    // Validación de seguridad para que no truene si la consulta falla
                    if ($resultado === false) {
                        die("Error en la consulta SQL: " . mysqli_error($conexion));
                    }

                    if (mysqli_num_rows($resultado) > 0) {
                        // Si ya existe, se actualiza
                        $sql_actualizar = "UPDATE registro_personal SET Apellido_Paterno='$paterno', Apellido_Materno='$materno', Nombre='$nombre' WHERE RFC='$rfc'";
                        mysqli_query($conexion, $sql_actualizar);
                    } else {
                        // Si es nuevo, se inserta
                        $sql_insertar = "INSERT INTO registro_personal (Apellido_Paterno, Apellido_Materno, Nombre, RFC) VALUES ('$paterno', '$materno', '$nombre', '$rfc')";
                        mysqli_query($conexion, $sql_insertar);
                    }
                }
            }
            $fila_num++;
        }
        fclose($gestor);
    }
}

// Redireccionar de vuelta a la página de bases de datos
header("Location: ../vista/bases de registro.php?sincronizado=ok");
exit();
?>