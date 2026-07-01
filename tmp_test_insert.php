<?php
require_once 'config/Conexion.php';
require_once 'modelos/Colaborador.php';

try {
    $c = new Colaborador();
    $cn = Conexion::conectar();
    $cn->beginTransaction();

    $ok = $c->guardar([
        'cedula' => '12345678',
        'nombre' => 'Test',
        'apellido' => 'Usuario',
        'fecha_nacimiento' => '1990-01-01',
        'correo' => 'test@example.com',
        'telefono' => '123456789',
        'direccion' => 'Prueba',
        'firma_digital' => ''
    ]);

    echo $ok ? 'OK' : 'FAIL';

    $cn->rollBack();
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
