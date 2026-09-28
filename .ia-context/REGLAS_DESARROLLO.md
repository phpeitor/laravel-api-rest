# Guía de desarrollo — API REST PHP

Este documento resume las reglas generales de `.ia-context/DEVELOPMENT_RULES.md`, que es la referencia principal para el flujo de trabajo y la colaboración entre agentes de IA. Los estándares específicos de backend, frontend y base de datos complementan estas reglas.

## Propósito

API REST de ejemplo para gestionar clientes/usuarios mediante PHP nativo, PDO y MySQL/MariaDB. Incluye endpoints CRUD en `api/`, clases de aplicación en `src/`, autenticación JWT, configuración por entorno y una interfaz web básica para probar solicitudes.

La implementación y el `README.md` son la referencia para rutas, campos y contratos. Si discrepan, inspeccionar el código y reportar la discrepancia antes de hacer cambios incompatibles.

## Flujo de una solicitud

```text
Cliente HTTP / interfaz de prueba
  -> endpoint PHP en api/
  -> validar método, autenticación y datos
  -> lógica/acceso a datos en src/ mediante PDO
  -> respuesta JSON con código HTTP adecuado
```

## Convenciones de trabajo

- Mantener PHP nativo; no añadir framework ni nuevas dependencias sin justificación concreta.
- Seguir PSR-12 y respetar la estructura existente.
- Consultar SQL y migraciones en `database/migrations/` antes de modificar el modelo de datos; ejecutar migraciones con `composer db:migrate`.
- Usar consultas preparadas, validar entradas en servidor y no exponer errores internos.
- Mantener secretos fuera del repositorio; documentar variables en `.env.example`.
- Para cambios en la interfaz, conservar HTML, CSS y JavaScript en archivos separados.
- Actualizar `README.md` cuando cambien endpoints, configuración, instalación o migraciones.
- Ejecutar `php -l` en los archivos PHP modificados y probar los flujos afectados.

## Alcance

No añadir funcionalidades ajenas a la API CRUD existente salvo solicitud explícita. Antes de cambiar una ruta, campo, autenticación o formato de respuesta, confirmar el contrato implementado y documentado.
