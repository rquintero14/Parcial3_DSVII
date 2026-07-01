<?php

require_once __DIR__ . '/../config/Conexion.php';

class PerfilLaboral
{
    private $cn;

    public function __construct()
    {
        $this->cn = Conexion::conectar();
    }

    public function guardar($datos)
    {
        $sql = "INSERT INTO perfiles_laborales
                (
                    colaborador_id,
                    ocupacion_id,
                    tipo_planilla_id,
                    salario,
                    fecha_inicio,
                    fecha_fin,
                    cargo_activo,
                    empleado_activo,
                    motivo,
                    firma_digital
                )
                VALUES
                (
                    :colaborador_id,
                    :ocupacion_id,
                    :tipo_planilla_id,
                    :salario,
                    :fecha_inicio,
                    :fecha_fin,
                    :cargo_activo,
                    :empleado_activo,
                    :motivo,
                    :firma_digital
                )";

        $stmt = $this->cn->prepare($sql);

        return $stmt->execute([
            ':colaborador_id' => $datos['colaborador_id'],
            ':ocupacion_id' => $datos['ocupacion_id'],
            ':tipo_planilla_id' => $datos['tipo_planilla_id'],
            ':salario' => $datos['salario'],
            ':fecha_inicio' => $datos['fecha_inicio'],
            ':fecha_fin' => $datos['fecha_fin'],
            ':cargo_activo' => $datos['cargo_activo'],
            ':empleado_activo' => $datos['empleado_activo'],
            ':motivo' => $datos['motivo'],
            ':firma_digital' => $datos['firma_digital']
        ]);
    }

    public function listar()
    {
        $sql = "SELECT
                    pl.*,
                    c.nombre,
                    c.apellido,
                    co.OCUPACION AS nombre_ocupacion,
                    tp.nombre_planilla
                FROM perfiles_laborales pl
                INNER JOIN colaboradores c
                    ON pl.colaborador_id = c.id
                INNER JOIN cat_ocupaciones co
                    ON pl.ocupacion_id = co.C_OCUP
                INNER JOIN cat_tipos_planilla tp
                    ON pl.tipo_planilla_id = tp.id
                ORDER BY c.nombre";

        return $this->cn->query($sql)->fetchAll();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT *
                FROM perfiles_laborales
                WHERE id = :id";

        $stmt = $this->cn->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    public function actualizar($id, $datos)
    {
        $sql = "UPDATE perfiles_laborales SET
                    ocupacion_id = :ocupacion_id,
                    tipo_planilla_id = :tipo_planilla_id,
                    salario = :salario,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    cargo_activo = :cargo_activo,
                    empleado_activo = :empleado_activo,
                    motivo = :motivo,
                    firma_digital = :firma_digital
                WHERE id = :id";

        $stmt = $this->cn->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':ocupacion_id' => $datos['ocupacion_id'],
            ':tipo_planilla_id' => $datos['tipo_planilla_id'],
            ':salario' => $datos['salario'],
            ':fecha_inicio' => $datos['fecha_inicio'],
            ':fecha_fin' => $datos['fecha_fin'],
            ':cargo_activo' => $datos['cargo_activo'],
            ':empleado_activo' => $datos['empleado_activo'],
            ':motivo' => $datos['motivo'],
            ':firma_digital' => $datos['firma_digital']
        ]);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM perfiles_laborales
                WHERE id = :id";

        $stmt = $this->cn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function obtenerHistorial($colaboradorId)
    {
        $sql = "SELECT
                    pl.*,
                    co.nombre_ocupacion,
                    tp.nombre_planilla
                FROM perfiles_laborales pl
                INNER JOIN cat_ocupaciones co
                    ON pl.ocupacion_id = co.id
                INNER JOIN cat_tipos_planilla tp
                    ON pl.tipo_planilla_id = tp.id
                WHERE pl.colaborador_id = :id
                ORDER BY pl.fecha_inicio DESC";

        $stmt = $this->cn->prepare($sql);

        $stmt->execute([
            ':id' => $colaboradorId
        ]);

        return $stmt->fetchAll();
    }
}
?>