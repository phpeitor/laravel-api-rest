# Laravel API REST

Proyecto Laravel 11 para gestionar clientes y probar una API REST desde una interfaz web propia.

## Resumen

- Backend: Laravel 11
- PHP: 8.2 o superior
- Base de datos: SQL Server (`sqlsrv`)
- Interfaz principal: panel web en `/`
- API principal: `/api/clientes`

## Qué incluye

- CRUD de clientes
- Validaciones de fecha, hora y teléfono
- Paginación del listado
- Notificaciones visuales de error y éxito
- Panel web para probar endpoints sin salir del navegador

## Endpoints

### 1) Listar clientes

```http
GET http://127.0.0.1:8000/api/clientes?page=1&per_page=5
```

Copiar y pegar en `curl`:

```cmd
curl -X GET "http://127.0.0.1:8000/api/clientes?page=1&per_page=5" \
	-H "Accept: application/json"
```

### 2) Consultar un cliente por ID

```http
GET http://127.0.0.1:8000/api/clientes/1
```

```cmd
curl -X GET "http://127.0.0.1:8000/api/clientes/1" ^
	-H "Accept: application/json"
```

### 3) Crear un cliente

```http
POST http://127.0.0.1:8000/api/clientes
Content-Type: application/json
Accept: application/json
```

```json
{
	"nombre": "Alejandro",
	"fecha_cita": "2026-05-10",
	"hora_cita": "09:30",
	"nombre_medico": "Dr. Perez",
	"nombre_centro": "Clinica Central",
	"telefono": "987654321"
}
```

```cmd
curl -X POST "http://127.0.0.1:8000/api/clientes" ^
	-H "Accept: application/json" ^
	-H "Content-Type: application/json" ^
	-d "{\"nombre\":\"Alejandro\",\"fecha_cita\":\"2026-05-10\",\"hora_cita\":\"09:30\",\"nombre_medico\":\"Dr. Perez\",\"nombre_centro\":\"Clinica Central\",\"telefono\":\"987654321\"}"
```

### 4) Actualizar un cliente completo

```http
PUT http://127.0.0.1:8000/api/clientes/1
Content-Type: application/json
Accept: application/json
```

```json
{
	"nombre": "Alejandro Actualizado",
	"fecha_cita": "2026-05-11",
	"hora_cita": "10:15",
	"nombre_medico": "Dr. Trux",
	"nombre_centro": "Clinica Norte",
	"telefono": "912345678"
}
```

```cmd
curl -X PUT "http://127.0.0.1:8000/api/clientes/1" ^
	-H "Accept: application/json" ^
	-H "Content-Type: application/json" ^
	-d "{\"nombre\":\"Alejandro Actualizado\",\"fecha_cita\":\"2026-05-11\",\"hora_cita\":\"10:15\",\"nombre_medico\":\"Dr. Trux\",\"nombre_centro\":\"Clinica Norte\",\"telefono\":\"912345678\"}"
```

### 5) Actualizar el estado de un cliente

```http
PATCH http://127.0.0.1:8000/api/clientes
Content-Type: application/json
Accept: application/json
```

```json
{
	"id": 1,
	"estado": "CONFIRMADO"
}
```

```cmd
curl -X PATCH "http://127.0.0.1:8000/api/clientes" ^
	-H "Accept: application/json" ^
	-H "Content-Type: application/json" ^
	-d "{\"id\":1,\"estado\":\"CONFIRMADO\"}"
```

### 6) Eliminar un cliente

```http
DELETE http://127.0.0.1:8000/api/clientes/1
Accept: application/json
```

```cmd
curl -X DELETE "http://127.0.0.1:8000/api/clientes/1" ^
	-H "Accept: application/json"
```

## Ejecución local

```cmd
php artisan optimize:clear
php artisan migrate
php artisan serve
```

## Frontend

```cmd
npm install
npm run build
```

## Notas importantes

- El proyecto usa SQL Server por defecto en `.env`.
- El panel web se apoya en archivos dedicados en `resources/css/` y `resources/js/`.
- El listado está paginado desde el backend para evitar cargar todo de golpe.

## Documentación de desarrollo

Revisa el archivo [`DEVELOPMENT_RULES.md`](DEVELOPMENT_RULES.md) antes de hacer cambios grandes.
