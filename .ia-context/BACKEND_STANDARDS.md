# Estándares Backend — API REST PHP

Aplican a la API PHP nativa del proyecto descrita en `.ia-context/DEVELOPMENT_RULES.md`; no asumir el uso de Laravel u otro framework.

## Diseño

- Implementar endpoints HTTP en `api/` y reutilizar las clases/lógica de `src/`. `includes/` contiene cargadores de compatibilidad.
- Mantener la lógica de transporte separada del acceso a datos; evitar duplicación entre endpoints.
- Usar PHP nativo y las dependencias ya declaradas en Composer. Evitar abstracciones y dependencias innecesarias.
- Seguir PSR-12 y el nivel de PHP definido en `composer.json` (PHP 8.3+).
- Los endpoints auxiliares de desarrollo deben vivir bajo `api/dev/` y responder únicamente con `APP_ENV=local`; no exponer credenciales en producción.

## Contrato HTTP

- Preservar rutas, métodos, nombres de campos y formato de salida existentes salvo que el requerimiento pida un cambio explícito.
- Rechazar métodos no admitidos con `405 Method Not Allowed` cuando sea aplicable.
- Validar y normalizar los datos recibidos en el servidor; no confiar en la interfaz cliente.
- Responder JSON con `Content-Type: application/json; charset=utf-8` y códigos HTTP apropiados: `400` para entrada inválida, `401`/`403` para problemas de autenticación/autorización, `404` para recursos ausentes, `405` para método no soportado y `500` para fallos internos.
- Mantener respuestas de error útiles y consistentes sin revelar SQL, trazas, rutas, secretos ni detalles internos.
- No incluir claves, contraseñas ni hashes en las respuestas.

## Base de datos

- Utilizar la conexión PDO compartida por el proyecto.
- Usar prepared statements para todos los valores externos; no concatenar entrada en SQL.
- Aplicar validación de campos antes de ejecutar consultas y gestionar los resultados/errores explícitamente.
- Mantener cambios de esquema y datos de prueba en `database/migrations/`, actualizando la migración correspondiente.

## Autenticación y configuración

- Seguir el mecanismo JWT existente y su librería `firebase/php-jwt`; no implementar criptografía propia.
- Leer configuración sensible desde el entorno con el flujo existente basado en `vlucas/phpdotenv`.
- `api/config.php` solo puede exponer configuración pública (`APP_NAME`, `APP_URL`, `API_BASE_URL`); nunca devolver variables privadas.
- No versionar `.env`, tokens reales ni credenciales. Mantener solo ejemplos ficticios en `.env.example`.

## Verificación

- Ejecutar `php -l` en cada archivo PHP modificado.
- Probar éxito, entrada inválida, recurso inexistente y autenticación en endpoints afectados, cuando el entorno lo permita.
- Documentar cualquier cambio de contrato API en `README.md`.
