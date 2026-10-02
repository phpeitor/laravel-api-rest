<?php

use App\Models\Cliente;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $clientesRegistrados = Cliente::count();
    $clientesActualizados = Cliente::whereColumn('updated_at', '!=', 'created_at')->count();

    $endpoints = [
        ['method' => 'GET', 'path' => '/api/v2/clientes', 'description' => 'Lista todos los clientes'],
        ['method' => 'GET', 'path' => '/api/v2/clientes/{id}', 'description' => 'Consulta un cliente por ID'],
        ['method' => 'POST', 'path' => '/api/v2/clientes', 'description' => 'Crea un cliente nuevo'],
        ['method' => 'PUT', 'path' => '/api/v2/clientes/{id}', 'description' => 'Actualiza un cliente completo'],
        ['method' => 'PATCH', 'path' => '/api/v2/clientes', 'description' => 'Actualiza el estado de un cliente'],
        ['method' => 'DELETE', 'path' => '/api/v2/clientes/{id}', 'description' => 'Elimina un cliente'],
    ];

    return view('dashboard', compact('endpoints', 'clientesRegistrados', 'clientesActualizados'));
});
