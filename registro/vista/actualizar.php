<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $archivo_param = $_POST['archivo'] ?? '';
    $fila_idx = intval($_POST['fila'] ?? 0);
    $nuevos_datos = $_POST['campo'] ?? [];

    // Definir la ruta del CSV según el archivo seleccionado
    $ruta_csv = "";
    if ($archivo_param === 'Empleados') {
        $ruta_csv = "../Empleados.csv";
    } elseif ($archivo_param === 'registro') {
        $ruta_csv = "../registro.csv";
    } elseif ($archivo_param === 'laboral') {
        $ruta_csv = "../laboral.csv";
    }

    if (!empty($ruta_csv) && file_exists($ruta_csv)) {
        // Leer todas las líneas del archivo CSV
        $lineas = [];
        if (($gestor = fopen($ruta_csv, "r")) !== FALSE) {
            while (($data = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                $lineas[] = $data;
            }
            fclose($gestor);
        }

        // Modificar la línea exacta si existe
        if (isset($lineas[$fila_idx])) {
            // Reordenar los datos del formulario en un array plano
            ksort($nuevos_datos);
            $lineas[$fila_idx] = array_values($nuevos_datos);

            // Sobrescribir el archivo CSV con los datos actualizados
            if (($gestor = fopen($ruta_csv, "w")) !== FALSE) {
                foreach ($lineas as $linea) {
                    fputcsv($gestor, $linea);
                }
                fclose($gestor);
            }
        }
    }

    // Redireccionar de vuelta a la tabla principal
    header("Location: bases de registro.php");
    exit();
}