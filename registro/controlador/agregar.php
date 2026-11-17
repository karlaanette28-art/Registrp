<?php
$conexion = new mysqli("localhost", "root", "", "registros");
$conexion->set_charset("utf8");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (isset($_POST['guardar'])) {
    $id_empleado = $_POST['id_empleado'];
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
    $genero = $_POST['genero'];<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Servidor Público</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background-color: #f4f6f9; 
        }
        header {
            background-color: #800020;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }
        .navbar .logo {
            color: #ffffff;
            font-weight: bold;
            text-decoration: none;
            font-size: 1.2rem;
        }
        .browser-menu {
            display: flex;
            gap: 20px;
        }
        .browser-menu a {
            color: #ecf0f1;
            text-decoration: none;
            transition: color 0.3s;
            font-weight: 500;
        }
        .browser-menu a:hover {
            color: #ffc107;
        }
        .form-container { 
            background: white; 
            padding: 35px; 
            border-radius: 12px; 
            max-width: 800px; 
            margin: auto; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.08); 
            border-top: 5px solid #800020;
        }
        h2 {
            color: #800020;
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 1.8rem;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .grid-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .campo-completo {
            grid-column: span 2;
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
            color: #444; 
            font-size: 0.9rem;
        }
        .tipo-dato {
            font-size: 0.75rem;
            color: #666;
            font-weight: normal;
            background-color: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 5px;
        }
        input, select { 
            width: 100%; 
            padding: 10px 12px; 
            margin-bottom: 5px; 
            border: 1px solid #ccc; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 0.95rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input:focus, select:focus {
            border-color: #800020;
            box-shadow: 0 0 5px rgba(128, 0, 32, 0.3);
            outline: none;
        }
        .btn-guardar { 
            background-color: #800020; 
            color: white; 
            padding: 12px 20px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            width: 100%; 
            font-size: 1.1rem; 
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .btn-guardar:hover { 
            background-color: #600018; 
        }
        @media (max-width: 768px) {
            .grid-inputs { grid-template-columns: 1fr; }
            .campo-completo { grid-column: span 1; }
        }
    </style>
</head>
<body>

    <header>
        <nav class="navbar">
            <a href="index.php" class="logo">⬅ Regresar a la Base de Datos</a>
            <div class="browser-menu">
                <a href="index.php">Ver Registros</a>
                <a href="registro.php">Registro de personas</a>
            </div>
        </nav>
    </header>

    <div class="form-container">
        <form action="../controlad/agregar.php" method="POST">
            <h2>✨ Registrar Nuevo Servidor Público</h2>

            <div class="grid-inputs">
                <div>
                    <label>ID Empleado: <span class="tipo-dato">Número</span></label>
                    <input type="number" name="id_empleado" required placeholder="Ej. 997016031">
                </div>
                <div>
                    <label>RFC: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="rfc" placeholder="Ej. BAFA7504306X3">
                </div>

                <div>
                    <label>CURP: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="curp" placeholder="Ej. BAFA750430MMCRRR15">
                </div>
                <div class="campo-completo">
                    <label>Nombre del empleado: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="nombre_empleado" required placeholder="NOMBRE APELLIDO PATERNO APELLIDO MATERNO">
                </div>

                <div>
                    <label>Fecha de Nacimiento: <span class="tipo-dato">Fecha</span></label>
                    <input type="date" name="fecha_nacimiento">
                </div>
                <div>
                    <label>Escolaridad: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="escolaridad" placeholder="Ej. Licenciatura">
                </div>

                <div>
                    <label>Estado Civil: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="estado_civil" placeholder="Ej. Soltero/a">
                </div>
                <div>
                    <label>Género: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="genero" placeholder="Ej. Femenino / Masculino">
                </div>

                <div>
                    <label>Celular: <span class="tipo-dato">Número</span></label>
                    <input type="tel" name="celular" placeholder="Número de contacto">
                </div>
                <div>
                    <label>Clave ISSEMYM: <span class="tipo-dato">Número / Texto</span></label>
                    <input type="text" name="clave_issemym">
                </div>

                <div class="campo-completo">
                    <label>Dirección (Calle y Colonia): <span class="tipo-dato">Texto</span></label>
                </div>
                <div>
                    <input type="text" name="calle" placeholder="Calle">
                </div>
                <div>
                    <input type="text" name="colonia" placeholder="Colonia">
                </div>

                <div>
                    <label>Municipio: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="municipio">
                </div>
                <div>
                    <label>CP (Código Postal): <span class="tipo-dato">Número</span></label>
                    <input type="number" name="cp">
                </div>

                <div>
                    <label>Fecha de Ingreso al GEM: <span class="tipo-dato">Fecha</span></label>
                    <input type="date" name="fecha_ingreso_gem">
                </div>
                <div>
                    <label>Fecha último Movimiento: <span class="tipo-dato">Fecha</span></label>
                    <input type="date" name="fecha_ultimo_movimiento">
                </div>

                <div>
                    <label>Fecha de paga: <span class="tipo-dato">Fecha</span></label>
                    <input type="date" name="fecha_paga">
                </div>
                <div>
                    <label>ID Unidad org: <span class="tipo-dato">Número</span></label>
                    <input type="number" name="id_unidad_org">
                </div>

                <div class="campo-completo">
                    <label>Unidad org: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="unidad_org">
                </div>

                <div>
                    <label>Dir: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="dir">
                </div>
                <div>
                    <label>ID Plaza: <span class="tipo-dato">Número</span></label>
                    <input type="number" name="id_plaza">
                </div>

                <div>
                    <label>Tipo Servidor Público: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="tipo_servidor_publico">
                </div>
                <div>
                    <label>Tipo Plaza: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="tipo_plaza">
                </div>

                <div>
                    <label>ID Puesto: <span class="tipo-dato">Número</span></label>
                    <input type="number" name="id_puesto">
                </div>
                <div>
                    <label>Nombre puesto: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="nombre_puesto">
                </div>

                <div>
                    <label>CCT: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="cct">
                </div>
                <div>
                    <label>Lugar de trabajo: <span class="tipo-dato">Texto</span></label>
                    <input type="text" name="lugar_de_trabajo">
                </div>
            </div>

            <button type="submit" name="guardar" class="btn-guardar">💾 Guardar Nuevo Registro</button>
        </form>
    </div>

</body>
</html>
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

    $sql_insert = "INSERT INTO servidores_publicos (
        id_empleado, rfc, curp, nombre_empleado, fecha_nacimiento, escolaridad, estado_civil, 
        colonia, calle, municipio, cp, celular, clave_issemym, fecha_ingreso_gem, 
        fecha_ultimo_movimiento, genero, fecha_paga, id_unidad_org, unidad_org, dir, 
        id_plaza, tipo_servidor_publico, tipo_plaza, id_puesto, nombre_puesto, cct, lugar_de_trabajo
    ) VALUES (
        '$id_empleado', '$rfc', '$curp', '$nombre', '$fnac', '$escolaridad', '$ecivil', 
        '$colonia', '$calle', '$municipio', '$cp', '$celular', '$clv_issemym', '$ing_gem', 
        '$ult_mov', '$genero', '$f_paga', '$id_u_org', '$u_org', '$dir', 
        '$id_plaza', '$t_servidor', '$t_plaza', '$id_puesto', '$nom_puesto', '$cct', '$lugar'
    )";

    if ($conexion->query($sql_insert) === TRUE) {
        header("Location: ../base de registros.html"); 
        exit();
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
}
$conexion->close();
?>