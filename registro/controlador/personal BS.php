<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recopilar los datos del formulario (ajustados al orden de tus columnas)
    $apellido_paterno = $_POST['Apellido_Paterno'] ?? '';
    $apellido_materno = $_POST['Apellido_Materno'] ?? '';
    $nombre = $_POST['Nombre'] ?? '';
    $nombre_completo = trim("$nombre $apellido_paterno $apellido_materno");
    
    $rfc = strtoupper($_POST['RFC'] ?? '');
    $curp = strtoupper($_POST['CURP'] ?? '');
    $fecha_nacimiento = $_POST['Fecha_de_Nacimiento'] ?? '';
    $escolaridad = $_POST['Escolaridad'] ?? '';
    $estado_civil = $_POST['Estado_Civil'] ?? '';
    $colonia = $_POST['Colonia'] ?? '';
    $calle = $_POST['Calle'] ?? '';
    $municipio = $_POST['Municipio'] ?? '';
    $codigo_postal = $_POST['Codigo_Postal'] ?? '';
    $telefono = $_POST['Telefono'] ?? '';

    // Nueva fila para el CSV (puedes ajustar los campos vacíos si tienes más columnas laborales)
    $nueva_fila = [
        "", // ID Empleado (se puede autogenerar o dejar vacío)
        $rfc,
        $curp,
        $nombre_completo,
        $fecha_nacimiento,
        $escolaridad,
        $estado_civil,
        $colonia,
        $calle,
        $municipio,
        $codigo_postal,
        $telefono
    ];

    // Ruta del archivo CSV principal
    $ruta_csv = "../ingresos.csv";

    // Abrir el archivo en modo 'append' (añadir al final)
    if (($gestor = fopen($ruta_csv, "a")) !== FALSE) {
        fputcsv($gestor, $nueva_fila);
        fclose($gestor);
        
        // Redirigir de vuelta a la tabla principal
        header("Location: ../vista/base de registros.php?exito=1");
        exit();
    } else {
        echo "Error al abrir la base de datos.";
    }
}
?>