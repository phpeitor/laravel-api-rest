<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#101a2b">
        <title>API Console · Clientes</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="toast-stack" data-toast-stack aria-live="polite" aria-atomic="true"></div>
        <div class="app-layout">
            <aside class="sidebar" aria-label="Navegación principal">
                <a class="brand" href="#overview" aria-label="API Console, inicio">
                    <span class="brand-mark" aria-hidden="true">A</span>
                    <span class="brand-copy"><strong>API Console</strong><small>Developer workspace</small></span>
                </a>

                <div class="sidebar-label">WORKSPACE</div>
                <nav class="side-nav">
                    <a class="side-link active" href="#overview" data-section-link="overview" aria-current="page"><svg class="ui-icon nav-icon" viewBox="0 0 24 24"><path d="m3 10 9-7 9 7"/><path d="M5 9v11h14V9M9 20v-6h6v6"/></svg>Resumen</a>
                    <a class="side-link" href="#endpoints" data-section-link="endpoints"><svg class="ui-icon nav-icon" viewBox="0 0 24 24"><path d="M4 7h15l-3-3M20 17H5l3 3"/><path d="M4 7v4m16 6v-4"/></svg>Endpoints<span class="nav-count">{{ count($endpoints) }}</span></a>
                    <a class="side-link" href="#playground" data-section-link="playground"><svg class="ui-icon nav-icon" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="m7 9 3 3-3 3m6 0h4"/></svg>Playground</a>
                    <a class="side-link" href="#records" data-section-link="records"><svg class="ui-icon nav-icon" viewBox="0 0 24 24"><path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5"/></svg>Clientes<span class="nav-count">{{ $clientesRegistrados }}</span></a>
                </nav>

                <div class="sidebar-label resource-label">RECURSOS</div>
                <a class="resource-link" href="#records" data-section-link="records"><span class="resource-dot"></span><span>Clientes</span><code>/api/v2/clientes</code></a>

                <div class="sidebar-bottom">
                    <div class="connection-indicator"><span class="status-dot"></span><span><strong data-api-connection>Token requerido</strong><small data-api-connection-detail>Autenticación Bearer</small></span></div>
                    <div class="sidebar-version">Laravel v{{ Illuminate\Foundation\Application::VERSION }}</div>
                </div>
            </aside>

            <main class="main-content">
                <header class="topbar">
                    <div class="breadcrumbs"><span>Workspace</span><span class="crumb-divider">/</span><strong>Clientes</strong></div>
                    <div class="topbar-actions">
                        <span class="environment-chip"><span class="status-dot"></span>Development</span>
                        <button class="token-trigger" type="button" data-open-token-dialog><span class="status-dot"></span><span data-token-label>Configurar token</span></button>
                        <a class="docs-link" href="#endpoints">Documentación <span aria-hidden="true">↗</span></a>
                    </div>
                </header>

                <div class="content-wrap">
                    <section class="page-heading" id="overview" data-page-section>
                        <div>
                            <div class="eyebrow"><span class="eyebrow-dot"></span>REST API <span class="eyebrow-separator">·</span> v2</div>
                            <h1>Clientes API</h1>
                            <p>Explora los recursos y prueba solicitudes directamente contra tu API.</p>
                        </div>
                        <button class="button button-primary" type="button" data-scroll-playground><span aria-hidden="true">＋</span> Nueva solicitud</button>
                    </section>

                    <section class="overview-grid" aria-label="Resumen de la API">
                        <article class="metric-card"><div class="metric-top"><span class="metric-icon teal" aria-hidden="true"><svg class="ui-icon" viewBox="0 0 24 24"><path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5"/></svg></span><span class="metric-label">Registros</span></div><strong class="metric-value">{{ number_format($clientesRegistrados) }}</strong><span class="metric-foot">Clientes en la colección</span></article>
                        <article class="metric-card"><div class="metric-top"><span class="metric-icon blue" aria-hidden="true"><svg class="ui-icon" viewBox="0 0 24 24"><path d="M20 7v5h-5M4 17v-5h5"/><path d="M5.5 9a7 7 0 0 1 11.8-2L20 12M4 12l2.7 5a7 7 0 0 0 11.8-2"/></svg></span><span class="metric-label">Actualizados</span></div><strong class="metric-value">{{ number_format($clientesActualizados) }}</strong><span class="metric-foot">Con cambios posteriores al alta</span></article>
                        <article class="metric-card"><div class="metric-top"><span class="metric-icon violet" aria-hidden="true"><svg class="ui-icon" viewBox="0 0 24 24"><path d="M4 7h15l-3-3M20 17H5l3 3"/><path d="M4 7v4m16 6v-4"/></svg></span><span class="metric-label">Operaciones</span></div><strong class="metric-value">{{ count($endpoints) }}</strong><span class="metric-foot">Métodos disponibles</span></article>
                        <article class="metric-card metric-api"><div class="metric-top"><span class="metric-icon green" aria-hidden="true"><svg class="ui-icon" viewBox="0 0 24 24"><path d="M12 3 4 6v5c0 5 3.4 8.2 8 10 4.6-1.8 8-5 8-10V6l-8-3Z"/><path d="m9 12 2 2 4-4"/></svg></span><span class="metric-label">Estado de API</span></div><strong class="api-status"><span class="status-dot"></span>Operativa</strong><span class="metric-foot"><code>{{ url('/api/v2/clientes') }}</code></span></article>
                    </section>

                    <section class="surface endpoints-section" id="endpoints" data-page-section>
                        <div class="section-heading">
                            <div><span class="section-kicker">REFERENCIA</span><h2>Endpoints</h2><p>Operaciones disponibles para el recurso <code>clientes</code>.</p></div>
                            <span class="version-tag">Base URL <code>{{ url('/api/v2') }}</code></span>
                        </div>
                        <div class="endpoint-table-wrap">
                            <table class="endpoint-table">
                                <thead><tr><th scope="col">Método</th><th scope="col">Endpoint</th><th scope="col">Descripción</th><th scope="col">Respuesta</th><th scope="col"></th></tr></thead>
                                <tbody>
                                    @foreach ($endpoints as $endpoint)
                                        @php $methodClass = 'method-' . strtolower($endpoint['method']); @endphp
                                        <tr>
                                            <td><span class="method-badge {{ $methodClass }}">{{ $endpoint['method'] }}</span></td>
                                            <td><code class="endpoint-path">{{ $endpoint['path'] }}</code></td>
                                            <td class="endpoint-description">{{ $endpoint['description'] }}</td>
                                            <td><span class="response-code">{{ $endpoint['method'] === 'POST' ? '201' : ($endpoint['method'] === 'DELETE' ? '200' : '200') }}</span></td>
                                            <td><a class="try-link" href="{{ $endpoint['method'] === 'GET' && $endpoint['path'] === '/api/v2/clientes' ? '#records' : '#playground' }}" data-endpoint-method="{{ $endpoint['method'] }}" data-endpoint-path="{{ $endpoint['path'] }}">Probar <span aria-hidden="true">↗</span></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="endpoint-note"><span aria-hidden="true">ⓘ</span> Las respuestas de error pueden devolver <code>400</code> para validación o <code>404</code> cuando el cliente no existe.</div>
                    </section>

                    <section class="playground-grid" id="playground" data-page-section>
                        <div class="surface request-panel">
                            <div class="section-heading compact-heading">
                                <div><span class="section-kicker">REQUEST BUILDER</span><h2>Playground</h2><p>Envía solicitudes reales a <code>/api/v2/clientes</code>.</p></div>
                                <span class="live-badge"><span class="status-dot"></span>LIVE</span>
                            </div>

                            <div class="request-tabs" role="tablist" aria-label="Tipo de solicitud">
                                <button type="button" class="request-tab active" role="tab" aria-selected="true" data-request-tab="create">Crear</button>
                                <button type="button" class="request-tab" role="tab" aria-selected="false" data-request-tab="show">Consultar</button>
                                <button type="button" class="request-tab" role="tab" aria-selected="false" data-request-tab="update">Actualizar</button>
                                <button type="button" class="request-tab" role="tab" aria-selected="false" data-request-tab="partial">Estado</button>
                                <button type="button" class="request-tab" role="tab" aria-selected="false" data-request-tab="delete">Eliminar</button>
                            </div>

                            <div class="request-route"><span class="method-badge method-post" data-active-method>POST</span><code data-active-path>/api/v2/clientes</code><svg class="ui-icon route-lock" viewBox="0 0 24 24" role="img" aria-label="Ruta protegida"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg></div>

                            <form class="request-form" data-form-create data-request-panel="create">
                                <div class="form-intro"><strong>Crear cliente</strong><span>Campos requeridos <b>*</b></span></div>
                                <div class="form-grid">
                                    <div class="field full"><label for="nombre">Nombre completo <b>*</b></label><input id="nombre" name="nombre" type="text" placeholder="Ej. Ana García" maxlength="255" autocomplete="name" required></div>
                                    <div class="field"><label for="fecha_cita">Fecha de cita <b>*</b></label><input id="fecha_cita" name="fecha_cita" type="date" min="{{ now()->toDateString() }}" required></div>
                                    <div class="field"><label for="hora_cita">Hora de cita <b>*</b></label><input id="hora_cita" name="hora_cita" type="time" required></div>
                                    <div class="field"><label for="nombre_medico">Médico <b>*</b></label><input id="nombre_medico" name="nombre_medico" type="text" placeholder="Ej. Dra. Pérez" maxlength="255" required></div>
                                    <div class="field"><label for="nombre_centro">Centro médico <b>*</b></label><input id="nombre_centro" name="nombre_centro" type="text" placeholder="Ej. Clínica Central" maxlength="255" required></div>
                                    <div class="field full"><label for="telefono">Teléfono <b>*</b></label><input id="telefono" name="telefono" type="tel" placeholder="912 345 678" maxlength="9" inputmode="numeric" pattern="[0-9]{9}" title="Ingresa exactamente 9 dígitos" autocomplete="tel" required><small class="field-hint">9 dígitos, sin espacios ni prefijo.</small></div>
                                </div>
                                <div class="form-actions"><span class="request-hint">Content-Type: <code>application/json</code></span><button class="button button-primary" type="submit">Enviar solicitud <span aria-hidden="true">→</span></button></div>
                            </form>

                            <form class="request-form is-hidden" data-form-show data-request-panel="show">
                                <div class="form-intro"><strong>Obtener un cliente</strong><span>GET · Lectura de recurso</span></div>
                                <div class="form-grid"><div class="field full"><label for="show_id">ID del cliente <b>*</b></label><input id="show_id" name="id" type="number" min="1" step="1" placeholder="Ej. 42" required><small class="field-hint">La ruta será <code>/api/v2/clientes/{id}</code>.</small></div></div>
                                <div class="form-actions"><span class="request-hint">Sin cuerpo de solicitud</span><button class="button button-primary" type="submit">Enviar solicitud <span aria-hidden="true">→</span></button></div>
                            </form>

                            <form class="request-form is-hidden" data-form-update data-request-panel="update">
                                <div class="form-intro"><strong>Reemplazar un cliente</strong><span>PUT · Actualización completa</span></div>
                                <div class="form-grid">
                                    <div class="field full"><label for="update_id">ID del cliente <b>*</b></label><input id="update_id" name="id" type="number" min="1" step="1" placeholder="Ej. 42" required></div>
                                    <div class="field full"><label for="update_nombre">Nombre completo <b>*</b></label><input id="update_nombre" name="nombre" type="text" maxlength="255" placeholder="Ej. Ana García" required></div>
                                    <div class="field"><label for="update_fecha">Fecha de cita <b>*</b></label><input id="update_fecha" name="fecha_cita" type="date" required></div>
                                    <div class="field"><label for="update_hora">Hora de cita <b>*</b></label><input id="update_hora" name="hora_cita" type="time" required></div>
                                    <div class="field"><label for="update_medico">Médico <b>*</b></label><input id="update_medico" name="nombre_medico" type="text" maxlength="255" required></div>
                                    <div class="field"><label for="update_centro">Centro médico <b>*</b></label><input id="update_centro" name="nombre_centro" type="text" maxlength="255" required></div>
                                    <div class="field full"><label for="update_telefono">Teléfono <b>*</b></label><input id="update_telefono" name="telefono" type="tel" maxlength="9" inputmode="numeric" pattern="[0-9]{9}" title="Ingresa exactamente 9 dígitos" required></div>
                                </div>
                                <div class="form-actions"><span class="request-hint">Content-Type: <code>application/json</code></span><button class="button button-primary" type="submit">Enviar solicitud <span aria-hidden="true">→</span></button></div>
                            </form>

                            <form class="request-form is-hidden" data-form-partial data-request-panel="partial">
                                <div class="form-intro"><strong>Cambiar estado</strong><span>PATCH · Actualización parcial</span></div>
                                <div class="form-grid"><div class="field"><label for="partial_id">ID del cliente <b>*</b></label><input id="partial_id" name="id" type="number" min="1" step="1" placeholder="Ej. 42" required></div><div class="field"><label for="partial_estado">Nuevo estado</label><input id="partial_estado" name="estado" type="text" maxlength="10" placeholder="CONFIRMADO"><small class="field-hint">Máximo 10 caracteres.</small></div></div>
                                <div class="form-actions"><span class="request-hint">Content-Type: <code>application/json</code></span><button class="button button-primary" type="submit">Enviar solicitud <span aria-hidden="true">→</span></button></div>
                            </form>

                            <form class="request-form is-hidden" data-form-delete data-request-panel="delete">
                                <div class="form-intro"><strong>Eliminar un cliente</strong><span class="danger-text">Esta acción no se puede deshacer.</span></div>
                                <div class="form-grid"><div class="field full"><label for="delete_id">ID del cliente <b>*</b></label><input id="delete_id" name="id" type="number" min="1" step="1" placeholder="Ej. 42" required></div></div>
                                <div class="form-actions"><span class="request-hint">DELETE · Sin cuerpo</span><button class="button button-danger" type="submit">Eliminar cliente <span aria-hidden="true">→</span></button></div>
                            </form>
                        </div>

                        <section class="surface response-panel" aria-label="Respuesta de la API">
                            <div class="response-heading"><div><span class="section-kicker">RESPONSE</span><h2>Respuesta</h2></div><button class="copy-button" type="button" data-copy-response disabled>Copiar JSON</button></div>
                            <div class="response-summary"><span class="response-state" data-response-title><span class="response-state-dot"></span>Esperando solicitud</span><span class="http-status" data-response-status>HTTP —</span></div>
                            <div class="response-details"><span data-response-time>— ms</span><span>application/json</span></div>
                            <pre class="response-output" data-response-output aria-live="polite"><code>{
  "message": "Selecciona una operación y envía una solicitud."
}</code></pre>
                            <div class="response-footer"><span><span class="status-dot"></span>Solicitud same-origin</span><span data-response-request>Sin solicitudes</span></div>
                        </section>
                    </section>

                    <section class="surface records-section" id="records" data-page-section>
                        <div class="section-heading records-heading">
                            <div><span class="section-kicker">DATA EXPLORER</span><h2>Clientes</h2><p>Vista paginada de los registros devueltos por la API.</p></div>
                            <button class="button button-quiet" type="button" data-refresh><span aria-hidden="true">↻</span> Actualizar</button>
                        </div>
                        <div class="records-meta"><span class="collection-tag"><span class="resource-dot"></span>clientes</span><span class="records-endpoint"><span class="method-badge method-get">GET</span><code>/api/v2/clientes</code></span><label class="page-size">Por página<select data-page-size aria-label="Clientes por página"><option value="5" selected>5</option><option value="10">10</option><option value="25">25</option></select></label></div>
                        <div class="table-wrap">
                            <table class="records-table">
                                <thead><tr><th scope="col">ID</th><th scope="col">Cliente</th><th scope="col">Cita</th><th scope="col">Médico</th><th scope="col">Centro</th><th scope="col">Teléfono</th><th scope="col">Estado</th><th scope="col"><span class="sr-only">Acciones</span></th></tr></thead>
                                <tbody data-clients-body><tr><td colspan="8" class="table-message"><span class="loading-spinner"></span> Cargando registros…</td></tr></tbody>
                            </table>
                        </div>
                        <div class="pagination-bar"><p class="pagination-summary" data-pagination-summary>Preparando listado…</p><div class="pagination-controls" data-pagination-controls></div></div>
                    </section>

                    <footer class="page-footer"><span>API Console <span aria-hidden="true">·</span> interfaz de desarrollo</span><span>Las operaciones se ejecutan contra <code>{{ url('/api/v2') }}</code></span></footer>
                </div>
            </main>
        </div>
        <dialog class="token-dialog" data-token-dialog aria-labelledby="token-dialog-title" aria-describedby="token-dialog-description">
            <form class="token-form" data-token-form>
                <div class="token-dialog-header">
                    <div class="token-dialog-mark" aria-hidden="true"><svg class="ui-icon" viewBox="0 0 24 24"><path d="M12 3 4 6v5c0 5 3.4 8.2 8 10 4.6-1.8 8-5 8-10V6l-8-3Z"/><path d="m9 12 2 2 4-4"/></svg></div>
                    <span class="token-version">API v2 <span class="status-dot"></span> Protegida</span>
                    <button class="token-close" type="button" data-close-token aria-label="Cerrar diálogo"><svg class="ui-icon" viewBox="0 0 24 24"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
                </div>
                <span class="section-kicker">AUTENTICACIÓN DE API</span>
                <h2 id="token-dialog-title">Autoriza tus solicitudes</h2>
                <p id="token-dialog-description">Conecta tu espacio de trabajo con un token Bearer. Se adjuntará a las solicitudes de esta pestaña y se eliminará al cerrar la sesión.</p>
                <div class="auth-scheme"><span class="auth-scheme-dot"></span><code>Authorization: Bearer &lt;token&gt;</code><span>HTTPS</span></div>
                <label for="api-token">Token de acceso</label>
                <div class="token-input-wrap"><svg class="ui-icon token-input-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg><input id="api-token" name="token" type="password" autocomplete="off" spellcheck="false" placeholder="Pega el valor de API_TOKEN" required><button class="token-visibility" type="button" data-toggle-token aria-label="Mostrar token"><svg class="ui-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg><span>Mostrar</span></button></div>
                <small class="token-help">El token se configura en el servidor mediante <code>API_TOKEN</code> en <code>.env</code>.</small>
                <div class="token-notice"><svg class="ui-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5m0-8h.01"/></svg><span>La conexión se valida antes de habilitar las operaciones.</span></div>
                <p class="token-error" data-token-error role="alert" hidden></p>
                <div class="token-dialog-actions"><button class="button button-quiet" type="button" data-clear-token>Desconectar</button><button class="button button-primary" type="submit" data-token-submit>Validar y conectar <svg class="ui-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></button></div>
            </form>
        </dialog>
    </body>
</html>
