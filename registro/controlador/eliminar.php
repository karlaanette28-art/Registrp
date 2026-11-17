<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../modelo/conexion.php";

if (isset($_GET['id'])) {
    $id_a_eliminar = intval($_GET['id']);

    // 1. Eliminar de la base de datos MySQL (primero la tabla laboral por la relación y luego registro_personal)
    mysqli_query($conexion, "DELETE FROM laboral WHERE ID_Empleado = $id_a_eliminar");
    $eliminado_bd = mysqli_query($conexion, "DELETE FROM registro_personal WHERE id_Registro_personal = $id_a_eliminar");

    if ($eliminado_bd) {
        // 2. Eliminar la fila correspondiente en Empleados.csv buscándola por su ID unificado (columna 0)
        $ruta_csv = "../Empleados.csv";

        if (file_exists($ruta_csv)) {
            $filas = [];
            
            if (($gestor = fopen($ruta_csv, "r")) !== FALSE) {
                while (($data = fgetcsv($gestor, 2000, ",")) !== FALSE) {
                    $filas[] = $data;
                }
                fclose($gestor);
            }

            $gestor = @fopen($ruta_csv, "w");
            if ($gestor !== FALSE) {
                foreach ($filas as $fila) {
                    if (!empty($fila) && isset($fila[0]) && trim($fila[0]) == $id_a_eliminar) {
                        continue; // Omitimos la fila borrada
                    }
                    fputcsv($gestor, $fila);
                }
                fclose($gestor);
            }
        }
    }
}

mysqli_close($conexion);

// 3. Redirección inteligente: si se pasó un ancla o ID, regresa exactamente a esa altura de la tabla
$ancla = isset($_GET['id']) ? "#fila-" . intval($_GET['id']) : "#tablaUnificadaSeccion";

// Si prefieres que se quede anclado directo en la sección general de la tabla, puedes usar:
header("Location: ../vista/bases de registro.php#tablaUnificadaSeccion");
exit();
?>