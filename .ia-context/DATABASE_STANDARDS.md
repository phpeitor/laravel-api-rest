# Estándares de Base de Datos — API REST PHP

Este documento complementa `.ia-context/DEVELOPMENT_RULES.md`. Mantener la persistencia existente del proyecto; no asumir Laravel, SQL Server ni un ORM.

## Tecnología y ubicación

El proyecto utiliza PDO con MySQL/MariaDB. La configuración de conexión se obtiene de variables de entorno; las migraciones SQL viven en `database/migrations/` y el ejecutor en `database/migrate.php`. Consultar la migración, `database/migrate.php` y `src/Database/Database.class.php` antes de cambiar el esquema o la conexión.

## Reglas

- No incorporar otra base de datos ni un ORM sin una necesidad aprobada y demostrable.
- Ejecutar todas las consultas con PDO prepared statements cuando incluyan valores dinámicos.
- No construir SQL concatenando datos procedentes de solicitudes.
- Validar entradas y límites de campos en la aplicación, sin depender solo de restricciones del cliente.
- Mantener consistentes SQL, migraciones, clases de acceso a datos y documentación.
- Para cambios estructurales, actualizar el SQL/migración y los datos de prueba pertinentes; describir el impacto y los pasos de ejecución en `README.md`.
- Evitar que errores PDO o SQL se filtren en respuestas HTTP; registrar o manejar el fallo conforme al patrón existente.
- No almacenar ni devolver secretos o contraseñas en texto plano. Nunca incluir credenciales reales en SQL versionado.

## Entorno

Las variables `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` y `DB_CHARSET` están documentadas en el README y en `.env.example`. El archivo `.env` local debe permanecer sin versionar. Ejecutar migraciones con `composer db:migrate` o `php database/migrate.php`.
