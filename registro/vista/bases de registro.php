<?php
// ==========================================
// 0. CONEXIÓN Y LÓGICA DE SINCRONIZACIÓN ÚNICA
// ==========================================
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "registros";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Error crítico de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

function corregirFecha($fecha) {
    $fecha = trim($fecha);
    if (empty($fecha) || $fecha == '0000-00-00' || $fecha == '-') return NULL;
    $partes = explode('/', $fecha);
    if (count($partes) === 3) {
        return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
    return $fecha;
}

if (isset($_GET['sincronizar']) && $_GET['sincronizar'] === 'true') {
    // Usamos EXCLUSIVAMENTE Empleados.csv para todo
    $ruta_empleados = "../Empleados.csv";
    
    if (file_exists($ruta_empleados)) {
        if (($gestor = fopen($ruta_empleados, "r")) !== FALSE) {
            $encabezados = fgetcsv($gestor, 3000, ","); 
            if (isset($encabezados[0])) {
                $encabezados[0] = str_replace("\xEF\xBB\xBF", "", $encabezados[0]);
            }
            $encabezados_limpios = array_map('trim', $encabezados);

            while (($fila = fgetcsv($gestor, 3000, ",")) !== FALSE) {
                if (count($fila) !== count($encabezados_limpios)) continue;
                $datos = array_combine($encabezados_limpios, $fila);

                $obtenerValor = function($claves) use ($datos) {
                    foreach ((array)$claves as $clave) {
                        foreach ($datos as $k => $v) {
                            if (strcasecmp(trim($k), trim($clave)) === 0) return $v;
                        }
                    }
                    return '';
                };

                // Datos Personales
                $id_emp          = mysqli_real_escape_string($conexion, trim($obtenerValor(['ID_Empleado', 'ID Empleado', 'id_empleado'])));
                $rfc             = mysqli_real_escape_string($conexion, $obtenerValor('RFC'));
                $curp            = mysqli_real_escape_string($conexion, $obtenerValor('CURP'));
                $ap_paterno      = mysqli_real_escape_string($conexion, $obtenerValor(['Apellido_Paterno', 'Apellido Paterno']));
                $ap_materno      = mysqli_real_escape_string($conexion, $obtenerValor(['Apellido_Materno', 'Apellido Materno']));
                $nombre          = mysqli_real_escape_string($conexion, $obtenerValor('Nombre'));
                $f_nacimiento    = corregirFecha($obtenerValor(['Fecha de Nacimiento', 'Fecha_de_Nacimiento']));
                $escolaridad     = mysqli_real_escape_string($conexion, $obtenerValor('Escolaridad'));
                $estado_civil    = mysqli_real_escape_string($conexion, $obtenerValor(['Estado_Civil', 'Estado Civil']));
                $colonia         = mysqli_real_escape_string($conexion, $obtenerValor('Colonia'));
                $calle           = mysqli_real_escape_string($conexion, $obtenerValor('Calle'));
                $municipio       = mysqli_real_escape_string($conexion, $obtenerValor('Municipio'));
                $cp              = mysqli_real_escape_string($conexion, $obtenerValor(['Codigo_Postal', 'Codigo Postal']));
                $telefono        = mysqli_real_escape_string($conexion, $obtenerValor('Telefono'));
                
                // Datos Laborales (sacados del mismo Empleados.csv)
                $issewym         = mysqli_real_escape_string($conexion, $obtenerValor(['Clave ISSEMYM', 'ISSEMYM', 'Clave_ISSEMYM']));
                $f_ingreso_gem   = corregirFecha($obtenerValor(['Fecha_de_Ingreso_al_GEM', 'Fecha de Ingreso al GEM']));
                $f_ultimo_mov    = corregirFecha($obtenerValor(['Fecha ultimo Movimiento', 'Fecha_de_Ultimo_Movimiento', 'Fecha Ultimo Movimiento']));
                $genero          = mysqli_real_escape_string($conexion, $obtenerValor(['Genero', 'Género']));
                
                $id_unidad_org   = mysqli_real_escape_string($conexion, $obtenerValor(['ID_Unidad_Org', 'ID Unidad Org']));
                $unidad_org      = mysqli_real_escape_string($conexion, $obtenerValor(['Unidad_Org', 'Unidad Org']));
                $direccion_lab   = mysqli_real_escape_string($conexion, $obtenerValor(['Direccion', 'Direccion_Laboral', 'Dirección']));
                
                $id_plaza_crudo  = $obtenerValor(['ID_de_Plaza', 'ID de Plaza', 'ID_Plaza']);
                $id_plaza        = mysqli_real_escape_string($conexion, trim(str_replace("'", "", $id_plaza_crudo)));

                $tipo_serv_pub   = mysqli_real_escape_string($conexion, $obtenerValor(['Tipo_Servidor_Publico', 'Tipo Servidor Publico']));
                $tipo_plaza      = mysqli_real_escape_string($conexion, $obtenerValor(['Tipo_Plaza _GENERAL/CONFIANZA', 'Tipo_Plaza', 'Tipo Plaza']));
                $id_puesto       = mysqli_real_escape_string($conexion, $obtenerValor(['ID_Puesto', 'ID Puesto']));
                $nombre_puesto   = mysqli_real_escape_string($conexion, $obtenerValor(['Nombre Puesto', 'Nombre_Puesto']));
                $cct             = mysqli_real_escape_string($conexion, $obtenerValor('CCT'));
                $lugar_trabajo   = mysqli_real_escape_string($conexion, $obtenerValor(['Lugar_de_Trabajo', 'Lugar de Trabajo']));
                
                $val_quinsenas   = $obtenerValor(['Fecha_de_Quinsenas', 'Fecha_de_Quincenas', 'Fecha_Quinsenas', 'Fecha_Quincenas']);
                $f_quinsenas     = corregirFecha($val_quinsenas);

                if (!empty($id_emp)) {
                    // 1. Guardar o actualizar en registro_personal
                    $sql_sync_p = "INSERT INTO registro_personal 
                                (id_Registro_personal, Apellido_Paterno, Apellido_Materno, Nombre, RFC, CURP, Fecha_de_Nacimiento, Escolaridad, Estado_Civil, Colonia, Calle, Municipio, Codigo_Postal, Telefono) 
                                 VALUES 
                                ('$id_emp', '$ap_paterno', '$ap_materno', '$nombre', '$rfc', '$curp', " . ($f_nacimiento ? "'$f_nacimiento'" : "NULL") . ", '$escolaridad', '$estado_civil', '$colonia', '$calle', '$municipio', '$cp', '$telefono') 
                                 ON DUPLICATE KEY UPDATE 
                                 Apellido_Paterno='$ap_paterno', Apellido_Materno='$ap_materno', Nombre='$nombre', RFC='$rfc', CURP='$curp', Fecha_de_Nacimiento=" . ($f_nacimiento ? "'$f_nacimiento'" : "NULL") . ", Escolaridad='$escolaridad', Estado_Civil='$estado_civil', Colonia='$colonia', Calle='$calle', Municipio='$municipio', Codigo_Postal='$cp', Telefono='$telefono'";
                    mysqli_query($conexion, $sql_sync_p);

                    // 2. Guardar o actualizar en laboral
                    $sql_sync_l = "INSERT INTO laboral 
                                (ID_Empleado, Genero, ISSEMYM, ID_Unidad_Org, Unidad_Org, Direccion, ID_de_Plaza, Tipo_de_Plaza, Tipo_de_Servicio_Publico, ID_Puesto, Nombre_Puesto, CCT, Lugar_de_Trabajo, Fecha_de_Ingreso_al_GEM, Fecha_de_Quinsenas, Fecha_de_Ultimo_Movimiento) 
                                 VALUES 
                                ('$id_emp', '$genero', '$issewym', '$id_unidad_org', '$unidad_org', '$direccion_lab', '$id_plaza', '$tipo_plaza', '$tipo_serv_pub', '$id_puesto', '$nombre_puesto', '$cct', '$lugar_trabajo', " . ($f_ingreso_gem ? "'$f_ingreso_gem'" : "NULL") . ", " . ($f_quinsenas ? "'$f_quinsenas'" : "NULL") . ", " . ($f_ultimo_mov ? "'$f_ultimo_mov'" : "NULL") . ")
                                 ON DUPLICATE KEY UPDATE 
                                 Genero='$genero', ISSEMYM='$issewym', ID_Unidad_Org='$id_unidad_org', Unidad_Org='$unidad_org', Direccion='$direccion_lab', ID_de_Plaza='$id_plaza', Tipo_de_Plaza='$tipo_plaza', Tipo_de_Servicio_Publico='$tipo_serv_pub', ID_Puesto='$id_puesto', Nombre_Puesto='$nombre_puesto', CCT='$cct', Lugar_de_Trabajo='$lugar_trabajo', Fecha_de_Ingreso_al_GEM=" . ($f_ingreso_gem ? "'$f_ingreso_gem'" : "NULL") . ", Fecha_de_Quinsenas=" . ($f_quinsenas ? "'$f_quinsenas'" : "NULL") . ", Fecha_de_Ultimo_Movimiento=" . ($f_ultimo_mov ? "'$f_ultimo_mov'" : "NULL");
                    mysqli_query($conexion, $sql_sync_l);
                }
            }
            fclose($gestor);
        }
    }

    header("Location: bases de registro.php?sincronizado=ok#tablaUnificadaSeccion");
    exit();
}

// ==========================================
// 1. PROCESAR GUARDADO DE CAMBIOS
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar_mysql_unificado') {
    $id_registro = mysqli_real_escape_string($conexion, $_POST['id_registro'] ?? '');
    $nuevos_datos = $_POST['campo'] ?? [];

    if (!empty($id_registro) && !empty($nuevos_datos)) {
        $sets_personal = [];
        $sets_laboral = [];

        $cols_pers = [];
        $res_cp = mysqli_query($conexion, "SHOW COLUMNS FROM registro_personal");
        while($row = mysqli_fetch_assoc($res_cp)) { $cols_pers[] = $row['Field']; }

        foreach ($nuevos_datos as $columna => $valor) {
            $col_limpia = mysqli_real_escape_string($conexion, $columna);
            $val_limpio = mysqli_real_escape_string($conexion, $valor);

            if (in_array($columna, $cols_pers)) {
                $sets_personal[] = "$col_limpia = '$val_limpio'";
            } else {
                $col_real = ($columna === 'Direccion_Laboral') ? 'Direccion' : $columna;
                $sets_laboral[] = "`$col_real` = '$val_limpio'";
            }
        }

        if (!empty($sets_personal)) {
            mysqli_query($conexion, "UPDATE registro_personal SET " . implode(", ", $sets_personal) . " WHERE id_Registro_personal = '$id_registro'");
        }
        if (!empty($sets_laboral)) {
            mysqli_query($conexion, "UPDATE laboral SET " . implode(", ", $sets_laboral) . " WHERE ID_Empleado = '$id_registro'");
        }
    }

    header("Location: bases de registro.php#tablaUnificadaSeccion");
    exit();
}

