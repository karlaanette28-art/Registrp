<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$ruta_csv = "../Empleados.csv";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_fila = $_POST['fila'] ?? 0;

    if (file_exists($ruta_csv)) {
        $filas = [];
        if (($gestor = fopen($ruta_csv, "r")) !== FALSE) {
            while (($data = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                $filas[] = $data;
            }
            fclose($gestor);
        }

        // Verificamos que la fila exista
        if (isset($filas[$num_fila])) {
            // Actualizamos dinámicamente cada columna enviada por el formulario
            foreach ($_POST as $clave => $valor) {
                if (strpos($clave, 'campo_') === 0) {
                    $indice_columna = str_replace('campo_', '', $clave);
                    $filas[$num_fila][$indice_columna] = trim($valor);
                }
            }
        }

        // Asegúrate de tener cerrado Excel en tu computadora antes de guardar
        $gestor = fopen($ruta_csv, "w");
        if ($gestor === FALSE) {
            die("Error: El archivo Empleados.csv está abierto en Excel. Ciérralo e inténtalo de nuevo.");
        }

        foreach ($filas as $fila) {
            if (!empty($fila)) {
                fputcsv($gestor, $fila);
            }
        }
        fclose($gestor);

        // Regresar a la tabla principal
        header("Location: ../vista/bases de registro.php");
        exit();
    }
} else {
    header("Location: ../vista/bases de registro.php");
    exit();
}
?>