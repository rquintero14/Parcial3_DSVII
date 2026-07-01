<?php
require_once '../Controllers/ColaboradorController.php';
require_once '../Controllers/PerfilLaboralController.php';
require_once '../Utils/FirmaDigital.php';

$mensaje = "";

$colaboradorController = new ColaboradorController();
$perfilController = new PerfilLaboralController();

$ocupaciones = $perfilController->listarOcupaciones();
$tiposPlanilla = $perfilController->listarTiposPlanilla();
$motivosBaja = $perfilController->listarMotivosTerminacion();
$estadosCiviles = $colaboradorController->listarEstadosCiviles();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $datosColaborador = [

        'cedula' => $_POST['cedula'],
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'fecha_nacimiento' => $_POST['fecha_nacimiento'],
        'correo' => $_POST['correo'],
        'telefono' => $_POST['telefono'],
        'direccion' => $_POST['direccion'] ?? '',
        'estado_civil' => $_POST['estado_civil'] ?? null,
        'firma_digital' => ''
    ];

    if ($colaboradorController->guardar($datosColaborador)) {

        $idColaborador = $colaboradorController->obtenerUltimoId();

        $firma = FirmaDigital::generarFirmaPerfil(
            $idColaborador,
            $_POST['ocupacion_id'],
            $_POST['tipo_planilla_id'],
            $_POST['salario'],
            $_POST['fecha_inicio']
        );

        $datosPerfil = [

            'colaborador_id' => $idColaborador,
            'ocupacion_id' => $_POST['ocupacion_id'],
            'tipo_planilla_id' => $_POST['tipo_planilla_id'],
            'salario' => $_POST['salario'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'] ?: null,
            'cargo_activo' => isset($_POST['cargo_activo']) ? (int) $_POST['cargo_activo'] : 1,
            'empleado_activo' => isset($_POST['empleado_activo']) ? (int) $_POST['empleado_activo'] : (empty($_POST['fecha_fin']) ? 1 : 0),
            'motivo' => $_POST['motivo'],
            'firma_digital' => $firma

        ];

        $perfilController->guardar($datosPerfil);

        $mensaje = "Registro guardado correctamente";
    } else {
        $mensaje = "Error: " . $colaboradorController->getError();
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Registro de Colaboradores</title>

<link rel="stylesheet" href="../assets/styles.css">

</head>

<body>

<div class="contenedor">

<h1>Registro de Colaboradores</h1>

<?php if($mensaje!=""){ ?>
<div class="ok"><?php echo $mensaje; ?></div>
<?php } ?>

<form method="POST">

<h2>Datos Personales</h2>

<input type="text" name="cedula" placeholder="Cédula" required>

<input type="text" name="nombre" placeholder="Nombre" required>

<input type="text" name="apellido" placeholder="Apellido" required>

<input type="date" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" required>

<input type="email" name="correo" placeholder="Correo" required>

<input type="text" name="telefono" placeholder="Teléfono" required>

<select name="estado_civil" required>
    <option value="">Estado Civil</option>
    <?php foreach ($estadosCiviles as $estado): ?>
        <option value="<?= htmlspecialchars($estado['id']); ?>">
            <?= htmlspecialchars($estado['nombre']); ?>
        </option>
    <?php endforeach; ?>
</select>

<textarea name="direccion" placeholder="Dirección"></textarea>

<h2>Perfil Laboral</h2>

<select name="ocupacion_id" required>
    <option value="">Ocupación</option>
    <?php foreach ($ocupaciones as $ocupacion): ?>
        <option value="<?= htmlspecialchars($ocupacion['C_OCUP']); ?>">
            <?= htmlspecialchars($ocupacion['OCUPACION']); ?>
        </option>
    <?php endforeach; ?>
</select>

<select name="tipo_planilla_id" required>
    <option value="">Planilla</option>
    <?php foreach ($tiposPlanilla as $planilla): ?>
        <option value="<?= htmlspecialchars($planilla['id']); ?>">
            <?= htmlspecialchars($planilla['nombre_planilla']); ?>
        </option>
    <?php endforeach; ?>
</select>

<input type="number" step="0.01" name="salario" placeholder="Salario" required>

<label>Fecha Inicio</label>
<input type="date" name="fecha_inicio" required>

<label>Fecha Fin</label>
<input type="date" name="fecha_fin">

<label>Cargo activo</label>
<select name="cargo_activo">
    <option value="1">Activo</option>
    <option value="0">No activo</option>
</select>

<label>Empleado activo</label>
<select name="empleado_activo">
    <option value="1">Activo</option>
    <option value="0">No activo</option>
</select>

<select name="motivo">
    <option value="">Motivo de baja</option>
    <?php foreach ($motivosBaja as $motivo): ?>
        <option value="<?= htmlspecialchars($motivo['MOTIVO']); ?>">
            <?= htmlspecialchars($motivo['MOTIVO']); ?>
        </option>
    <?php endforeach; ?>
</select>

<button type="submit">
Guardar Registro
</button>

</form>

<br>

<a href="reporte.php" class="btn">
Ver Reporte
</a>

</div>

<footer>
© <?php echo date("Y"); ?> iTECH Contrataciones. All rights reserved.
</footer>

</body>
</html>