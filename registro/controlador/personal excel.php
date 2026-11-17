<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Ruta a tu archivo de Excel completo
$archivoExcel = '../Base de Datos KARLa (1).xlsx';

// Cargar el archivo
$spreadsheet = IOFactory::load($archivoExcel);

// 1. Obtener el nombre de todas las hojas disponibles en tu Excel
$hojasDisponibles = $spreadsheet->getSheetNames();

// 2. Seleccionar cuál hoja leer (por defecto toma la primera o la que le pases por URL)
$hojaSeleccionada = $_GET['hoja'] ?? $hojasDisponibles[0];
$hojaActual = $spreadsheet->getSheetByName($hojaSeleccionada);

// Convertir los datos de esa hoja a un arreglo/matriz de PHP
$datosTabla = $hojaActual->toArray();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visualizador de Hojas de Excel</title>
    <style>
        .pestanhas { margin-bottom: 20px; }
        .pestanhas a {
            padding: 8px 16px;
            background-color: #8b0000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
        }
        .pestanhas a.activa { background-color: #28a745; }
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        th { background: #8b0000; color: white; padding: 10px; }
        td { border-bottom: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>

    <!-- MENÚ DE PESTAÑAS (Generado automáticamente con las hojas de tu Excel) -->
    <div class="pestanhas">
        <h3>Selecciona la hoja que deseas ver:</h3>
        <?php foreach ($hojasDisponibles as $nombreHoja): ?>
            <a href="?hoja=<?php echo urlencode($nombreHoja); ?>" 
               class="<?php echo ($nombreHoja === $hojaSeleccionada) ? 'activa' : ''; ?>">
                📄 <?php echo htmlspecialchars($nombreHoja); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- TABLA QUE MUESTRA LOS DATOS DE LA HOJA SELECCIONADA -->
    <div class="table-responsive">
        <table>
            <thead>
                <?php if (!empty($datosTabla)): ?>
                    <tr>
                        <?php foreach ($datosTabla[0] as $encabezado): ?>
                            <th><?php echo htmlspecialchars($encabezado ?? ''); ?></th>
                        <?php endforeach; ?>
                    </tr>
                <?php endif; ?>
            </thead>
            <tbody>
                <?php 
                // Recorrer las filas omitiendo la primera (encabezados)
                for ($i = 1; $i < count($datosTabla); $i++): 
                    // Si la fila está vacía, saltarla
                    if (array_filter($datosTabla[$i]) === []) continue;
                ?>
                    <tr>
                        <?php foreach ($datosTabla[$i] as $celda): ?>
                            <td><?php echo htmlspecialchars($celda ?? ''); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>

</body>
</html>