<?php

require_once __DIR__ . '/../modelos/Colaborador.php';

class ColaboradorController
{
    private $modelo;
    private $error = '';

    public function __construct()
    {
        $this->modelo = new Colaborador();
    }

    public function guardar($datos)
    {
        try {

            if (
                empty($datos['cedula']) ||
                empty($datos['nombre']) ||
                empty($datos['apellido']) ||
                empty($datos['fecha_nacimiento']) ||
                empty($datos['correo']) ||
                empty($datos['telefono']) ||
                empty($datos['estado_civil'])
            ) {
                throw new Exception("Todos los campos obligatorios deben ser completados.");
            }

            return $this->modelo->guardar($datos);

        } catch (Exception $e) {

            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getError()
    {
        return $this->error;
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

    public function obtenerUltimoId()
    {
        return $this->modelo->obtenerUltimoId();
    }

    public function listarEstadosCiviles()
    {
        return $this->modelo->listarEstadosCiviles();
    }
}