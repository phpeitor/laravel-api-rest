<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Panel API Clientes</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="toast-stack" data-toast-stack aria-live="polite" aria-atomic="true"></div>
        <main class="api-shell">
            <section class="api-hero">
                <article class="hero-card">
                    <span class="eyebrow">API REST Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
                    <h4 class="hero-title">Panel web para probar clientes y endpoints.</h4>
                    <p class="hero-copy">
                        Esta portada sirve como base para validar por navegador la API del proyecto. Desde aquí puedes crear,
                        consultar, actualizar y eliminar clientes sin salir de la app.
                    </p>

                    <div class="hero-stats">
                        <div class="stat">
                            <strong>{{ $clientesRegistrados }}</strong>
                            <span>Clientes registrados</span>
                        </div>
                        <div class="stat">
                            <strong>{{ $clientesActualizados }}</strong>
                            <span>Clientes actualizados</span>
                        </div>
                        <div class="stat">
                            <strong>{{ count($endpoints) }}</strong>
                            <span>Endpoints disponibles en el panel.</span>
                        </div>
                    </div>
                </article>

                <aside class="side-stack">
                    <section class="panel">
                        <h2 class="panel-title">Endpoints disponibles</h2>
                        <p class="panel-copy">La ruta raíz muestra la referencia. La interacción real ocurre contra <strong>/api/clientes</strong>.</p>

                        <div class="endpoint-list">
                            @foreach ($endpoints as $endpoint)
                                @php
                                    $methodClass = 'method-' . strtolower($endpoint['method']);
                                @endphp
                                <article class="endpoint-card">
                                    <div class="endpoint-top">
                                        <span class="method-badge {{ $methodClass }}">{{ $endpoint['method'] }}</span>
                                        <span class="endpoint-path">{{ $endpoint['path'] }}</span>
                                    </div>
                                    <span class="endpoint-desc">{{ $endpoint['description'] }}</span>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    <section class="response-box">
                        <div class="response-meta">
                            <span class="pill" data-response-title>Respuesta API</span>
                            <span class="pill" data-response-status>HTTP --</span>
                        </div>
                        <pre data-response-output>{}</pre>
                    </section>
                </aside>
            </section>

            <section class="workspace">
                <div class="workspace-grid">
                    <section class="panel">
                        <h2 class="section-title">Crear cliente</h2>
                        <p class="section-copy">Completa el formulario y presiona crear para disparar un POST a la API.</p>

                        <form data-form-create>
                            <div class="form-grid">
                                <div class="field full">
                                    <label for="nombre">Nombre</label>
                                    <input id="nombre" name="nombre" type="text" placeholder="Paciente" required>
                                </div>
                                <div class="field">
                                    <label for="fecha_cita">Fecha cita</label>
                                    <input id="fecha_cita" name="fecha_cita" type="date" min="{{ now()->toDateString() }}" required>
                                </div>
                                <div class="field">
                                    <label for="hora_cita">Hora cita</label>
                                    <input id="hora_cita" name="hora_cita" type="time" required>
                                </div>
                                <div class="field">
                                    <label for="nombre_medico">Médico</label>
                                    <input id="nombre_medico" name="nombre_medico" type="text" placeholder="Dr. Pérez" required>
                                </div>
                                <div class="field">
                                    <label for="nombre_centro">Centro</label>
                                    <input id="nombre_centro" name="nombre_centro" type="text" placeholder="Clínica Central" required>
                                </div>
                                <div class="field full">
                                    <label for="telefono">Teléfono</label>
                                    <input id="telefono" name="telefono" type="text" placeholder="912345678" maxlength="9" inputmode="numeric" pattern="[0-9]{9}" title="El teléfono debe tener exactamente 9 dígitos" required>
                                </div>
                            </div>

                            <div class="actions">
                                <button class="button button-primary" type="submit">Crear cliente</button>
                                <button class="button button-secondary" type="button" data-refresh>Recargar listado</button>
                            </div>
                        </form>
                    </section>

                    <section class="panel">
                        <h2 class="section-title">Pruebas rápidas</h2>
                        <p class="section-copy">Usa estos formularios para consultar, actualizar o eliminar por ID.</p>

                        <div class="endpoint-list">
                            <form data-form-show>
                                <div class="form-grid">
                                    <div class="field full">
                                        <label for="show_id">Consultar por ID</label>
                                        <input id="show_id" name="id" type="number" min="1" placeholder="1" required>
                                    </div>
                                </div>
                                <div class="actions">
                                    <button class="button button-secondary" type="submit">Ver cliente</button>
                                </div>
                            </form>

                            <form data-form-update>
                                <div class="form-grid">
                                    <div class="field full">
                                        <label for="update_id">Actualizar por ID</label>
                                        <input id="update_id" name="id" type="number" min="1" placeholder="1" required>
                                    </div>
                                    <div class="field full">
                                        <label for="update_nombre">Nombre</label>
                                        <input id="update_nombre" name="nombre" type="text" placeholder="Paciente actualizado" required>
                                    </div>
                                    <div class="field">
                                        <label for="update_fecha">Fecha cita</label>
                                        <input id="update_fecha" name="fecha_cita" type="date" required>
                                    </div>
                                    <div class="field">
                                        <label for="update_hora">Hora cita</label>
                                        <input id="update_hora" name="hora_cita" type="time" required>
                                    </div>
                                    <div class="field">
                                        <label for="update_medico">Médico</label>
                                        <input id="update_medico" name="nombre_medico" type="text" placeholder="Dr. Actualizado" required>
                                    </div>
                                    <div class="field">
                                        <label for="update_centro">Centro</label>
                                        <input id="update_centro" name="nombre_centro" type="text" placeholder="Centro actualizado" required>
                                    </div>
                                    <div class="field full">
                                        <label for="update_telefono">Teléfono</label>
                                        <input id="update_telefono" name="telefono" type="text" placeholder="987654321" maxlength="9" required>
                                    </div>
                                </div>
                                <div class="actions">
                                    <button class="button button-primary" type="submit">Actualizar cliente</button>
                                </div>
                            </form>

                            <form data-form-partial>
                                <div class="form-grid">
                                    <div class="field">
                                        <label for="partial_id">ID</label>
                                        <input id="partial_id" name="id" type="number" min="1" placeholder="1" required>
                                    </div>
                                    <div class="field">
                                        <label for="partial_estado">Estado</label>
                                        <input id="partial_estado" name="estado" type="text" placeholder="PENDIENTE" maxlength="10">
                                    </div>
                                </div>
                                <div class="actions">
                                    <button class="button button-secondary" type="submit">Actualizar estado</button>
                                </div>
                            </form>

                            <form data-form-delete>
                                <div class="form-grid">
                                    <div class="field full">
                                        <label for="delete_id">Eliminar por ID</label>
                                        <input id="delete_id" name="id" type="number" min="1" placeholder="1" required>
                                    </div>
                                </div>
                                <div class="actions">
                                    <button class="button button-danger" type="submit">Eliminar cliente</button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>

                <section class="table-card">
                    <div class="response-meta">
                        <span class="pill">Listado sincronizado</span>
                        <span class="pill">GET /api/clientes</span>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Médico</th>
                                    <th>Centro</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody data-clients-body>
                                <tr>
                                    <td colspan="8">Cargando clientes...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-bar">
                        <p class="pagination-summary" data-pagination-summary>Mostrando 0 de 0 clientes</p>
                        <div class="pagination-controls" data-pagination-controls></div>
                    </div>
                </section>
            </section>
        </main>
    </body>
</html>