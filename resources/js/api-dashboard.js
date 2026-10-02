const responseOutput = document.querySelector('[data-response-output]');
const responseStatus = document.querySelector('[data-response-status]');
const responseTitle = document.querySelector('[data-response-title]');
const responseTime = document.querySelector('[data-response-time]');
const responseRequest = document.querySelector('[data-response-request]');
const copyResponseButton = document.querySelector('[data-copy-response]');
const clientsTableBody = document.querySelector('[data-clients-body]');
const toastStack = document.querySelector('[data-toast-stack]');
const paginationControls = document.querySelector('[data-pagination-controls]');
const paginationSummary = document.querySelector('[data-pagination-summary]');
const pageSizeSelect = document.querySelector('[data-page-size]');

const endpoints = {
    list: '/api/clientes',
    show: (id) => `/api/clientes/${encodeURIComponent(id)}`,
    create: '/api/clientes',
    update: (id) => `/api/clientes/${encodeURIComponent(id)}`,
    partial: '/api/clientes',
    delete: (id) => `/api/clientes/${encodeURIComponent(id)}`,
};

const forms = {
    create: document.querySelector('[data-form-create]'),
    show: document.querySelector('[data-form-show]'),
    update: document.querySelector('[data-form-update]'),
    partial: document.querySelector('[data-form-partial]'),
    delete: document.querySelector('[data-form-delete]'),
    refresh: document.querySelector('[data-refresh]'),
};

const requestInfo = {
    create: { method: 'POST', path: '/api/clientes' },
    show: { method: 'GET', path: (data) => endpoints.show(data.id) },
    update: { method: 'PUT', path: (data) => endpoints.update(data.id) },
    partial: { method: 'PATCH', path: endpoints.partial },
    delete: { method: 'DELETE', path: (data) => endpoints.delete(data.id) },
};

const paginationState = {
    currentPage: 1,
    lastPage: 1,
    perPage: Number(pageSizeSelect?.value ?? 5),
    total: 0,
    from: 0,
    to: 0,
};

const showToast = (type, message) => {
    if (!toastStack) return;

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.setAttribute('role', type === 'error' ? 'alert' : 'status');
    toast.textContent = message;
    toastStack.prepend(toast);

    window.setTimeout(() => toast.remove(), 5000);
};

const normalizeErrors = (payload) => {
    if (!payload || typeof payload !== 'object' || !payload.errors) return [];

    return Object.values(payload.errors)
        .flat()
        .filter(Boolean)
        .map((value) => String(value));
};

const notifyResponse = (response, payload, successMessage) => {
    if (response?.ok) {
        if (successMessage) showToast('success', successMessage);
        return;
    }

    const errorMessages = normalizeErrors(payload);
    if (errorMessages.length) {
        errorMessages.forEach((message) => showToast('error', message));
        return;
    }

    showToast('error', payload?.message ?? 'No se pudo completar la solicitud.');
};

const fillResponseMeta = (label, method, path, status, elapsed) => {
    if (responseTitle) {
        responseTitle.classList.remove('success', 'error');
        responseTitle.classList.add(status >= 200 && status < 300 ? 'success' : 'error');
        responseTitle.lastChild.textContent = ` ${label}`;
    }

    if (responseStatus) {
        responseStatus.textContent = status ? `HTTP ${status}` : 'NETWORK';
        responseStatus.classList.remove('success', 'error');
        responseStatus.classList.add(status >= 200 && status < 300 ? 'success' : 'error');
    }

    if (responseTime) responseTime.textContent = `${elapsed} ms`;
    if (responseRequest) responseRequest.textContent = `${method} ${path}`;
};

const showResponse = (label, method, path, status, payload, elapsed = 0) => {
    fillResponseMeta(label, method, path, status, elapsed);

    if (responseOutput) {
        responseOutput.textContent = JSON.stringify(payload ?? {}, null, 2);
        if (copyResponseButton) copyResponseButton.disabled = false;
    }
};

const getFormData = (form) => Object.fromEntries(new FormData(form).entries());

const fetchJson = async (url, options = {}) => {
    const startedAt = performance.now();

    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                Accept: 'application/json',
                ...(options.body ? { 'Content-Type': 'application/json' } : {}),
                ...(options.headers ?? {}),
            },
        });
        const responseText = await response.text();
        let payload;

        try {
            payload = responseText ? JSON.parse(responseText) : {};
        } catch {
            payload = { message: 'El servidor devolvió una respuesta que no es JSON.', raw: responseText };
        }

        return { response, payload, elapsed: Math.round(performance.now() - startedAt) };
    } catch (error) {
        return {
            response: null,
            payload: { message: error.message || 'No se pudo conectar con la API.' },
            elapsed: Math.round(performance.now() - startedAt),
        };
    }
};

const appendCell = (row, value, className = '') => {
    const cell = document.createElement('td');
    if (className) cell.className = className;
    cell.textContent = value ?? '—';
    row.append(cell);
    return cell;
};

