<?php

class Conexion
{
    private static $host = "localhost";
    private static $db = "parcial_3";
    private static $user = "root";
    private static $pass = "";
    private static $conexion = null;

    public static function conectar()
    {
        if (self::$conexion === null) {

            try {

                self::$conexion = new PDO(
                    "mysql:host=" . self::$host .
                    ";dbname=" . self::$db .
                    ";charset=utf8mb4",
                    self::$user,
                    self::$pass
                );

                self::$conexion->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );

                self::$conexion->setAttribute(
                    PDO::ATTR_DEFAULT_FETCH_MODE,
                    PDO::FETCH_ASSOC
                );

            } catch (PDOException $e) {

                die("Error de conexión: " . $e->getMessage());

            }
        }

        return self::$conexion;
    }
}
?>