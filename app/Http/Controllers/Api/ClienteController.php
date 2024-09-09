<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ClienteController extends Controller
{
    public function index()
    {
        $clientes = cliente::all();

        if ($clientes->isEmpty()) {
             $data = [
                 'message' => 'No se encontraron clientes',
                 'status' => 200
             ];
             return response()->json($data, 404);
        }

        $data = [
            'clientes' => $clientes,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:255',
            'fecha_cita' => 'required|date|date_format:Y-m-d',
            'hora_cita' => 'required|date_format:H:i',
            'nombre_medico' => 'required|max:255',
            'nombre_centro' => 'required|max:255',
            'telefono' => 'required|digits:9'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'fecha_cita' => $request->fecha_cita,
            'hora_cita' => $request->hora_cita,
            'nombre_medico' => $request->nombre_medico,
            'nombre_centro' => $request->nombre_centro,
            'telefono' => $request->telefono
        ]);

        if (!$cliente) {
            $data = [
                'message' => 'Error al crear el cliente',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'cliente' => $cliente,
            'status' => 201
        ];

        return response()->json($data, 201);

    }

    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'Id' => $cliente->Id,
            'Paciente' => $cliente->nombre,
            'Medico' => $cliente->nombre_medico,
            'FechaCita' => $cliente->fecha_cita,
            'HoraCita' => $cliente->hora_cita,
            'Servicio' => $cliente->servicio,
            'Prestacion' => $cliente->presentacion,
            'Estado' => $cliente->estado
        ];
    
        return response()->json(['cliente' => $data, 'status' => 200], 200);
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        
        $cliente->delete();

        $data = [
            'message' => 'Cliente eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:255',
            'fecha_cita' => 'required|date|date_format:Y-m-d',
            'hora_cita' => 'required|date_format:H:i',
            'nombre_medico' => 'required|max:255',
            'nombre_centro' => 'required|max:255',
            'telefono' => 'required|digits:9'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $cliente->nombre = $request->nombre;
        $cliente->fecha_cita = $request->fecha_cita;
        $cliente->hora_cita = $request->hora_cita;
        $cliente->nombre_medico = $request->nombre_medico;
        $cliente->nombre_centro = $request->nombre_centro;
        $cliente->telefono = $request->telefono;

        $cliente->save();

        $data = [
            'message' => 'Cliente actualizado',
            'cliente' => $cliente,
            'status' => 200
        ];

        return response()->json($data, 200);

    }

    public function updatePartial(Request $request)
    {
        $id = $request->input('Id');

        if (!$id) {
            return response()->json(['message' => 'Id no proporcionado', 'status' => 400], 400);
        }

        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado', 'status' => 404], 404);
        }

        if (is_null($cliente->estado)) {
            $cliente->estado = 'PENDIENTE';
        }

        if ($cliente->estado !== 'PENDIENTE') {
            return response()->json(['message' => 'Cliente no se encuentra pendiente', 'status' => 400], 400);
        }

        $validator = Validator::make($request->all(), [
            'estado' => 'max:10'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación de los datos', 'errors' => $validator->errors(), 'status' => 400], 400);
        }

        $estado = $request->input('estado');
        if ($estado) {
            $cliente->estado = $estado;
        }

        if ($cliente->isDirty()) {
            $cliente->save();
        } else {
            return response()->json(['message' => 'No hay cambios para actualizar', 'status' => 200], 200);
        }

        return response()->json(['message' => 'Cliente actualizado', 'cliente' => $cliente, 'status' => 200], 200);
    }


}
