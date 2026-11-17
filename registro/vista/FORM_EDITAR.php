<?php
$num_fila = $_GET['fila'] ?? 0;
$ruta_csv = "../Empleados.csv";
$empleado = [];

if (file_exists($ruta_csv)) {
    if (($gestor = fopen($ruta_csv, "r")) !== FALSE) {
        $i = 0;
        while (($data = fgetcsv($gestor, 1000, ",")) !== FALSE) {
            if ($i == $num_fila) {
                $empleado = $data;
                break;
            }
            $i++;
        }
        fclose($gestor);
    }
}

// Lista con los nombres de todas tus columnas
$columnas = [
    "ID_Empleado", "RFC", "CURP", "Nombre del empleado", "Fecha de Nacimiento", 
    "Escolaridad", "Estado Civil", "Colonia", "Calle", "Municipio", "CP", 
    "Celular", "Clave ISSEMYM", "Fecha de Ingreso al GEM", "Fecha ultimo Movimiento", 
    "Genero", "Fecha de paga", "ID Unidad org", "Unidad org", "Dir", "ID Plaza", 
    "Tipo Servidor Publico", "Tipo Plaza GENERAL/CONFIANZA", "ID Puesto", 
    "Nombre puesto", "CCT", "Lugar de trabajo"
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado - Formulario Ancho</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f6f9; 
            margin: 0; 
            padding: 30px; 
        }
        /* Contenedor principal mucho más ancho */
        .form-container { 
            background: #ffffff; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
            max-width: 900px; 
            margin: auto; 
        }
        h3 { 
            color: #800020; 
            margin-top: 0; 
            text-align: center; 
            margin-bottom: 25px;
            font-size: 22px;
        }
        /* Estructura en dos columnas para que no sea largo verticalmente */
        .grid-campos {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .campo { 
            display: flex;
            flex-direction: column;
        }
        label { 
            font-weight: bold; 
            font-size: 13px; 
            color: #444; 
            margin-bottom: 6px; 
        }
        input { 
            width: 100%; 
            padding: 10px 12px; 
            border: 1px solid #ccc; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input:focus {
            border-color: #8b0000;
            box-shadow: 0 0 5px rgba(139, 0, 0, 0.2);
            outline: none;
        }
        /* Contenedor de botones estéticos y modernos */
        .acciones-container {
            grid-column: span 2;
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        .btn-guardar { 
            background-color: #28a745; 
            color: white; 
            border: none; 
            padding: 12px 20px; 
            flex: 1; 
            font-weight: bold; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 16px;
            transition: background-color 0.2s;
            box-shadow: 0 2px 5px rgba(40,167,69,0.3);
        }
        .btn-guardar:hover { 
            background-color: #218838; 
        }
        .btn-cancelar { 
            background-color: #dc3545; 
            color: white; 
            border: none; 
            padding: 12px 20px; 
            flex: 1; 
            font-weight: bold; 
            border-radius: 6px; 
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.2s;
            box-shadow: 0 2px 5px rgba(220,53,69,0.3);
            display: inline-block;
            box-sizing: border-box;
        }
        .btn-cancelar:hover { 
            background-color: #c82333; 
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3>✏️ Editar Registro del Empleado</h3>
        <form action="../controlador/editar.php" method="POST">
            <input type="hidden" name="fila" value="<?php echo $num_fila; ?>">
            
            <div class="grid-campos">
                <?php foreach ($columnas as $index_col => $nombre_col): 
                    $valor_actual = $empleado[$index_col] ?? '';
                ?>
                    <div class="campo">
                        <label><?php echo $nombre_col; ?>:</label>
                        <input type="text" name="campo_<?php echo $index_col; ?>" value="<?php echo htmlspecialchars($valor_actual); ?>">
                    </div>
                <?php endforeach; ?>
                
                <!-- Botones modernos, estilizados y lado a lado -->
                <div class="acciones-container">
                    <button type="submit" class="btn-guardar">💾 Guardar Cambios</button>
                    <a href="bases de registro.php" class="btn-cancelar">❌ Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>