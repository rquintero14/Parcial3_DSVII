<?php

require_once '../Controllers/PerfilLaboralController.php';
require_once '../Utils/FirmaDigital.php';

$controller = new PerfilLaboralController();
$registros = $controller->listar();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Reporte de Colaboradores</title>

    <link rel="stylesheet" href="../assets/styles.css">

</head>

<body>

<header>
    Sistema de Gestión de Colaboradores - iTECH Contrataciones
</header>

<div class="contenedor">

    <h1>Historial Laboral de Colaboradores</h1>

    <div style="margin-bottom:20px;">

        <a href="formulario.php" class="btn">
            Nuevo Registro
        </a>

        <a href="../excel/exportar_excel.php" class="btn btn-success">
            Exportar Excel
        </a>

    </div>

    <?php if (count($registros) > 0): ?>

        <table>

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Ocupación</th>
                    <th>Planilla</th>
                    <th>Estado Civil</th>
                    <th>Salario</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Cargo Activo</th>
                    <th>Empleado Activo</th>
                    <th>Firma Digital</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach ($registros as $fila): ?>

                    <?php

                    $firmaCadena = FirmaDigital::generarCadenaPerfil(
                        $fila['colaborador_id'],
                        $fila['ocupacion_id'],
                        $fila['tipo_planilla_id'],
                        $fila['salario'],
                        $fila['fecha_inicio']
                    );

                    $firmaValida = FirmaDigital::verificarFirma(
                        $firmaCadena,
                        $fila['firma_digital']
                    );

                    ?>

                    <tr>

                        <td><?= $fila['id']; ?></td>

                        <td><?= htmlspecialchars($fila['nombre']); ?></td>

                        <td><?= htmlspecialchars($fila['apellido']); ?></td>

                        <td><?= htmlspecialchars($fila['nombre_ocupacion']); ?></td>

                        <td><?= htmlspecialchars($fila['nombre_planilla']); ?></td>

                        <td><?= htmlspecialchars($fila['estado_civil'] ?? 'N/A'); ?></td>

                        <td>$<?= number_format($fila['salario'], 2); ?></td>

                        <td><?= $fila['fecha_inicio']; ?></td>

                        <td>
                            <?= !empty($fila['fecha_fin'])
                                ? $fila['fecha_fin']
                                : 'N/A'; ?>
                        </td>

                        <td>
                            <?= $fila['cargo_activo']
                                ? 'SI'
                                : 'NO'; ?>
                        </td>

                        <td>
                            <?= $fila['empleado_activo']
                                ? 'SI'
                                : 'NO'; ?>
                        </td>

                        <td
                            style="
                            font-weight:bold;
                            text-align:center;
                            color:<?= $firmaValida ? 'green' : 'red'; ?>;
                            "
                        >
                            <?= $firmaValida
                                ? 'VÁLIDA'
                                : 'ALTERADA'; ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php else: ?>

        <div class="error">
            No existen registros para mostrar.
        </div>

    <?php endif; ?>

</div>

<footer>
    © <?= date("Y"); ?> iTECH Contrataciones - Todos los derechos reservados.
</footer>

</body>

</html>