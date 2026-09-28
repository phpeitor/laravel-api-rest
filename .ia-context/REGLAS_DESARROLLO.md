# Guía de desarrollo — API REST PHP

Este documento es la referencia principal de desarrollo y colaboración para personas y agentes de IA. Los estándares de backend, frontend y base de datos lo complementan. Si hay discrepancias con la implementación, `composer.json` o `README.md`, inspeccionar el código y señalar la diferencia antes de hacer cambios incompatibles.

## Flujo de trabajo con agentes de IA

1. **Entender:** resumir el objetivo y revisar `README.md`, instrucciones del repositorio y archivos relacionados antes de editar.
2. **Planificar:** delimitar el alcance, los archivos afectados y las verificaciones necesarias para cambios de varias partes.
3. **Implementar:** trabajar en cambios acotados y coherentes con la estructura existente; evitar sobrescribir cambios ajenos.
4. **Revisar:** inspeccionar el diff para detectar regresiones, cambios accidentales y documentación o contratos desactualizados.
5. **Verificar e iterar:** ejecutar las comprobaciones pertinentes; corregir los fallos relacionados con el cambio y repetir las pruebas afectadas. No presentar pruebas no ejecutadas como exitosas.
6. **Entregar:** informar qué cambió, las verificaciones y resultados, y las comprobaciones que no pudieron ejecutarse con su motivo.

### Coordinación entre agentes

- Al delegar, especificar objetivo, contexto, límites, archivos a consultar y verificaciones esperadas.
- Dividir tareas en unidades independientes y evitar ediciones simultáneas de los mismos archivos.
- Revisar el trabajo recibido contra el código y las reglas del proyecto antes de integrarlo.
- Resolver conflictos preservando los cambios existentes y el contrato implementado.

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
