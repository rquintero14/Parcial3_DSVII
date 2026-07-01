<?php

class Sanitizador
{
    public static function texto($valor)
    {
        return htmlspecialchars(
            trim($valor),
            ENT_QUOTES,
            'UTF-8'
        );
    }

    public static function email($valor)
    {
        return filter_var(
            trim($valor),
            FILTER_SANITIZE_EMAIL
        );
    }

    public static function entero($valor)
    {
        return filter_var(
            $valor,
            FILTER_SANITIZE_NUMBER_INT
        );
    }

    public static function decimal($valor)
    {
        return filter_var(
            $valor,
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );
    }

    public static function fecha($valor)
    {
        return trim($valor);
    }

    public static function limpiarArray($datos)
    {
        $resultado = [];

        foreach ($datos as $key => $value) {

            if (is_array($value)) {

                $resultado[$key] = self::limpiarArray($value);

            } else {

                $resultado[$key] = self::texto($value);
            }
        }

        return $resultado;
    }
}
?>