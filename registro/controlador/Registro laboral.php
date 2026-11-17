<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../modelo/conexion.php";

mysqli_report(MYSQLI_REPORT_OFF);

// 1. RECIBIR Y LIMPIAR DATOS LABORALES
$ID_Empleado              = $_SESSION['id_empleado_actual'] ?? ($_POST['ID_Empleado'] ?? null);
$Genero                   = trim($_POST['Genero'] ?? '');
$ISSEMYM                  = trim($_POST['ISSEMYM'] ?? '');

$ID_Unidad_Org            = $_POST['ID_Unidad_Org'] ?? null;
$Unidad_Org               = trim($_POST['Unidad_ORG'] ?? $_POST['Unidad_Org'] ?? $_POST['Unidad_ORG_input'] ?? '');
$Direccion                = trim($_POST['Direccion'] ?? $_POST['Direccion_de_area_de_trabajo_(texto)'] ?? '');

$ID_de_Plaza              = $_POST['ID_de_Plaza'] ?? null;
$Tipo_de_Plaza            = trim($_POST['tipo_plaza'] ?? $_POST['Tipo_de_Plaza'] ?? '');
$Tipo_de_Servicio_Publico = trim($_POST['tipo_servidor'] ?? $_POST['Tipo_de_Servicio_Publico'] ?? '');

$ID_Puesto                = $_POST['ID_Puesto'] ?? null;
$Nombre_Puesto            = trim($_POST['Nombre_Puesto'] ?? $_POST['nombre_puesto'] ?? ''); 
$CCT                      = trim($_POST['CCT'] ?? '');
$Lugar_de_Trabajo         = trim($_POST['lugar_trabajo'] ?? $_POST['Lugar_de_Trabajo'] ?? '');

$Fecha_de_Ingreso_al_GEM  = $_POST['Fecha_de_Ingreso_al_GEM'] ?? null;
$Fecha_de_Quinsenas       = $_POST['Fecha_de_Quinsenas'] ?? $_POST['Fecha_de_Paga'] ?? null;
$Fecha_de_Ultimo_Movimiento = $_POST['Fecha_de_Ultimo_Movimiento'] ?? null;

// Conversión limpia de numéricos
$ID_Empleado   = ($ID_Empleado !== null && $ID_Empleado !== '') ? (int)$ID_Empleado : null;
$ID_Unidad_Org = ($ID_Unidad_Org !== null && $ID_Unidad_Org !== '') ? (int)$ID_Unidad_Org : null;
$ID_de_Plaza   = ($ID_de_Plaza !== null && $ID_de_Plaza !== '') ? (int)$ID_de_Plaza : null;
$ID_Puesto     = ($ID_Puesto !== null && $ID_Puesto !== '') ? (int)$ID_Puesto : null;

// 2. INSERT EN MYSQL (Tabla laboral)
$insertar = "INSERT INTO laboral (
    ID_Empleado, Genero, ISSEMYM, ID_Unidad_Org, 
    Unidad_Org, Direccion, ID_de_Plaza, Tipo_de_Plaza, Tipo_de_Servicio_Publico, 
    ID_Puesto, Nombre_Puesto, CCT, Lugar_de_Trabajo, 
    Fecha_de_Ingreso_al_GEM, Fecha_de_Quinsenas, Fecha_de_Ultimo_Movimiento
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexion, $insertar);
$error_mysql = "";