const renderClients = (clientes) => {
    if (!clientsTableBody) return;
    clientsTableBody.replaceChildren();

    if (!Array.isArray(clientes) || clientes.length === 0) {
        const row = document.createElement('tr');
        const cell = appendCell(row, 'No hay clientes para mostrar. Crea un registro desde el playground.', 'table-message');
        cell.colSpan = 8;
        clientsTableBody.append(row);
        return;
    }

    clientes.forEach((cliente) => {
        const row = document.createElement('tr');
        appendCell(row, cliente.id);

        const nameCell = document.createElement('td');
        const name = document.createElement('span');
        name.className = 'client-name';
        name.textContent = cliente.nombre || '—';
        nameCell.append(name);
        row.append(nameCell);

        const appointment = [cliente.fecha_cita, cliente.hora_cita].filter(Boolean).join(' · ') || '—';
        appendCell(row, appointment);
        appendCell(row, cliente.nombre_medico);
        appendCell(row, cliente.nombre_centro);
        appendCell(row, cliente.telefono);

        const statusCell = document.createElement('td');
        const status = document.createElement('span');
        const statusText = String(cliente.estado || 'PENDIENTE');
        status.className = `client-status ${statusText.toLocaleLowerCase('es').normalize('NFD').replace(/[\u0300-\u036f]/g, '')}`;
        status.textContent = statusText;
        statusCell.append(status);
        row.append(statusCell);

        const actionsCell = document.createElement('td');
        const viewButton = document.createElement('button');
        viewButton.type = 'button';
        viewButton.className = 'pagination-button';
        viewButton.textContent = 'Ver';
        viewButton.setAttribute('aria-label', `Consultar cliente ${cliente.id}`);
        viewButton.addEventListener('click', () => {
            selectRequestTab('show');
            const idInput = forms.show?.querySelector('[name="id"]');
            if (idInput) {
                idInput.value = cliente.id;
                forms.show.requestSubmit();
            }
            document.querySelector('#playground')?.scrollIntoView({ behavior: 'smooth' });
        });
        actionsCell.append(viewButton);
        row.append(actionsCell);
        clientsTableBody.append(row);
    });
};

const renderPagination = () => {
    if (!paginationControls) return;
    paginationControls.replaceChildren();

    const { currentPage, lastPage, total, from, to } = paginationState;
    if (paginationSummary) {
        paginationSummary.textContent = total
            ? `Mostrando ${from ?? 0}–${to ?? 0} de ${total} clientes`
            : 'No hay clientes registrados.';
    }

    if (!total || lastPage <= 1) return;

    const addPageButton = (label, page, { active = false, disabled = false, ariaLabel = label } = {}) => {
        const button = document.createElement('button');
        button.className = `pagination-button${active ? ' active' : ''}`;
        button.type = 'button';
        button.textContent = label;
        button.disabled = disabled;
        button.setAttribute('aria-label', ariaLabel);
        if (active) button.setAttribute('aria-current', 'page');
        button.addEventListener('click', () => loadClients(page));
        paginationControls.append(button);
    };

    addPageButton('‹', currentPage - 1, { disabled: currentPage <= 1, ariaLabel: 'Página anterior' });
    const start = Math.max(1, Math.min(currentPage - 2, lastPage - 4));
    const end = Math.min(lastPage, start + 4);
    for (let page = start; page <= end; page += 1) {
        addPageButton(String(page), page, { active: page === currentPage, ariaLabel: `Página ${page}` });
    }
    addPageButton('›', currentPage + 1, { disabled: currentPage >= lastPage, ariaLabel: 'Página siguiente' });
};

const setLoading = (isLoading) => {
    if (!clientsTableBody) return;
    if (!isLoading) return;

    const row = document.createElement('tr');
    const cell = document.createElement('td');
    cell.colSpan = 8;
    cell.className = 'table-message';
    const spinner = document.createElement('span');
    spinner.className = 'loading-spinner';
    spinner.setAttribute('aria-hidden', 'true');
    cell.append(spinner, document.createTextNode(' Cargando registros…'));
    row.append(cell);
    clientsTableBody.replaceChildren(row);
};

const loadClients = async (page = paginationState.currentPage) => {
    const params = new URLSearchParams({ page: String(page), per_page: String(paginationState.perPage) });
    const path = `${endpoints.list}?${params.toString()}`;
    setLoading(true);

    const { response, payload, elapsed } = await fetchJson(path, { method: 'GET' });
    showResponse('Listado de clientes', 'GET', path, response?.status ?? 0, payload, elapsed);

    if (!response?.ok) {
        renderClients([]);
        if (clientsTableBody?.firstElementChild) {
            clientsTableBody.firstElementChild.firstElementChild.textContent = payload?.message ?? 'No se pudo cargar el listado.';
        }
        paginationState.total = 0;
        renderPagination();
        return;
    }

    const pagination = payload.pagination ?? {};
    paginationState.currentPage = Number(pagination.current_page ?? page);
    paginationState.lastPage = Number(pagination.last_page ?? 1);
    paginationState.perPage = Number(pagination.per_page ?? paginationState.perPage);
    paginationState.total = Number(pagination.total ?? 0);
    paginationState.from = Number(pagination.from ?? 0);
    paginationState.to = Number(pagination.to ?? 0);

    if (paginationState.currentPage > paginationState.lastPage && paginationState.lastPage > 0) {
        await loadClients(paginationState.lastPage);
        return;
    }

    renderClients(payload.clientes ?? []);
    renderPagination();
};

