<?php
$conexion = new mysqli("localhost", "root", "", "registros");
$conexion->set_charset("utf8");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Cuando le das clic a "Guardar Cambios" desde el formulario
if (isset($_POST['actualizar'])) {
    $id = $_POST['id_empleado'];
    $rfc = $_POST['rfc'];
    $curp = $_POST['curp'];
    $nombre = $_POST['nombre_empleado'];
    $fnac = $_POST['fecha_nacimiento'];
    $escolaridad = $_POST['escolaridad'];
    $ecivil = $_POST['estado_civil'];
    $colonia = $_POST['colonia'];
    $calle = $_POST['calle'];
    $municipio = $_POST['municipio'];
    $cp = $_POST['cp'];
    $celular = $_POST['celular'];
    $clv_issemym = $_POST['clave_issemym'];
    $ing_gem = $_POST['fecha_ingreso_gem'];
    $ult_mov = $_POST['fecha_ultimo_movimiento'];
    $genero = $_POST['genero'];
    $f_paga = $_POST['fecha_paga'];
    $id_u_org = $_POST['id_unidad_org'];
    $u_org = $_POST['unidad_org'];
    $dir = $_POST['dir'];
    $id_plaza = $_POST['id_plaza'];
    $t_servidor = $_POST['tipo_servidor_publico'];
    $t_plaza = $_POST['tipo_plaza'];
    $id_puesto = $_POST['id_puesto'];
    $nom_puesto = $_POST['nombre_puesto'];
    $cct = $_POST['cct'];
    $lugar = $_POST['lugar_de_trabajo'];

    // Actualiza en MySQL
    $sql_update = "UPDATE servidores_publicos SET 
        rfc='$rfc', curp='$curp', nombre_empleado='$nombre', fecha_nacimiento='$fnac', 
        escolaridad='$escolaridad', estado_civil='$ecivil', colonia='$colonia', calle='$calle', 
        municipio='$municipio', cp='$cp', celular='$celular', clave_issemym='$clv_issemym', 
        fecha_ingreso_gem='$ing_gem', fecha_ultimo_movimiento='$ult_mov', genero='$genero', 
        fecha_paga='$f_paga', id_unidad_org='$id_u_org', unidad_org='$u_org', dir='$dir', 
        id_plaza='$id_plaza', tipo_servidor_publico='$t_servidor', tipo_plaza='$t_plaza', 
        id_puesto='$id_puesto', nombre_puesto='$nom_puesto', cct='$cct', lugar_de_trabajo='$lugar' 
        WHERE id_empleado='$id'";

    if ($conexion->query($sql_update) === TRUE) {
        header("Location: base_de_datos.php");
        exit();
    } else {
        echo "Error al actualizar: " . $conexion->error;
    }
}

// Cargar los datos actuales del empleado al hacer clic en Editar (viene el ID por la URL)
$id_empleado = $_GET['id'] ?? '';
$row = [];
if (!empty($id_empleado)) {
    $resultado = $conexion->query("SELECT * FROM servidores_publicos WHERE id_empleado = '$id_empleado'");
    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Registro</title>
</head>
<body>
    <h2>Editar Registro Completo</h2>
    <form action="editar.php" method="POST">
        <input type="hidden" name="id_empleado" value="<?php echo $row['id_empleado'] ?? ''; ?>">

        <label>RFC:</label><br><input type="text" name="rfc" value="<?php echo $row['rfc'] ?? ''; ?>"><br><br>
        <label>CURP:</label><br><input type="text" name="curp" value="<?php echo $row['curp'] ?? ''; ?>"><br><br>
        <label>Nombre del empleado:</label><br><input type="text" name="nombre_empleado" value="<?php echo $row['nombre_empleado'] ?? ''; ?>"><br><br>
        <label>Fecha de Nacimiento:</label><br><input type="text" name="fecha_nacimiento" value="<?php echo $row['fecha_nacimiento'] ?? ''; ?>"><br><br>
        <label>Escolaridad:</label><br><input type="text" name="escolaridad" value="<?php echo $row['escolaridad'] ?? ''; ?>"><br><br>
        <label>Estado Civil:</label><br><input type="text" name="estado_civil" value="<?php echo $row['estado_civil'] ?? ''; ?>"><br><br>
        <label>Colonia:</label><br><input type="text" name="colonia" value="<?php echo $row['colonia'] ?? ''; ?>"><br><br>
        <label>Calle:</label><br><input type="text" name="calle" value="<?php echo $row['calle'] ?? ''; ?>"><br><br>
        <label>Municipio:</label><br><input type="text" name="municipio" value="<?php echo $row['municipio'] ?? ''; ?>"><br><br>
        <label>CP:</label><br><input type="text" name="cp" value="<?php echo $row['cp'] ?? ''; ?>"><br><br>
        <label>Celular:</label><br><input type="text" name="celular" value="<?php echo $row['celular'] ?? ''; ?>"><br><br>
        <label>Clave ISSEMYM:</label><br><input type="text" name="clave_issemym" value="<?php echo $row['clave_issemym'] ?? ''; ?>"><br><br>
        <label>Fecha de Ingreso al GEM:</label><br><input type="text" name="fecha_ingreso_gem" value="<?php echo $row['fecha_ingreso_gem'] ?? ''; ?>"><br><br>
        <label>Fecha último Movimiento:</label><br><input type="text" name="fecha_ultimo_movimiento" value="<?php echo $row['fecha_ultimo_movimiento'] ?? ''; ?>"><br><br>
        <label>Género:</label><br><input type="text" name="genero" value="<?php echo $row['genero'] ?? ''; ?>"><br><br>
        <label>Fecha de paga:</label><br><input type="text" name="fecha_paga" value="<?php echo $row['fecha_paga'] ?? ''; ?>"><br><br>
        <label>ID Unidad org:</label><br><input type="text" name="id_unidad_org" value="<?php echo $row['id_unidad_org'] ?? ''; ?>"><br><br>
        <label>Unidad org:</label><br><input type="text" name="unidad_org" value="<?php echo $row['unidad_org'] ?? ''; ?>"><br><br>
        <label>Dir:</label><br><input type="text" name="dir" value="<?php echo $row['dir'] ?? ''; ?>"><br><br>
        <label>ID Plaza:</label><br><input type="text" name="id_plaza" value="<?php echo $row['id_plaza'] ?? ''; ?>"><br><br>
        <label>Tipo Servidor Público:</label><br><input type="text" name="tipo_servidor_publico" value="<?php echo $row['tipo_servidor_publico'] ?? ''; ?>"><br><br>
        <label>Tipo Plaza:</label><br><input type="text" name="tipo_plaza" value="<?php echo $row['tipo_plaza'] ?? ''; ?>"><br><br>
        <label>ID Puesto:</label><br><input type="text" name="id_puesto" value="<?php echo $row['id_puesto'] ?? ''; ?>"><br><br>
        <label>Nombre puesto:</label><br><input type="text" name="nombre_puesto" value="<?php echo $row['nombre_puesto'] ?? ''; ?>"><br><br>
        <label>CCT:</label><br><input type="text" name="cct" value="<?php echo $row['cct'] ?? ''; ?>"><br><br>
        <label>Lugar de trabajo:</label><br><input type="text" name="lugar_de_trabajo" value="<?php echo $row['lugar_de_trabajo'] ?? ''; ?>"><br><br>

        <button type="submit" name="actualizar">Guardar Cambios</button>
    </form>
</body>
</html>