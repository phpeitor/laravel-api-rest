# Laravel API REST

Proyecto Laravel 11 para gestionar clientes y probar una API REST desde una interfaz web propia.

> Panel web y API REST para administración de clientes con validaciones, paginación y pruebas visuales.

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

| Method | Endpoint | Description | Body / Example |
| --- | --- | --- | --- |
| `GET` | `/api/clientes?page=1&per_page=5` | Lista clientes paginados | `curl -X GET "http://127.0.0.1:8000/api/clientes?page=1&per_page=5" -H "Accept: application/json"` |
| `GET` | `/api/clientes/{id}` | Consulta un cliente por ID | `curl -X GET "http://127.0.0.1:8000/api/clientes/1" -H "Accept: application/json"` |
| `POST` | `/api/clientes` | Crea un cliente nuevo | ```json
{
  "nombre": "Alejandro",
  "fecha_cita": "2026-05-10",
  "hora_cita": "09:30",
  "nombre_medico": "Dr. Perez",
  "nombre_centro": "Clinica Central",
  "telefono": "987654321"
}
``` |
| `PUT` | `/api/clientes/{id}` | Actualiza un cliente completo | ```json
{
  "nombre": "Alejandro Actualizado",
  "fecha_cita": "2026-05-11",
  "hora_cita": "10:15",
  "nombre_medico": "Dr. Trux",
  "nombre_centro": "Clinica Norte",
  "telefono": "912345678"
}
``` |
| `PATCH` | `/api/clientes` | Actualiza el estado de un cliente | ```json
{
  "id": 1,
  "estado": "CONFIRMADO"
}
``` |
| `DELETE` | `/api/clientes/{id}` | Elimina un cliente | `curl -X DELETE "http://127.0.0.1:8000/api/clientes/1" -H "Accept: application/json"` |

### Ejemplo rápido para Postman

```http
POST http://127.0.0.1:8000/api/clientes
Content-Type: application/json
Accept: application/json

{
  "nombre": "Alejandro",
  "fecha_cita": "2026-05-10",
  "hora_cita": "09:30",
  "nombre_medico": "Dr. Perez",
  "nombre_centro": "Clinica Central",
  "telefono": "987654321"
}
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
