# Reglas de desarrollo y colaboración con agentes de IA

Estas reglas son la referencia común para personas y agentes que trabajen en este repositorio. Si otro documento de contexto contradice el código, `composer.json` o `README.md`, inspecciona la implementación actual y señala la discrepancia antes de hacer cambios incompatibles.

## Contexto técnico del proyecto

- API REST de ejemplo implementada en PHP nativo, PDO y MySQL/MariaDB; no asumir que es una aplicación Laravel.
- Mantener la estructura existente: endpoints en `api/`, lógica reutilizable en `src/`, compatibilidad en `includes/`, interfaz de prueba en `index.html` y `assets/`, y migraciones en `database/migrations/`.
- Respetar la versión de PHP y dependencias definidas en `composer.json` (PHP 8.3+).
- Consultar `README.md`, el endpoint/clase afectados y las migraciones pertinentes antes de cambiar contratos o datos.
- La implementación existente es la referencia para rutas, campos y respuestas de la API.

## Reglas generales

- Mantener PHP nativo y las dependencias actuales; añadir framework, ORM o dependencias solo con justificación concreta.
- Seguir PSR-12 y los patrones de la estructura existente.
- Preservar contratos HTTP, autenticación y comportamiento actual salvo que el requerimiento solicite cambiarlos explícitamente.
- Validar y normalizar las entradas en el servidor; usar consultas preparadas PDO para valores dinámicos.
- Responder JSON con códigos HTTP adecuados y no revelar SQL, trazas, rutas, credenciales ni detalles internos.
- Mantener secretos fuera del repositorio; actualizar `.env.example` solo con valores ficticios al documentar variables nuevas.
- Mantener HTML, CSS y JavaScript separados; usar JavaScript nativo salvo necesidad concreta.
- Mantener la interfaz de prueba accesible y responsive, con estados claros de carga, éxito y error, y alineada con los contratos reales de la API.
- Al cambiar esquema, coordinar migraciones, código y documentación; no alterar datos o migraciones sin revisar su impacto.
- Actualizar `README.md` si cambian endpoints, configuración, instalación, contratos o migraciones.
- No borrar ni revertir cambios de usuario ajenos al alcance solicitado.

## Flujo de trabajo iterativo para agentes de IA

1. **Entender:** resume el objetivo y el alcance; inspecciona las instrucciones y archivos relevantes antes de editar. No infieras requisitos que contradigan el proyecto.
2. **Planificar:** para cambios de varias partes, identifica archivos/componentes afectados y dependencias. Mantén el cambio acotado al objetivo.
3. **Implementar:** realiza un paso coherente, siguiendo las convenciones existentes. No reemplaces archivos completos si basta con un cambio localizado.
4. **Revisar:** inspecciona el diff para detectar regresiones, cambios accidentales, inconsistencias de contrato y documentación desactualizada.
5. **Verificar:** ejecuta las comprobaciones pertinentes disponibles. Para PHP modificado, ejecuta `php -l`; para cambios de API, comprueba casos exitosos y errores relevantes cuando el entorno lo permita; para interfaz, prueba las interacciones afectadas y revisa errores de consola.
6. **Iterar:** si una comprobación falla, analiza la causa, corrige lo relacionado con el cambio y vuelve a ejecutar la verificación afectada. No ocultes fallos ni declares pruebas no ejecutadas como exitosas.
7. **Entregar:** informa brevemente qué cambió, qué verificaciones se ejecutaron y sus resultados. Declara explícitamente cualquier comprobación que no pudo ejecutarse y el motivo.

## Coordinación entre agentes

- Antes de delegar, proporcionar al agente objetivo, contexto relevante, límites del alcance, rutas/documentos a consultar y verificaciones esperadas.
- Dividir el trabajo en unidades independientes con responsables y archivos claramente delimitados; evitar que varios agentes editen simultáneamente los mismos archivos.
- Tratar resultados de otros agentes como propuestas que deben revisarse contra el código, las reglas del proyecto y el diff final.
- Integrar cambios sin descartar trabajo existente; resolver conflictos preservando el objetivo y los contratos establecidos.
- Si falta información que pueda cambiar sustancialmente la solución, inspeccionar lo disponible y pedir aclaración antes de asumir un cambio incompatible.

## Fuera de alcance

No añadir funcionalidades ajenas a la API CRUD existente (por ejemplo, procesamiento de imágenes o servicios externos) salvo solicitud explícita.
