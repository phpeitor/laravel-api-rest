# Estándares Frontend — Interfaz de prueba de la API

## Alcance

La interfaz existente (`index.html`, `assets/css/` y `assets/js/`) permite probar los endpoints REST. No es el backend ni sustituye la validación del servidor.

## Convenciones

- Mantener HTML, CSS y JavaScript separados; evitar estilos o scripts inline nuevos.
- Usar JavaScript nativo salvo necesidad concreta de una dependencia.
- Ajustar las solicitudes a los métodos, rutas, campos y autenticación realmente soportados por `api/` y documentados en `README.md`.
- Enlazar la documentación legible mediante `docs.php`; el README fuente puede descargarse, pero no debe presentarse como texto plano dentro de la interfaz.
- Indicar estados de carga, éxito y error; procesar correctamente códigos HTTP y respuestas JSON no válidas.
- No incluir secretos permanentes en el código frontend. Un token introducido para pruebas no debe registrarse ni enviarse a destinos distintos de la API configurada.
- Escapar o insertar texto de respuesta como texto, no como HTML sin sanitizar.

## Accesibilidad y responsive

- Usar elementos semánticos y asociar controles con sus `label`.
- Permitir el uso con teclado y conservar un foco visible y contraste suficiente.
- Mantener la interfaz utilizable en escritorio y móvil, sin desbordamiento horizontal.

## Verificación

- Probar cada interacción con respuesta exitosa y errores HTTP relevantes.
- Revisar la consola del navegador y confirmar que la interfaz refleja los contratos actuales de la API.
