## Laravel API Rest 🐘
[![forthebadge](http://forthebadge.com/images/badges/for-robots.svg)](https://www.linkedin.com/in/drphp/)
[![forthebadge](http://forthebadge.com/images/badges/built-with-love.svg)](https://www.linkedin.com/in/drphp/)

[![Video](https://img.youtube.com/vi/qgyMLh8dh5g/0.jpg)](https://www.youtube.com/watch?v=qgyMLh8dh5g)  

[![Video Demo](https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube)](https://www.youtube.com/watch?v=qgyMLh8dh5g)


## Vista rápida

| Campo | Valor |
| --- | --- |
| Framework | Laravel 11 |
| PHP | 8.2 o superior |
| Base de datos | SQL Server (`sqlsrv`) |
| Panel web | `http://127.0.0.1:8000` |
| API principal | `/api/clientes` |

## Qué incluye

- CRUD completo de clientes
- Validación de fecha y hora de cita
- Validación de teléfono numérico y único
- Listado paginado
- Notificaciones visuales para errores y éxito
- Panel web moderno para probar la API

## Inicio rápido

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

## Variables de entorno

El proyecto usa SQL Server por defecto. Revisa estos valores en `.env`:

```dotenv
DB_CONNECTION=sqlsrv
DB_HOST=192.168.1.250
DB_PORT=1433
DB_DATABASE=BD_TEST
DB_USERNAME=sa
DB_PASSWORD=********
```

## Endpoints

### Tabla de referencia

| Method | Endpoint | Description | Copy/Paste |
| --- | --- | --- | --- |
| `GET` | `/api/clientes?page=1&per_page=5` | Lista clientes paginados | `curl -X GET "http://127.0.0.1:8000/api/clientes?page=1&per_page=5" -H "Accept: application/json"` |
| `GET` | `/api/clientes/{id}` | Consulta un cliente por ID | `curl -X GET "http://127.0.0.1:8000/api/clientes/1" -H "Accept: application/json"` |
| `POST` | `/api/clientes` | Crea un cliente nuevo | Ver ejemplo JSON abajo |
| `PUT` | `/api/clientes/{id}` | Actualiza un cliente completo | Ver ejemplo JSON abajo |
| `PATCH` | `/api/clientes` | Actualiza el estado de un cliente | Ver ejemplo JSON abajo |
| `DELETE` | `/api/clientes/{id}` | Elimina un cliente | `curl -X DELETE "http://127.0.0.1:8000/api/clientes/1" -H "Accept: application/json"` |

### Ejemplos copiables

#### Listar clientes

```http
GET http://127.0.0.1:8000/api/clientes?page=1&per_page=5
Accept: application/json
```

#### Consultar cliente por ID

```http
GET http://127.0.0.1:8000/api/clientes/1
Accept: application/json
```

#### Crear cliente

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

#### Actualizar cliente completo

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

#### Actualizar estado

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

#### Eliminar cliente

```http
DELETE http://127.0.0.1:8000/api/clientes/1
Accept: application/json
```

```cmd
curl -X DELETE "http://127.0.0.1:8000/api/clientes/1" ^
  -H "Accept: application/json"
```

## Panel web

- La página principal está en `/`
- El listado se carga desde la API con paginación
- Las alertas de error y éxito aparecen en pantalla
- Los formularios de creación, consulta, actualización y eliminación están listos para pruebas rápidas

## Notas importantes

- El proyecto usa archivos dedicados en `resources/css/` y `resources/js/`.
- No se mezcla CSS/JS dentro de Blade.
- El listado de clientes se pagina desde el backend.
- La app valida fechas y horas antes de crear un cliente.

## Desarrollo

Revisa [`DEVELOPMENT_RULES.md`](DEVELOPMENT_RULES.md) antes de hacer cambios grandes.

## Comandos útiles

```cmd
php artisan test
npm run build
php artisan optimize:clear
```
