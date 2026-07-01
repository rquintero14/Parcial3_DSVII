<?php

class FirmaDigital
{
    private static $clavePrivada =
        "PARCIAL_RRHH_2026_UTP";

    public static function generarFirma($datos)
    {
        if (is_array($datos)) {

            ksort($datos);

            $datos = json_encode(
                $datos,
                JSON_UNESCAPED_UNICODE
            );
        }

        return hash_hmac(
            'sha256',
            $datos,
            self::$clavePrivada
        );
    }

    public static function verificarFirma(
        $datos,
        $firma
    )
    {
        $firmaGenerada =
            self::generarFirma($datos);

        return hash_equals(
            $firmaGenerada,
            $firma
        );
    }

    public static function generarFirmaPerfil(
        $colaboradorId,
        $ocupacionId,
        $tipoPlanillaId,
        $salario,
        $fechaInicio
    )
    {
        $cadena =
            $colaboradorId . "|" .
            $ocupacionId . "|" .
            $tipoPlanillaId . "|" .
            $salario . "|" .
            $fechaInicio;

        return self::generarFirma($cadena);
    }
}
?>