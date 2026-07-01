<?php

require_once __DIR__ . '/../modelos/PerfilLaboral.php';

class PerfilLaboralController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new PerfilLaboral();
    }

    public function guardar($datos)
    {
        try {

            if (
                empty($datos['colaborador_id']) ||
                empty($datos['ocupacion_id']) ||
                empty($datos['tipo_planilla_id']) ||
                empty($datos['salario']) ||
                empty($datos['fecha_inicio'])
            ) {
                throw new Exception("Complete todos los campos requeridos");
            }

            return $this->modelo->guardar($datos);

        } catch (Exception $e) {

            return false;
        }
    }

    public function listar()
    {
        return $this->modelo->listar();
    }

    public function buscarPorId($id)
    {
        return $this->modelo->buscarPorId($id);
    }

    public function actualizar($id, $datos)
    {
        return $this->modelo->actualizar($id, $datos);
    }

    public function eliminar($id)
    {
        return $this->modelo->eliminar($id);
    }

    public function obtenerHistorial($colaboradorId)
    {
        return $this->modelo->obtenerHistorial($colaboradorId);
    }

    public function finalizarCargo($id, $fechaFin, $motivo)
    {
        $perfil = $this->modelo->buscarPorId($id);

        if (!$perfil) {
            return false;
        }

        $perfil['fecha_fin'] = $fechaFin;
        $perfil['cargo_activo'] = 0;
        $perfil['empleado_activo'] = 0;
        $perfil['motivo'] = $motivo;

        return $this->modelo->actualizar($id, $perfil);
    }

    public function promoverEmpleado(
        $colaboradorId,
        $ocupacionId,
        $tipoPlanillaId,
        $salario,
        $fechaInicio,
        $firmaDigital
    ) {

        $historial = $this->modelo->obtenerHistorial($colaboradorId);

        foreach ($historial as $cargo) {

            if ($cargo['cargo_activo'] == 1) {

                $cargo['cargo_activo'] = 0;

                $this->modelo->actualizar(
                    $cargo['id'],
                    $cargo
                );
            }
        }

        $nuevoCargo = [

            'colaborador_id' => $colaboradorId,
            'ocupacion_id' => $ocupacionId,
            'tipo_planilla_id' => $tipoPlanillaId,
            'salario' => $salario,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => null,
            'cargo_activo' => 1,
            'empleado_activo' => 1,
            'motivo' => 'Promoción',
            'firma_digital' => $firmaDigital

        ];

        return $this->modelo->guardar($nuevoCargo);
    }
}