## Laravel API Rest 🐘
[![forthebadge](http://forthebadge.com/images/badges/not-a-bug-a-feature.svg))](https://www.linkedin.com/in/drphp/)
[![forthebadge](http://forthebadge.com/images/badges/built-with-love.svg)](https://www.linkedin.com/in/drphp/)

[![Video](https://img.youtube.com/vi/qgyMLh8dh5g/0.jpg)](https://www.youtube.com/watch?v=qgyMLh8dh5g)  

[![Video Demo](https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube)](https://www.youtube.com/watch?v=qgyMLh8dh5g)

## Contenido

- [Funcionalidades](#funcionalidades)
- [Requisitos](#requisitos)
- [Instalación local](#instalación-local)
- [Configuración](#configuración)
- [Autenticación](#autenticación)
- [API v2](#api-v2)
- [Consola web](#consola-web)
- [Pruebas y comandos](#pruebas-y-comandos)
- [Estructura](#estructura)
- [Utilidad opcional para VS Code](#utilidad-opcional-para-vs-code-windows)

## Funcionalidades

- CRUD de clientes y consulta/actualización del estado.
- Validación en el servidor de campos, fechas de cita y teléfono al crear.
- Listado ordenado por ID descendente y paginado; el tamaño de página se limita a 25.
- Consola web adaptable con catálogo de endpoints, playground, tabla paginada y visor de respuestas HTTP/JSON.
- Autenticación Bearer requerida en cada operación de la API y límite de 60 solicitudes por minuto.

## Requisitos

- PHP 8.2 o superior con las extensiones que requiere Laravel y los drivers `sqlsrv` y `pdo_sqlsrv`.
- Microsoft ODBC Driver para SQL Server, compatible con los drivers PHP instalados.
- Composer 2.
- Node.js y npm.
- Una instancia SQL Server accesible y una base de datos creada para este proyecto.

Comprueba la versión de PHP y que los drivers estén cargados:

```powershell
php -v
php -m | Select-String 'sqlsrv|pdo_sqlsrv'
```

## Instalación local

Ejecuta desde la raíz del repositorio en PowerShell:

```powershell
composer install
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate
npm ci
```

Configura las variables de base de datos y `API_TOKEN` según [Configuración](#configuración) y después ejecuta:

```powershell
php artisan config:clear
php artisan migrate
npm run build
```

Para desarrollo, inicia Laravel y Vite en terminales separadas:

```powershell
php artisan serve
```

```powershell
npm run dev
```

Abre `http://127.0.0.1:8000`. Para servir los recursos compilados, detén Vite y mantén `npm run build` actualizado.

Las migraciones crean las tablas de la aplicación; la base de datos de SQL Server debe existir antes de ejecutarlas.

## Configuración

`.env.example` documenta las variables. Edita el `.env` local —ignorado por Git— con los datos de tu entorno:

```dotenv
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=BD_CLINICA
DB_USERNAME=sa
DB_PASSWORD=CAMBIA_ESTA_CLAVE
DB_ENCRYPT=yes
DB_TRUST_SERVER_CERTIFICATE=false

API_TOKEN=REEMPLAZA_POR_UN_SECRETO_ALEATORIO
```

Adapta el host, la base, el usuario y la contraseña. Si ODBC 18 rechaza un certificado autofirmado en un entorno local controlado, `DB_TRUST_SERVER_CERTIFICATE=true` permite confiar en el certificado presentado mientras mantiene el cifrado; usa un certificado de confianza y mantén la verificación habilitada en producción.

No publiques `.env`, contraseñas ni tokens. Los valores de ejemplo no deben reutilizarse en despliegues.

## Autenticación

Genera un token aleatorio de 32 bytes o más y configura su valor como `API_TOKEN` en `.env`:

```powershell
php -r "echo bin2hex(random_bytes(32));"
```

Después de cambiar la variable, limpia la configuración almacenada en caché:

```powershell
php artisan config:clear
```

Todas las solicitudes a `/api/v2/clientes` deben incluir:

```http
Authorization: Bearer TU_API_TOKEN
Accept: application/json
```

La consola solicita el token en cada operación del playground y por separado para el explorador paginado. Solo lo envía en el encabezado de esa petición; no lo incluye en el cuerpo ni lo persiste en el almacenamiento del navegador.

El backend compara el token en tiempo constante. Devuelve `401` si falta o no coincide, y falla cerrado con `503` si `API_TOKEN` no está configurado. Las rutas también están limitadas a 60 solicitudes por minuto.

Esta implementación utiliza una clave compartida sin caducidad ni permisos por usuario; está pensada para desarrollo e integraciones internas. Para producción, sirve la aplicación sobre HTTPS, rota el secreto y considera autenticación individual con caducidad y autorización por recurso.

## API v2

**Base URL local:** `http://127.0.0.1:8000/api/v2`

La versión forma parte de la ruta. Usa `/api/v2/clientes`; la ruta anterior sin versión (`/api/clientes`) no está registrada.

| Método | Ruta | Descripción | Éxito |
| --- | --- | --- | --- |
| `GET` | `/clientes?page=1&per_page=5` | Lista clientes paginados | `200` |
| `GET` | `/clientes/{id}` | Obtiene un cliente | `200` |
| `POST` | `/clientes` | Crea un cliente | `201` |
| `PUT` | `/clientes/{id}` | Actualiza los datos del cliente | `200` |
| `PATCH` | `/clientes` | Actualiza el estado de un cliente pendiente | `200` |
| `DELETE` | `/clientes/{id}` | Elimina un cliente | `200` |

Todas las rutas requieren `Authorization: Bearer TU_API_TOKEN` y aceptan `Accept: application/json`. Las operaciones con cuerpo reciben JSON.

### Listar clientes

Parámetros: `page` (página solicitada) y `per_page` (por defecto `5`, máximo `25`). Los resultados se ordenan por ID descendente.

```powershell
$baseUrl = 'http://127.0.0.1:8000/api/v2'
$token = 'TU_API_TOKEN'
$headers = @{
    Accept = 'application/json'
    Authorization = "Bearer $token"
}

Invoke-RestMethod -Uri "$baseUrl/clientes?page=1&per_page=5" -Headers $headers
```

La respuesta contiene `clientes` y `pagination` (`current_page`, `last_page`, `per_page`, `total`, `from`, `to`).

### Consultar un cliente

```powershell
Invoke-RestMethod -Uri "$baseUrl/clientes/1" -Headers $headers
```

La operación individual devuelve el objeto bajo `cliente`; sus nombres de propiedades históricos (`Id`, `Paciente`, `Medico`, `FechaCita`, `HoraCita`, `CentroSalud`, `Telefono`, `Estado`) difieren del listado.

### Crear un cliente

```powershell
$cliente = @{
    nombre = 'Ana García'
    fecha_cita = '2030-06-15'
    hora_cita = '09:30'
    nombre_medico = 'Dra. Pérez'
    nombre_centro = 'Clínica Central'
    telefono = '900000001'
} | ConvertTo-Json

Invoke-RestMethod -Method Post `
    -Uri "$baseUrl/clientes" `
    -Headers $headers `
    -ContentType 'application/json' `
    -Body $cliente
```

El teléfono debe tener exactamente nueve dígitos y ser único al crear. La fecha no puede ser anterior al día actual; si la cita es hoy, la hora debe ser igual o posterior a la hora actual. Los nombres de campos obligatorios se muestran en el ejemplo.

### Actualizar un cliente

`PUT` reemplaza los campos del cliente. La validación requiere nombre, fecha, hora, médico, centro y un teléfono de nueve dígitos.

```powershell
$clienteActualizado = @{
    nombre = 'Ana García'
    fecha_cita = '2030-06-16'
    hora_cita = '10:15'
    nombre_medico = 'Dra. Pérez'
    nombre_centro = 'Clínica Norte'
    telefono = '900000002'
} | ConvertTo-Json

Invoke-RestMethod -Method Put `
    -Uri "$baseUrl/clientes/1" `
    -Headers $headers `
    -ContentType 'application/json' `
    -Body $clienteActualizado
```

### Actualizar el estado

`PATCH /clientes` recibe un `id` y un `estado` de hasta 10 caracteres. Solo actualiza clientes que actualmente estén en estado `PENDIENTE`.

```powershell
$cambioEstado = @{ id = 1; estado = 'CONFIRMADO' } | ConvertTo-Json

Invoke-RestMethod -Method Patch `
    -Uri "$baseUrl/clientes" `
    -Headers $headers `
    -ContentType 'application/json' `
    -Body $cambioEstado
```

### Eliminar un cliente

```powershell
Invoke-RestMethod -Method Delete -Uri "$baseUrl/clientes/1" -Headers $headers
```

### Códigos de respuesta

| Código | Uso |
| --- | --- |
| `200` | Consulta o modificación correcta |
| `201` | Cliente creado |
| `400` | Datos inválidos o transición de estado no permitida |
| `401` | Token Bearer ausente o inválido |
| `404` | Cliente no encontrado |
| `405` | Método HTTP no admitido para la ruta |
| `429` | Límite de solicitudes excedido |
| `503` | Token de API no configurado en el servidor |
| `500` | Error interno del servidor |

Los errores de validación incluyen un `message` y un objeto `errors` con el detalle por campo. El status HTTP es la fuente autoritativa del resultado.

## Consola web

La página `/` incluye navegación por secciones, catálogo de rutas, playground para cada operación y explorador paginado. Cada operación tiene su campo Bearer; el listado tiene uno independiente que se utiliza para la consulta y paginación. El menú lateral puede contraerse y expandirse.

La interfaz usa archivos separados para Blade, CSS y JavaScript. Los datos recibidos de la API se insertan como texto y las respuestas JSON se pueden copiar desde el panel.

## Pruebas y comandos

```powershell
# Suite completa
php artisan test

# Pruebas de autenticación Bearer
php artisan test --filter=ApiTokenTest

# Compilar frontend para producción
npm run build

# Listar las rutas de la API v2
php artisan route:list --path=api/v2/clientes -v

# Limpiar cachés de Laravel
php artisan optimize:clear
```

## Estructura

```text
app/Http/Controllers/Api/ClienteController.php  Controladores de API
app/Http/Middleware/EnsureApiToken.php          Autenticación Bearer
app/Models/Cliente.php                          Modelo Eloquent
database/migrations/                            Esquema de SQL Server
resources/views/dashboard.blade.php             Consola web
resources/css/app.css                           Estilos del panel
resources/js/api-dashboard.js                   Interacciones y solicitudes
routes/api.php                                  Rutas versionadas /api/v2
routes/web.php                                  Ruta del panel
tests/Feature/ApiTokenTest.php                  Pruebas de autenticación
```

## Utilidad opcional para VS Code (Windows)

El proyecto incluye el comando `php artisan vscode:update-font` para configurar una fuente desde `resources/fonts` y actualizar `editor.fontFamily` en la configuración de VS Code. El comando es específico de Windows. Para ver opciones:

```powershell
php artisan vscode:update-font --list
```

Para instalar/seleccionar una fuente, inicia el flujo interactivo con:

```powershell
php artisan vscode:update-font --install
```