if ($stmt) {
    mysqli_stmt_bind_param(
        $stmt, 
        "isisssssisssssss", 
        $ID_Empleado, $Genero, $ISSEMYM, $ID_Unidad_Org, $Unidad_Org, 
        $Direccion, $ID_de_Plaza, $Tipo_de_Plaza, $Tipo_de_Servicio_Publico, 
        $ID_Puesto, $Nombre_Puesto, $CCT, $Lugar_de_Trabajo, 
        $Fecha_de_Ingreso_al_GEM, $Fecha_de_Quinsenas, $Fecha_de_Ultimo_Movimiento
    );

    $guardado_bd = mysqli_stmt_execute($stmt);
    
    if ($guardado_bd) {
        // ===================================================================
        // 3. ACTUALIZAR O ANEXAR EL REGISTRO COMPLETO EN EMPLEADOS.CSV USANDO __DIR__
        // ===================================================================
        $id_buscado = $ID_Empleado;
        $query_unido = "SELECT 
            p.id_Registro_personal, p.Apellido_Paterno, p.Apellido_Materno, p.Nombre, 
            p.RFC, p.CURP, p.Fecha_de_Nacimiento, p.Escolaridad, p.Estado_Civil, 
            p.Colonia, p.Calle, p.Municipio, p.Codigo_Postal, p.Telefono, 
            l.ID_Empleado, l.Genero, l.ISSEMYM, l.ID_Unidad_Org, l.Unidad_Org, 
            l.Direccion, l.ID_de_Plaza, l.Tipo_de_Plaza, l.Tipo_de_Servicio_Publico, 
            l.ID_Puesto, l.Nombre_Puesto, l.CCT, l.Lugar_de_Trabajo, 
            l.Fecha_de_Ingreso_al_GEM, l.Fecha_de_Quinsenas, l.Fecha_de_Ultimo_Movimiento
        FROM registro_personal p 
        LEFT JOIN laboral l ON p.id_Registro_personal = l.ID_Empleado 
        WHERE p.id_Registro_personal = $id_buscado 
        LIMIT 1";

        $resultado = mysqli_query($conexion, $query_unido);
        $datos = ($resultado) ? mysqli_fetch_assoc($resultado) : null;

        $archivo_empleados = __DIR__ . "/../Empleados.csv";
        $nuevo = !file_exists($archivo_empleados);

        $fp = fopen($archivo_empleados, 'a');
        if ($fp) {
            if ($nuevo) {
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

            if ($datos) {
                $fila_limpia = [
                    $datos['id_Registro_personal'] ?? '',
                    $datos['Apellido_Paterno'] ?? '',
                    $datos['Apellido_Materno'] ?? '',
                    $datos['Nombre'] ?? '',
                    $datos['RFC'] ?? '',
                    $datos['CURP'] ?? '',
                    $datos['Fecha_de_Nacimiento'] ?? '',
                    $datos['Escolaridad'] ?? '',
                    $datos['Estado_Civil'] ?? '',
                    $datos['Colonia'] ?? '',
                    $datos['Calle'] ?? '',
                    $datos['Municipio'] ?? '',
                    $datos['Codigo_Postal'] ?? '',
                    $datos['Telefono'] ?? '',
                    $datos['ID_Empleado'] ?? '',
                    $datos['Genero'] ?? '',
                    $datos['ISSEMYM'] ?? '',
                    $datos['ID_Unidad_Org'] ?? '',
                    $datos['Unidad_Org'] ?? '',
                    $datos['Direccion'] ?? '',
                    $datos['ID_de_Plaza'] ?? '',
                    $datos['Tipo_de_Plaza'] ?? '',
                    $datos['Tipo_de_Servicio_Publico'] ?? '',
                    $datos['ID_Puesto'] ?? '',
                    $datos['Nombre_Puesto'] ?? '',
                    $datos['CCT'] ?? '',
                    $datos['Lugar_de_Trabajo'] ?? '',
                    $datos['Fecha_de_Ingreso_al_GEM'] ?? '',
                    $datos['Fecha_de_Quinsenas'] ?? '',
                    $datos['Fecha_de_Ultimo_Movimiento'] ?? ''
                ];
                fputcsv($fp, $fila_limpia);
            }
            fclose($fp);
        }

    } else {
        $error_mysql = mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
    $guardado_bd = false;
    $error_mysql = mysqli_error($conexion);
}

mysqli_close($conexion);

// 4. RESPUESTA Y REDIRECCIÓN
if ($guardado_bd) {
    unset($_SESSION['id_empleado_actual']);
    echo "<script>
        alert('¡Registro completo guardado en MySQL y sincronizado en Empleados.csv!');
        window.location.href = '../vista/bases de registro.php';
    </script>";
} else {
    $error_msj = addslashes($error_mysql);
    echo "<script>
        alert('Error al guardar: $error_msj');
        window.history.back();
    </script>";
}
?>