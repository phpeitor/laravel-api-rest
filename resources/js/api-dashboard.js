const responseOutput = document.querySelector('[data-response-output]');
const responseStatus = document.querySelector('[data-response-status]');
const responseTitle = document.querySelector('[data-response-title]');
const clientsTableBody = document.querySelector('[data-clients-body]');
const toastStack = document.querySelector('[data-toast-stack]');

const endpoints = {
    list: '/api/clientes',
    show: (id) => `/api/clientes/${id}`,
    create: '/api/clientes',
    update: (id) => `/api/clientes/${id}`,
    partial: '/api/clientes',
    delete: (id) => `/api/clientes/${id}`,
};

const forms = {
    create: document.querySelector('[data-form-create]'),
    show: document.querySelector('[data-form-show]'),
    update: document.querySelector('[data-form-update]'),
    partial: document.querySelector('[data-form-partial]'),
    delete: document.querySelector('[data-form-delete]'),
    refresh: document.querySelector('[data-refresh]'),
};

const todayIsoDate = () => {
    const now = new Date();
    const timezoneOffset = now.getTimezoneOffset() * 60000;
    return new Date(now.getTime() - timezoneOffset).toISOString().slice(0, 10);
};

const showToast = (type, message) => {
    if (!toastStack) {
        return;
    }

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    toastStack.prepend(toast);

    window.setTimeout(() => {
        toast.remove();
    }, 5000);
};

const normalizeErrors = (payload) => {
    if (!payload || typeof payload !== 'object' || !payload.errors) {
        return [];
    }

    return Object.values(payload.errors)
        .flat()
        .filter(Boolean)
        .map((value) => String(value));
};

const notifyResponse = (response, payload, successMessage) => {
    if (response.ok) {
        if (successMessage) {
            showToast('success', successMessage);
        }
        return;
    }

    const errorMessages = normalizeErrors(payload);

    if (errorMessages.length > 0) {
        errorMessages.forEach((message) => showToast('error', message));
        return;
    }

    const fallbackMessage = payload?.message ?? 'Ocurrio un error al procesar la solicitud.';
    showToast('error', fallbackMessage);
};

const validateCreateForm = (data) => {
    const errors = [];
    const today = todayIsoDate();

    if (!/^\d{9}$/.test(data.telefono ?? '')) {
        errors.push('El telefono debe tener exactamente 9 digitos numericos.');
    }

    if ((data.fecha_cita ?? '') < today) {
        errors.push('La fecha de cita no puede ser menor a la fecha actual.');
    }

    return errors;
};

const fillStatus = (label, status) => {
    if (responseTitle) {
        responseTitle.textContent = label;
    }

    if (responseStatus) {
        responseStatus.textContent = `HTTP ${status}`;
    }
};

const showResponse = (label, status, payload) => {
    fillStatus(label, status);

    if (responseOutput) {
        responseOutput.textContent = JSON.stringify(payload, null, 2);
    }
};

const getFormData = (form) => Object.fromEntries(new FormData(form).entries());

const renderClients = (clientes) => {
    if (!clientsTableBody) {
        return;
    }

    if (!Array.isArray(clientes) || clientes.length === 0) {
        clientsTableBody.innerHTML = `
            <tr>
                <td colspan="8">Todavía no hay clientes registrados. Usa el formulario superior para crear el primero.</td>
            </tr>
        `;
        return;
    }

    clientsTableBody.innerHTML = clientes.map((cliente) => `
        <tr>
            <td>${cliente.id}</td>
            <td>${cliente.nombre ?? ''}</td>
            <td>${cliente.fecha_cita ?? ''}</td>
            <td>${cliente.hora_cita ?? ''}</td>
            <td>${cliente.nombre_medico ?? ''}</td>
            <td>${cliente.nombre_centro ?? ''}</td>
            <td>${cliente.telefono ?? ''}</td>
            <td>${cliente.estado ?? ''}</td>
        </tr>
    `).join('');
};

const fetchJson = async (url, options = {}) => {
    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...(options.headers ?? {}),
        },
        ...options,
    });

    let payload = null;

    try {
        payload = await response.json();
    } catch {
        payload = { message: 'La respuesta no devolvió JSON' };
    }

    return { response, payload };
};

const loadClients = async () => {
    const { response, payload } = await fetchJson(endpoints.list, { method: 'GET' });

    if (response.ok) {
        renderClients(payload.clientes ?? []);
    }

    showResponse('Listado de clientes', response.status, payload);
};

const bindForm = (form, action) => {
    if (!form) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const result = await action(getFormData(form));

        if (result?.refresh !== false) {
            await loadClients();
        }
    });
};

bindForm(forms.create, async (data) => {
    const localErrors = validateCreateForm(data);

    if (localErrors.length > 0) {
        localErrors.forEach((message) => showToast('error', message));
        return { refresh: false };
    }

    const { response, payload } = await fetchJson(endpoints.create, {
        method: 'POST',
        body: JSON.stringify(data),
    });

    showResponse('Crear cliente', response.status, payload);
    notifyResponse(response, payload, 'Cliente creado correctamente.');
});

bindForm(forms.show, async (data) => {
    const { response, payload } = await fetchJson(endpoints.show(data.id), { method: 'GET' });
    showResponse('Consultar cliente', response.status, payload);
    notifyResponse(response, payload, 'Consulta realizada correctamente.');

    return { refresh: false };
});

bindForm(forms.update, async (data) => {
    const id = data.id;
    const payloadData = { ...data };
    delete payloadData.id;

    const { response, payload } = await fetchJson(endpoints.update(id), {
        method: 'PUT',
        body: JSON.stringify(payloadData),
    });

    showResponse('Actualizar cliente', response.status, payload);
    notifyResponse(response, payload, 'Cliente actualizado correctamente.');
});

bindForm(forms.partial, async (data) => {
    const { response, payload } = await fetchJson(endpoints.partial, {
        method: 'PATCH',
        body: JSON.stringify(data),
    });

    showResponse('Actualizar estado', response.status, payload);
    notifyResponse(response, payload, 'Estado actualizado correctamente.');
});

bindForm(forms.delete, async (data) => {
    const { response, payload } = await fetchJson(endpoints.delete(data.id), {
        method: 'DELETE',
    });

    showResponse('Eliminar cliente', response.status, payload);
    notifyResponse(response, payload, 'Cliente eliminado correctamente.');
});

if (forms.refresh) {
    forms.refresh.addEventListener('click', loadClients);
}

loadClients().catch((error) => {
    showResponse('Error al cargar clientes', 500, {
        message: error.message,
    });
    showToast('error', 'No se pudo cargar el listado de clientes.');
});