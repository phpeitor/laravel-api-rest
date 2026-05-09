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

- `GET /api/clientes` Lista clientes paginados
- `GET /api/clientes/{id}` Consulta un cliente
- `POST /api/clientes` Crea un cliente
- `PUT /api/clientes/{id}` Actualiza un cliente
- `PATCH /api/clientes` Actualiza el estado de un cliente
- `DELETE /api/clientes/{id}` Elimina un cliente

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
