# Reglas de desarrollo

Este proyecto sigue estas reglas para mantener consistencia y evitar regresiones:

## Base técnica

- Usar Laravel 11 y PHP 8.2 o superior.
- Mantener SQL Server (`sqlsrv`) como conexión principal.
- No cambiar la estructura de datos sin revisar migraciones y validaciones.

## Frontend

- No mezclar CSS ni JavaScript dentro de las vistas Blade.
- Usar `resources/css/` para estilos y `resources/js/` para lógica de interfaz.
- Mantener la interfaz del panel limpia, vertical y orientada a prueba de API.

## API y validación

- Validar siempre en backend y, cuando aplique, también en frontend.
- Las fechas de cita no pueden ser anteriores a la fecha actual.
- Si la fecha es hoy, la hora no puede ser menor a la hora actual.
- El teléfono debe tener 9 dígitos numéricos y ser único al crear.

## Listado y datos

- El listado de clientes debe mantenerse paginado.
- Evitar cargar todos los registros de una sola vez.
- Mostrar mensajes claros cuando no existan clientes.

## UI y experiencia

- Mostrar notificaciones visibles para errores y operaciones exitosas.
- Mantener un layout responsive para escritorio y móvil.
- Priorizar claridad sobre efectos decorativos excesivos.

## Cambio seguro

- Antes de cerrar un cambio, validar con `php artisan test`.
- Si se altera el frontend, validar también con `npm run build`.
- No eliminar cambios del usuario ni revertir archivos ajenos.