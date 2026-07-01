<?php

class Validacion
{
    public static function requerido($valor)
    {
        return !empty(trim($valor));
    }

    public static function email($correo)
    {
        return filter_var(
            $correo,
            FILTER_VALIDATE_EMAIL
        );
    }

    public static function entero($valor)
    {
        return filter_var(
            $valor,
            FILTER_VALIDATE_INT
        ) !== false;
    }

    public static function decimal($valor)
    {
        return is_numeric($valor);
    }

    public static function fecha($fecha)
    {
        $d = DateTime::createFromFormat(
            'Y-m-d',
            $fecha
        );

        return $d &&
               $d->format('Y-m-d') === $fecha;
    }

    public static function identidad($identidad)
    {
        return preg_match(
            '/^[0-9\-]{6,20}$/',
            $identidad
        );
    }

    public static function celular($celular)
    {
        return preg_match(
            '/^[0-9]{7,15}$/',
            $celular
        );
    }

    public static function salario($salario)
    {
        return $salario > 0;
    }

    public static function longitud(
        $texto,
        $min,
        $max
    )
    {
        $longitud = strlen(trim($texto));

        return (
            $longitud >= $min &&
            $longitud <= $max
        );
    }
}
?>