// ==========================================
// 2. LÓGICA DE EDICIÓN
// ==========================================
$editando_mysql = false;
$datos_mysql_editar = [];
$id_mysql_editar = '';

if (isset($_GET['editar_mysql'])) {
    $id_mysql_editar = mysqli_real_escape_string($conexion, $_GET['editar_mysql']);
    $res_edit_m = mysqli_query($conexion, "SELECT rp.*, l.* FROM registro_personal rp LEFT JOIN laboral l ON rp.id_Registro_personal = l.ID_Empleado WHERE rp.id_Registro_personal = '$id_mysql_editar'");
    if ($res_edit_m && mysqli_num_rows($res_edit_m) > 0) {
        $datos_mysql_editar = mysqli_fetch_assoc($res_edit_m);
        $editando_mysql = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base de Datos - Servidores Públicos</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
        header { background-color: #800020; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; }
        .navbar .logo { color: #ffffff; font-weight: bold; text-decoration: none; font-size: 1.2rem; }
        .browser-menu { display: flex; gap: 20px; }
        .browser-menu a { color: #ecf0f1; text-decoration: none; font-weight: 500; }
        .browser-menu a:hover { color: #ffc107; }
        .top-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-container h2 { color: #333; margin: 0; }
        .btn-sincronizar { background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .btn-sincronizar:hover { background-color: #218838; }
        .alerta-exito { background-color: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; font-weight: bold; }
        .panel-impresion { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .botones-sup { display: flex; gap: 15px; align-items: center; margin-bottom: 15px; }
        .btn-imprimir { background-color: #28a745; color: white; padding: 10px 18px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 14px; }
        .btn-imprimir:hover { background-color: #218838; }
        .btn-accion-col { background-color: #6c757d; color: white; padding: 6px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; }
        .btn-accion-col:hover { background-color: #5a6268; }
        .columnas-selector { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 8px; background: #fdfdfd; padding: 12px; border: 1px solid #ddd; border-radius: 6px; max-height: 150px; overflow-y: auto; }
        .columnas-selector label { font-size: 12px; color: #333; cursor: pointer; display: flex; align-items: center; gap: 6px; }
        .btn-tabla-header { background: #a52a2a; color: white; border: 1px solid #ffcccc; padding: 2px 6px; font-size: 10px; border-radius: 3px; cursor: pointer; margin-left: 4px; font-weight: bold; }
        .btn-tabla-header:hover { background: #800000; }
        .seccion-edicion { background: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(128, 0, 32, 0.2); margin-bottom: 25px; border-left: 6px solid #800020; scroll-margin-top: 20px; }
        .seccion-edicion h3 { color: #800020; margin-top: 0; margin-bottom: 15px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; margin-bottom: 15px; }
        .form-group label { display: block; font-size: 12px; font-weight: bold; color: #555; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 13px; }
        .form-actions { display: flex; gap: 10px; }
        .btn-actualizar { background-color: #800020; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; }
        .btn-actualizar:hover { background-color: #50121f; }
        .btn-cancelar { background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; }
        .btn-cancelar:hover { background-color: #5a6268; }
        .seccion-tabla { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-top: 25px; }
        .buscador-container { margin-bottom: 15px; }
        .input-buscador { width: 100%; max-width: 350px; padding: 10px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline: none; transition: border-color 0.3s; }
        .input-buscador:focus { border-color: #8b0000; box-shadow: 0 0 5px rgba(139, 0, 0, 0.2); }
        .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #e0e0e0; border-radius: 6px; max-height: 550px; }
        
        table { width: 100%; border-collapse: collapse; table-layout: auto; }
        th, td { white-space: normal; word-break: break-word; min-width: 130px; }

        th { 
            background-color: #800020; 
            color: white; 
            padding: 12px 15px; 
            text-align: left; 
            font-size: 14px; 
            position: sticky; 
            top: 0; 
            z-index: 90; 
            user-select: none;
        }
        
        .resizer {
            position: absolute;
            top: 0;
            right: 0;
            width: 5px;
            cursor: col-resize;
            user-select: none;
            height: 100%;
        }
        .resizer:hover, .resizing {
            background-color: rgba(255, 255, 255, 0.5);
        }

        td { padding: 10px 15px; color: #333; font-size: 13px; border-bottom: 1px solid #e0e0e0; }
        tr:nth-child(even) { background-color: #fcfcfc; }
        tr:hover { background-color: #f1f1f1; }
        
        tr.fila-seleccionada {
            background-color: #fff9c4 !important;
        }

        h3 { color: #800020; border-bottom: 2px solid #eee; padding-bottom: 8px; margin-top: 0; }
    </style>
</head>
<body>

    <header>
        <nav class="navbar">
            <a href="bases de registro.php" class="logo">📊 Sistema de Ingresos</a>
            <div class="browser-menu" id="nav-links">
                <a href="inicio.html">Inicio</a>
                <a href="Registro de personas.html">Registro de personas</a>
                <a href="bases de registro.php">Bases de datos</a>
            </div>
        </nav>
    </header>

    <div class="top-container">
        <h2>Bases de Datos Generales</h2>
        <a href="bases de registro.php?sincronizar=true" class="btn-sincronizar">🔄 Sincronizar Excel ➔ MySQL</a>
    </div>

    <?php if (isset($_GET['sincronizado']) && $_GET['sincronizado'] === 'ok'): ?>
        <div class="alerta-exito">
            ✅ ¡Sincronización completa realizada con éxito desde Empleados.csv!
        </div>
    <?php endif; ?>

    <!-- FORMULARIO DE EDICIÓN -->
    <?php if ($editando_mysql && !empty($datos_mysql_editar)): ?>
        <div class="seccion-edicion" id="formularioEdicion">
            <h3>✏️ Modificar Registro (ID: #<?php echo $id_mysql_editar; ?>)</h3>
            <form action="bases de registro.php" method="POST">
                <input type="hidden" name="accion" value="actualizar_mysql_unificado">
                <input type="hidden" name="id_registro" value="<?php echo $id_mysql_editar; ?>">
                <div class="form-grid">
                    <?php 
                    foreach ($datos_mysql_editar as $columna => $valor) {
                        if ($columna === 'id_Registro_personal' || $columna === 'ID_Empleado') continue; 
                        echo "<div class='form-group'>";
                        echo "<label>" . htmlspecialchars($columna) . "</label>";
                        echo "<input type='text' name='campo[{$columna}]' value='" . htmlspecialchars($valor) . "'>";
                        echo "</div>";
                    }
                    ?>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-actualizar">💾 Guardar Cambios</button>
                    <a href="bases de registro.php#tablaUnificadaSeccion" class="btn-cancelar">❌ Cancelar</a>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- TABLA GENERAL -->
    <div class="seccion-tabla" id="tablaUnificadaSeccion">
        <h3>📁 Base de Datos Unificada (Registros de MySQL)</h3>
        
        <div class="panel-impresion" style="margin-top: 15px;">
            <div class="botones-sup">
                <button onclick="imprimirUnificadoPersonalizado()" class="btn-imprimir">🖨️ Imprimir Seleccionados</button>
                <button type="button" onclick="marcarTodasColumnasUnificadas(true)" class="btn-accion-col">Mostrar Todas</button>
                <button type="button" onclick="marcarTodasColumnasUnificadas(false)" class="btn-accion-col">Ocultar Todas</button>
            </div>
            <div style="font-size: 13px; font-weight: bold; color: #444; margin-bottom: 8px;">📋 Selecciona columnas a mostrar:</div>
            <div class="columnas-selector" id="contenedorSelectorColumnasUnificada"></div>
        </div>

        <div class="buscador-container">
            <input type="text" id="buscadorUnificado" class="input-buscador" placeholder="🔍 Buscar..." onkeyup="filtrarUnificado()">
        </div>

        <div class="table-responsive">
            <table id="tablaUnificada">
                <thead>
                    <tr>
                        <th class="col-imprimir-unificado" style="text-align: center; width: 85px;">
                            🖨️<br>
                            <button type="button" class="btn-tabla-header" onclick="marcarTodasFilasUnificadas(true)">Todos</button>
                            <button type="button" class="btn-tabla-header" onclick="marcarTodasFilasUnificadas(false)">Ocultar</button>
                        </th>
                        <th class="col-acciones-pers">Acciones</th>
                        <?php
                        $sql_mysql_unif = "SELECT rp.*, l.* FROM registro_personal rp LEFT JOIN laboral l ON rp.id_Registro_personal = l.ID_Empleado";
                        $res_mysql_unif = mysqli_query($conexion, $sql_mysql_unif);
                        
                        if ($res_mysql_unif && mysqli_num_rows($res_mysql_unif) > 0) {
                            $primer_registro = mysqli_fetch_assoc($res_mysql_unif);
                            $u_idx = 0;
                            foreach ($primer_registro as $columna => $val) {
                                echo "<th class='th-columna-u-{$u_idx}' data-col-index='{$u_idx}'>" . htmlspecialchars($columna) . "</th>";
                                $u_idx++;
                            }
                            mysqli_data_seek($res_mysql_unif, 0); 
                        } else {
                            echo "<th>No se encontraron registros en la tabla de MySQL.</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($res_mysql_unif && mysqli_num_rows($res_mysql_unif) > 0) {
                        while ($fila_m = mysqli_fetch_assoc($res_mysql_unif)) {
                            $id_pers = $fila_m['id_Registro_personal'] ?? 0;
                            echo "<tr>";
                            echo "<td style='text-align: center;'><input type='checkbox' class='fila-checkbox-unificada' value='{$id_pers}'></td>";
                            echo "<td class='col-acciones-pers'>
                                    <a href='bases de registro.php?editar_mysql={$id_pers}#formularioEdicion' style='color: #007bff; text-decoration: none; font-weight: bold;'>Editar</a> | 
                                    <a href='../controlador/eliminar.php?id={$id_pers}#tablaUnificadaSeccion' onclick='return confirm(\"¿Estás seguro de eliminar este registro?\");' style='color: red; text-decoration: none; font-weight: bold;'>Eliminar</a>
                                  </td>";
                            
                            $u_idx = 0;
                            foreach ($fila_m as $val) {
                                echo "<td class='td-columna-u-{$u_idx}'>" . htmlspecialchars($val ?? '') . "</td>";
                                $u_idx++;
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='30' style='text-align: center;'>No hay registros en MySQL todavía.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SCRIPTS DINÁMICOS -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            let selectorContainerUnif = document.getElementById('contenedorSelectorColumnasUnificada');
            let thsUnif = document.querySelectorAll('#tablaUnificada th');
            
            for (let i = 2; i < thsUnif.length; i++) {
                let th = thsUnif[i];
                let nombreCol = th.textContent.trim();
                let colIndex = th.getAttribute('data-col-index');
                
                let resizer = document.createElement('div');
                resizer.className = 'resizer';
                th.appendChild(resizer);
                
                createResizableColumn(th, resizer);

                let label = document.createElement('label');
                label.innerHTML = `<input type='checkbox' class='col-checkbox-unificada' value='${colIndex}' checked> ${nombreCol}`;
                let chk = label.querySelector('input');
                chk.addEventListener('change', (e) => {
                    document.querySelectorAll(`.th-columna-u-${colIndex}, .td-columna-u-${colIndex}`).forEach(el => {
                        el.style.display = e.target.checked ? '' : 'none';
                    });
                });
                selectorContainerUnif.appendChild(label);
            }

            let seleccionadosGuardados = JSON.parse(localStorage.getItem('filasSeleccionadas') || '[]');
            seleccionadosGuardados.forEach(id => {
                let chk = document.querySelector(`.fila-checkbox-unificada[value='${id}']`);
                if (chk) {
                    chk.checked = true;
                    chk.closest('tr').classList.add('fila-seleccionada');
                }
            });

            document.querySelectorAll('.fila-checkbox-unificada').forEach(chk => {
                chk.addEventListener('change', function() {
                    let tr = this.closest('tr');
                    if (this.checked) {
                        tr.classList.add('fila-seleccionada');
                    } else {
                        tr.classList.remove('fila-seleccionada');
                    }
                    actualizarMemoriaSeleccion();
                });
            });
        });

        function actualizarMemoriaSeleccion() {
            let seleccionados = [];
            document.querySelectorAll('.fila-checkbox-unificada:checked').forEach(chk => {
                seleccionados.push(chk.value);
            });
            localStorage.setItem('filasSeleccionadas', JSON.stringify(seleccionados));
        }

        function createResizableColumn(th, resizer) {
            let x = 0;
            let w = 0;

            const mouseDownHandler = function (e) {
                x = e.clientX;
                w = th.offsetWidth;
                document.addEventListener('mousemove', mouseMoveHandler);
                document.addEventListener('mouseup', mouseUpHandler);
                resizer.classList.add('resizing');
            };

            const mouseMoveHandler = function (e) {
                const dx = e.clientX - x;
                th.style.width = `${w + dx}px`;
            };

            const mouseUpHandler = function () {
                document.removeEventListener('mousemove', mouseMoveHandler);
                document.removeEventListener('mouseup', mouseUpHandler);
                resizer.classList.remove('resizing');
            };

            resizer.addEventListener('mousedown', mouseDownHandler);
        }

        function filtrarUnificado() {
            let input = document.getElementById("buscadorUnificado").value.toLowerCase();
            document.querySelectorAll("#tablaUnificada tbody tr").forEach(fila => {
                fila.style.display = fila.textContent.toLowerCase().includes(input) ? "" : "none";
            });
        }

        let marcarTodasColumnasUnificadas = (estado) => {
            document.querySelectorAll(".col-checkbox-unificada").forEach(chk => { chk.checked = estado; chk.dispatchEvent(new Event('change')); });
        };

        let marcarTodasFilasUnificadas = (estado) => {
            document.querySelectorAll(".fila-checkbox-unificada").forEach(chk => {
                if (chk.closest("tr").style.display !== "none") {
                    chk.checked = estado;
                    let tr = chk.closest('tr');
                    if(estado) { tr.classList.add('fila-seleccionada'); } else { tr.classList.remove('fila-seleccionada'); }
                }
            });
            actualizarMemoriaSeleccion();
        };

        function imprimirUnificadoPersonalizado() {
            if (document.querySelectorAll(".fila-checkbox-unificada:checked").length === 0) {
                alert("Selecciona al menos una fila en la Base de Datos.");
                return;
            }
            let win = window.open('', '', 'height=700,width=1100');
            win.document.write('<html><head><title>Reporte General</title><style>@page{size:landscape;margin:10mm;}body{font-family:Arial;font-size:7px;}table{width:100%!important;border-collapse:collapse;}th,td{border:1px solid #999;padding:3px;}th{background:#800020!important;color:#fff;-webkit-print-color-adjust:exact;}.col-imprimir-unificado,.col-acciones-pers{display:none!important;}.resizer{display:none;}</style></head><body><h2>Reporte General MySQL</h2>');
            let clon = document.getElementById('tablaUnificada').cloneNode(true);
            clon.querySelectorAll("tbody tr").forEach((f, idx) => {
                if (!document.querySelectorAll(".fila-checkbox-unificada")[idx].checked) f.remove();
                else { let td = f.querySelector("td"); if(td) td.remove(); }
            });
            let ths = clon.querySelectorAll("th");
            if(ths[0]) ths[0].remove();
            if(ths[1]) ths[1].remove();
            win.document.write(clon.outerHTML + '</body></html>');
            win.document.close();
            setTimeout(() => { win.print(); win.close(); }, 500);
        }
    </script>
                  <!-- === COLOCA ESTO AL FINAL DE TU CONTENIDO (antes de </body>) === -->

</body>
</html>