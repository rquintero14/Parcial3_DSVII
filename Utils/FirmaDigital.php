<?php

class FirmaDigital
{
    private static function rutaClavePrivada()
    {
        return __DIR__ . '/../config/keys/private.pem';
    }

    private static function rutaClavePublica()
    {
        return __DIR__ . '/../config/keys/public.pem';
    }

    private static function asegurarClaves()
    {
        $privateKeyPath = self::rutaClavePrivada();
        $publicKeyPath = self::rutaClavePublica();
        $directory = dirname($privateKeyPath);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (file_exists($privateKeyPath) && file_exists($publicKeyPath)) {
            $privateKeyPem = @file_get_contents($privateKeyPath);
            $publicKeyPem = @file_get_contents($publicKeyPath);

            if ($privateKeyPem !== false && $publicKeyPem !== false) {
                $privateKey = openssl_pkey_get_private($privateKeyPem);
                $publicKey = openssl_pkey_get_public($publicKeyPem);

                if ($privateKey !== false && $publicKey !== false) {
                    return;
                }
            }

            @unlink($privateKeyPath);
            @unlink($publicKeyPath);
        }

        $config = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $keyResource = openssl_pkey_new($config);

        if ($keyResource === false) {
            throw new Exception('Error generando la clave RSA: ' . self::obtenerErroresOpenSSL());
        }

        if (!openssl_pkey_export($keyResource, $privateKeyPem)) {
            throw new Exception('Error exportando la clave privada RSA: ' . self::obtenerErroresOpenSSL());
        }

        $publicKeyDetails = openssl_pkey_get_details($keyResource);

        if ($publicKeyDetails === false || empty($publicKeyDetails['key'])) {
            throw new Exception('Error obteniendo la clave pública RSA: ' . self::obtenerErroresOpenSSL());
        }

        $publicKeyPem = $publicKeyDetails['key'];

        if (file_put_contents($privateKeyPath, $privateKeyPem) === false) {
            throw new Exception('No se pudo guardar la clave privada en: ' . $privateKeyPath);
        }

        if (file_put_contents($publicKeyPath, $publicKeyPem) === false) {
            throw new Exception('No se pudo guardar la clave pública en: ' . $publicKeyPath);
        }
    }

    private static function obtenerClavePrivada()
    {
        self::asegurarClaves();

        $privateKeyPem = @file_get_contents(self::rutaClavePrivada());

        if ($privateKeyPem === false) {
            throw new Exception('No se pudo leer la clave privada.');
        }

        $privateKey = openssl_pkey_get_private($privateKeyPem);

        if ($privateKey === false) {
            throw new Exception('Clave privada inválida: ' . self::obtenerErroresOpenSSL());
        }

        return $privateKey;
    }

    private static function obtenerClavePublica()
    {
        self::asegurarClaves();

        $publicKeyPem = @file_get_contents(self::rutaClavePublica());

        if ($publicKeyPem === false) {
            throw new Exception('No se pudo leer la clave pública.');
        }

        $publicKey = openssl_pkey_get_public($publicKeyPem);

        if ($publicKey === false) {
            throw new Exception('Clave pública inválida: ' . self::obtenerErroresOpenSSL());
        }

        return $publicKey;
    }

    private static function obtenerErroresOpenSSL()
    {
        $errores = [];

        while ($error = openssl_error_string()) {
            $errores[] = $error;
        }

        return implode(' | ', $errores) ?: 'Error OpenSSL desconocido.';
    }

    public static function generarCadenaPerfil(
        $colaboradorId,
        $ocupacionId,
        $tipoPlanillaId,
        $salario,
        $fechaInicio
    )
    {
        $salarioFormateado = number_format(
            (float) $salario,
            2,
            '.',
            ''
        );

        return implode('|', [
            trim((string) $colaboradorId),
            trim((string) $ocupacionId),
            trim((string) $tipoPlanillaId),
            $salarioFormateado,
            trim((string) $fechaInicio)
        ]);
    }

    public static function generarFirmaPerfil(
        $colaboradorId,
        $ocupacionId,
        $tipoPlanillaId,
        $salario,
        $fechaInicio
    )
    {
        $datos = self::generarCadenaPerfil(
            $colaboradorId,
            $ocupacionId,
            $tipoPlanillaId,
            $salario,
            $fechaInicio
        );

        $privateKey = self::obtenerClavePrivada();
        openssl_sign($datos, $firmaBinaria, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($firmaBinaria);
    }

    public static function verificarFirma(
        $datos,
        $firma
    )
    {
        $publicKey = self::obtenerClavePublica();
        $firmaBinaria = base64_decode($firma);

        return openssl_verify($datos, $firmaBinaria, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }
}
?>