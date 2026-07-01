<?php

require_once '../vendor/autoload.php';
require_once '../Controllers/PerfilLaboralController.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$controller = new PerfilLaboralController();

$registros = $controller->listar();

$excel = new Spreadsheet();

$hoja = $excel->getActiveSheet();

$hoja->setTitle('Colaboradores');

$hoja->setCellValue('A1', 'ID');
$hoja->setCellValue('B1', 'Nombre');
$hoja->setCellValue('C1', 'Apellido');
$hoja->setCellValue('D1', 'Ocupación');
$hoja->setCellValue('E1', 'Tipo Planilla');
$hoja->setCellValue('F1', 'Salario');
$hoja->setCellValue('G1', 'Fecha Inicio');
$hoja->setCellValue('H1', 'Fecha Fin');
$hoja->setCellValue('I1', 'Cargo Activo');
$hoja->setCellValue('J1', 'Empleado Activo');
$hoja->setCellValue('K1', 'Motivo');

$fila = 2;

foreach ($registros as $registro) {

    $hoja->setCellValue('A' . $fila, $registro['id']);
    $hoja->setCellValue('B' . $fila, $registro['nombre']);
    $hoja->setCellValue('C' . $fila, $registro['apellido']);
    $hoja->setCellValue('D' . $fila, $registro['nombre_ocupacion']);
    $hoja->setCellValue('E' . $fila, $registro['nombre_planilla']);
    $hoja->setCellValue('F' . $fila, $registro['salario']);
    $hoja->setCellValue('G' . $fila, $registro['fecha_inicio']);
    $hoja->setCellValue('H' . $fila, $registro['fecha_fin']);
    $hoja->setCellValue(
        'I' . $fila,
        $registro['cargo_activo'] ? 'SI' : 'NO'
    );
    $hoja->setCellValue(
        'J' . $fila,
        $registro['empleado_activo'] ? 'SI' : 'NO'
    );
    $hoja->setCellValue(
        'K' . $fila,
        $registro['motivo']
    );

    $fila++;
}

$hoja->getStyle('A1:K1')
    ->getFont()
    ->setBold(true);

foreach (range('A', 'K') as $columna) {

    $hoja->getColumnDimension($columna)
         ->setAutoSize(true);
}

$nombreArchivo =
    'Reporte_Colaboradores_' .
    date('Ymd_His') .
    '.xlsx';

header(
    'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
);

header(
    'Content-Disposition: attachment;filename="' .
    $nombreArchivo .
    '"'
);

header('Cache-Control: max-age=0');

$writer = new Xlsx($excel);

$writer->save('php://output');

exit;
?>