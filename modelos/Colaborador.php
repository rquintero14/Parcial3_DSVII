<?php

require_once __DIR__ . '/../config/Conexion.php';

class Colaborador
{
    private $cn;

    public function __construct()
    {
        $this->cn = Conexion::conectar();
    }

    public function guardar($datos)
    {
        $sql = "INSERT INTO colaboradores
                (
                    cedula,
                    nombre,
                    apellido,
                    fecha_nacimiento,
                    correo,
                    telefono,
                    direccion,
                    firma_digital
                )
                VALUES
                (
                    :cedula,
                    :nombre,
                    :apellido,
                    :fecha_nacimiento,
                    :correo,
                    :telefono,
                    :direccion,
                    :firma_digital
                )";

        $stmt = $this->cn->prepare($sql);

        return $stmt->execute([
            ':cedula' => $datos['cedula'],
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':correo' => $datos['correo'],
            ':telefono' => $datos['telefono'],
            ':direccion' => $datos['direccion'],
            ':firma_digital' => $datos['firma_digital']
        ]);
    }

    public function listar()
    {
        $sql = "SELECT * FROM colaboradores
                ORDER BY nombre ASC";

        return $this->cn->query($sql)->fetchAll();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT *
                FROM colaboradores
                WHERE id = :id";

        $stmt = $this->cn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function actualizar($id, $datos)
    {
        $sql = "UPDATE colaboradores SET
                    cedula = :cedula,
                    nombre = :nombre,
                    apellido = :apellido,
                    fecha_nacimiento = :fecha_nacimiento,
                    correo = :correo,
                    telefono = :telefono,
                    direccion = :direccion,
                    firma_digital = :firma_digital
                WHERE id = :id";

        $stmt = $this->cn->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':cedula' => $datos['cedula'],
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':correo' => $datos['correo'],
            ':telefono' => $datos['telefono'],
            ':direccion' => $datos['direccion'],
            ':firma_digital' => $datos['firma_digital']
        ]);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM colaboradores
                WHERE id = :id";

        $stmt = $this->cn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function obtenerUltimoId()
    {
        return $this->cn->lastInsertId();
    }
}
?>