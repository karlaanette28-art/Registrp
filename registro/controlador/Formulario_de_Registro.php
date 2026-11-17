<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../modelo/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. RECIBIR DATOS PERSONALES
    $Apellido_Paterno   = trim($_POST['Apellido_Paterno'] ?? '');
    $Apellido_Materno   = trim($_POST['Apellido_Materno'] ?? '');
    $Nombre             = trim($_POST['Nombre'] ?? '');
    $RFC                = trim($_POST['RFC'] ?? '');
    $CURP               = trim($_POST['CURP'] ?? '');
    $Fecha_de_Nacimiento = $_POST['Fecha_de_Nacimiento'] ?? '';
    $Escolaridad        = trim($_POST['Escolaridad'] ?? '');
    $Estado_Civil       = trim($_POST['Estado_Civil'] ?? '');
    $Colonia            = trim($_POST['Colonia'] ?? '');
    $Calle              = trim($_POST['Calle'] ?? '');
    $Municipio          = trim($_POST['Municipio'] ?? '');
    $Codigo_Postal      = trim($_POST['Codigo_Postal'] ?? '');
    $Telefono           = trim($_POST['Telefono'] ?? '');

    // 2. INSERT EN MYSQL (Tabla registro_personal)
    $insertar = "INSERT INTO registro_personal (
        Apellido_Paterno, Apellido_Materno, Nombre, RFC, CURP, 
        Fecha_de_Nacimiento, Escolaridad, Estado_Civil, Colonia, Calle, Municipio, Codigo_Postal, Telefono
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conexion, $insertar);

    if ($stmt === false) {
        die("Error en la consulta SQL: " . mysqli_error($conexion));
    }

    mysqli_stmt_bind_param($stmt, "sssssssssssss", 
        $Apellido_Paterno, $Apellido_Materno, $Nombre, $RFC, $CURP, 
        $Fecha_de_Nacimiento, $Escolaridad, $Estado_Civil, $Colonia, $Calle, $Municipio, $Codigo_Postal, $Telefono
    );
    
    $guardado_bd = mysqli_stmt_execute($stmt);
    $id_insertado = mysqli_insert_id($conexion);
    $_SESSION['id_empleado_actual'] = $id_insertado;

    if ($guardado_bd) {
        // ===================================================================
        // 3. GUARDAR EN EXCEL USANDO RUTA ABSOLUTA CON __DIR__
        // ===================================================================
        $archivo_csv = __DIR__ . "/../Empleados.csv"; 
        $nuevo_archivo = !file_exists($archivo_csv);
        
        $fp = fopen($archivo_csv, 'a');
        if ($fp) {
            // Si el archivo se crea por primera vez, ponemos los títulos de columnas
            if ($nuevo_archivo) {
                $encabezados = [
                    "id_Registro_personal", "Apellido_Paterno", "Apellido_Materno", "Nombre", 
                    "RFC", "CURP", "Fecha_de_Nacimiento", "Escolaridad", "Estado_Civil", 
                    "Colonia", "Calle", "Municipio", "Codigo_Postal", "Telefono", 
                    "ID_Empleado", "Genero", "ISSEMYM", "ID_Unidad_Org", "Unidad_Org", 
                    "Direccion", "ID_de_Plaza", "Tipo_de_Plaza", "Tipo_de_Servicio_Publico", 
                    "ID_Puesto", "Nombre_Puesto", "CCT", "Lugar_de_Trabajo", 
                    "Fecha_de_Ingreso_al_GEM", "Fecha_de_Quinsenas", "Fecha_de_Ultimo_Movimiento"
                ];
                fputcsv($fp, $encabezados);
            }

            // Fila inicial con datos personales (los campos laborales irán vacíos hasta el paso 2)
            $fila_datos = [
                $id_insertado,
                $Apellido_Paterno, 
                $Apellido_Materno, 
                $Nombre, 
                $RFC, 
                $CURP, 
                $Fecha_de_Nacimiento, 
                $Escolaridad, 
                $Estado_Civil, 
                $Colonia, 
                $Calle, 
                $Municipio, 
                $Codigo_Postal, 
                $Telefono,
                "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""
            ];

            fputcsv($fp, $fila_datos);
            fclose($fp);
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);

    // 4. REDIRECCIÓN
    if ($guardado_bd) {
        echo "<script>
            alert('¡Datos personales guardados en MySQL y Excel! Vamos al siguiente formulario.');
            window.location.href = '../vista/Registro laboral.html';
        </script>";
    }
}
?>