const requestTabData = {
    create: { method: 'POST', path: '/api/clientes' },
    show: { method: 'GET', path: '/api/clientes/{id}' },
    update: { method: 'PUT', path: '/api/clientes/{id}' },
    partial: { method: 'PATCH', path: '/api/clientes' },
    delete: { method: 'DELETE', path: '/api/clientes/{id}' },
};

const selectRequestTab = (tabName) => {
    document.querySelectorAll('[data-request-tab]').forEach((tab) => {
        const selected = tab.dataset.requestTab === tabName;
        tab.classList.toggle('active', selected);
        tab.setAttribute('aria-selected', String(selected));
    });
    document.querySelectorAll('[data-request-panel]').forEach((panel) => {
        panel.classList.toggle('is-hidden', panel.dataset.requestPanel !== tabName);
    });

    const info = requestTabData[tabName];
    const methodBadge = document.querySelector('[data-active-method]');
    const path = document.querySelector('[data-active-path]');
    if (methodBadge && info) {
        methodBadge.textContent = info.method;
        methodBadge.className = `method-badge method-${info.method.toLowerCase()}`;
    }
    if (path && info) path.textContent = info.path;
};

document.querySelectorAll('[data-request-tab]').forEach((tab) => {
    tab.addEventListener('click', () => selectRequestTab(tab.dataset.requestTab));
});

document.querySelectorAll('[data-endpoint-method]').forEach((link) => {
    link.addEventListener('click', () => {
        if (link.dataset.endpointMethod === 'GET' && link.dataset.endpointPath === endpoints.list) {
            loadClients(1);
            return;
        }

        const methodToTab = { GET: 'show', POST: 'create', PUT: 'update', PATCH: 'partial', DELETE: 'delete' };
        selectRequestTab(methodToTab[link.dataset.endpointMethod] ?? 'create');
    });
});

document.querySelector('[data-scroll-playground]')?.addEventListener('click', () => {
    selectRequestTab('create');
    document.querySelector('#playground')?.scrollIntoView({ behavior: 'smooth' });
    forms.create?.querySelector('input')?.focus({ preventScroll: true });
});

const bindForm = (form, type, successMessage) => {
    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const data = getFormData(form);
        const { method, path } = requestInfo[type];
        const requestPath = typeof path === 'function' ? path(data) : path;
        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.dataset.originalText = submitButton.textContent;
            submitButton.textContent = 'Enviando…';
        }

        let requestOptions = { method };
        if (type === 'create') requestOptions.body = JSON.stringify(data);
        if (type === 'update') {
            const { id, ...clientData } = data;
            requestOptions.body = JSON.stringify(clientData);
        }
        if (type === 'partial') requestOptions.body = JSON.stringify(data);

        try {
            if (type === 'delete' && !window.confirm(`¿Eliminar el cliente ${data.id}? Esta acción no se puede deshacer.`)) {
                return;
            }

            const { response, payload, elapsed } = await fetchJson(requestPath, requestOptions);
            showResponse(`${method} ${type === 'create' ? '· Crear cliente' : ''}`.trim(), method, requestPath, response?.status ?? 0, payload, elapsed);
            notifyResponse(response, payload, successMessage);

            if (response?.ok && ['create', 'update', 'partial', 'delete'].includes(type)) {
                form.reset();
                if (type === 'create') {
                    const dateInput = form.querySelector('[name="fecha_cita"]');
                    if (dateInput) dateInput.min = new Date().toLocaleDateString('en-CA');
                }
                await loadClients(paginationState.currentPage);
            }
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = submitButton.dataset.originalText || 'Enviar solicitud';
            }
        }
    });
};

bindForm(forms.create, 'create', 'Cliente creado correctamente.');
bindForm(forms.show, 'show', 'Consulta realizada correctamente.');
bindForm(forms.update, 'update', 'Cliente actualizado correctamente.');
bindForm(forms.partial, 'partial', 'Estado actualizado correctamente.');
bindForm(forms.delete, 'delete', 'Cliente eliminado correctamente.');

forms.refresh?.addEventListener('click', () => loadClients(paginationState.currentPage));
pageSizeSelect?.addEventListener('change', () => {
    paginationState.perPage = Number(pageSizeSelect.value);
    loadClients(1);
});

copyResponseButton?.addEventListener('click', async () => {
    if (!responseOutput) return;
    try {
        await navigator.clipboard.writeText(responseOutput.textContent);
        showToast('success', 'Respuesta JSON copiada.');
    } catch {
        showToast('error', 'No fue posible copiar. Selecciona el JSON y cópialo manualmente.');
    }
});

loadClients().catch((error) => {
    showResponse('Error al cargar clientes', 'GET', endpoints.list, 0, { message: error.message }, 0);
    showToast('error', 'No se pudo cargar el listado de clientes.');
});
