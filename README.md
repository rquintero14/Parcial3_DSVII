# Parcial 3 - iTECH Contrataciones

Aplicación PHP para gestión de colaboradores y perfiles laborales con firma digital.

## Características

- Registro de colaboradores con datos personales.
- Selección dinámica de ocupación, tipo de planilla y motivo de terminación.
- Estado civil integrado en el formulario de colaboradores.
- Perfil laboral con salario, fechas, estado de cargo y firma digital.
- Verificación de integridad de firma digital mediante OpenSSL.
- Exportación a Excel desde la vista de reporte.

## Requisitos

- PHP 8.x con extensión `pdo_mysql` y `openssl`
- MySQL / MariaDB
- WAMP (o servidor web equivalente)

## Instalación

1. Copia el proyecto dentro de la carpeta de tu servidor web (`www` en WAMP).
2. Asegúrate de que la carpeta `config/keys` tenga permisos de escritura.
3. Importa la base de datos desde `parcial_3.sql` en MySQL.
4. Verifica `config/Conexion.php` y ajusta las credenciales si es necesario.

## Configuración de base de datos

- Base de datos: `parcial_3`
- Usuario: `root`
- Contraseña: `` (vacía por defecto)

Si necesitas usar otras credenciales, edita `config/Conexion.php`:

```php
private static $host = "localhost";
private static $db = "parcial_3";
private static $user = "root";
private static $pass = "";
```

## Uso

- Abre el formulario de registro en el navegador:

  `http://localhost/Parcial_3_Quintero/vistas/formulario.php`

- Visualiza el reporte y la verificación de firma en:

  `http://localhost/Parcial_3_Quintero/vistas/reporte.php`

## Claves digitales

- La aplicación genera automáticamente los archivos de clave RSA en `config/keys`:
  - `private.pem`
  - `public.pem`

- Si la carpeta `config/keys` no existe, se crea sola en el primer uso.

## Estructura principal

- `config/Conexion.php` — Conexión PDO a la base de datos.
- `modelos/Colaborador.php` — Modelo para el registro de colaboradores.
- `modelos/PerfilLaboral.php` — Modelo para perfiles laborales y catálogos.
- `Controllers/ColaboradorController.php` — Lógica de negocio para colaboradores.
- `Controllers/PerfilLaboralController.php` — Lógica de negocio para perfiles.
- `Utils/FirmaDigital.php` — Generación/verificación de firma digital.
- `vistas/formulario.php` — Formulario de registro.
- `vistas/reporte.php` — Reporte de historial laboral.

## Notas

- El campo `estado_civil` ya está integrado en el formulario de colaboradores.
- El reporte muestra el estado civil junto con los datos del perfil laboral.
- Para depuración de OpenSSL, revisa `Utils/FirmaDigital.php`.
