# Roles de trabajo asistido — API REST PHP

Antes de asumir un rol, seguir `.ia-context/DEVELOPMENT_RULES.md`, que define el flujo iterativo y la coordinación entre agentes de IA. Estas responsabilidades complementan las reglas generales.

## Análisis

- Leer primero `README.md`, el endpoint afectado, las clases relacionadas y el SQL/migrador pertinente.
- Confirmar los contratos reales y detectar discrepancias documentales antes de proponer cambios incompatibles.
- Mantener el alcance de API REST CRUD en PHP nativo, salvo solicitud explícita de ampliar funcionalidades.

## Backend PHP

- Mantener endpoints en `api/` y lógica reutilizable/acceso PDO en `src/`; `includes/` conserva cargadores de compatibilidad.
- Validar método, autenticación y entradas; preservar el contrato HTTP; responder JSON y códigos adecuados.
- Aplicar PSR-12, prepared statements y manejo seguro de errores.
- Evitar framework, ORM o dependencias nuevas sin necesidad concreta.

## Datos

- Revisar y mantener coordinados `src/`, `database/migrations/` y el esquema de la base.
- Proponer migración/documentación con cambios estructurales.
- Nunca incluir credenciales reales, contraseñas en texto plano ni secretos en archivos versionados.

## Interfaz de prueba

- Mantener `index.html`, `assets/css/` y `assets/js/` separados.
- Consumir únicamente rutas y contratos realmente implementados.
- Comunicar de forma accesible errores de red, autenticación y validación.

## QA y entrega

- Ejecutar `php -l` para PHP modificado y las comprobaciones pertinentes disponibles.
- Para cambios API, verificar solicitudes válidas y casos de error relevantes siempre que el entorno permita ejecutarlas.
- Actualizar `README.md` cuando cambie el uso, la configuración, el contrato o las migraciones.
- Informar archivos/cambios y pruebas realizadas, aclarando las pruebas que no pudieron ejecutarse.
