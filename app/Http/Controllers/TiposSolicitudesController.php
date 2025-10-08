<?php

namespace App\Http\Controllers;

use App\Models\TipoSolicitud;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TiposSolicitudesController extends Controller
{
    use ApiResponse;

    private function validaciones(Request $request, bool $isUpdate = false, ?int $id = null)
    {
        $rules = [
            'tipoSolicitud' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:255',
                Rule::unique('tiposSolicitudes', 'tipoSolicitud')->ignore($id, 'idTiposSolicitudes'),
            ],
            'nombre' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:255',
            ],
        ];

        return Validator::make($request->all(), $rules);
    }

    public function index()
    {
        $tiposSolicitudes = TipoSolicitud::all();
        if ($tiposSolicitudes->isEmpty()) {
            return $this->notFoundResponse('No se encontraron tipos de solicitudes');
        }
        return $this->successResponse('Tipos de solicitudes encontrados', $tiposSolicitudes);
    }

    public function store(Request $request)
    {
        $validator = $this->validaciones($request);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $tipoSolicitud = TipoSolicitud::create($validator->validated());
        return $this->createdResponse('Tipo de solicitud creado con éxito', $tipoSolicitud);
    }

    public function show($id)
    {
        $tipoSolicitud = TipoSolicitud::find($id);
        if (!$tipoSolicitud) {
            return $this->notFoundResponse('Tipo de solicitud no encontrado');
        }
        return $this->successResponse('Tipo de solicitud encontrado', $tipoSolicitud);
    }

    public function update(Request $request, $id)
    {
        $tipoSolicitud = TipoSolicitud::find($id);
        if (!$tipoSolicitud) {
            return $this->notFoundResponse('Tipo de solicitud no encontrado');
        }

        $validator = $this->validaciones($request, true, $tipoSolicitud->idTiposSolicitudes);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $tipoSolicitud->update($validator->validated());
        return $this->successResponse('Tipo de solicitud actualizado correctamente', $tipoSolicitud);
    }

    public function destroy($id)
    {
        $tipoSolicitud = TipoSolicitud::find($id);
        if (!$tipoSolicitud) {
            return $this->notFoundResponse('Tipo de solicitud no encontrado');
        }

        $tipoSolicitud->delete();
        return $this->successResponse('Tipo de solicitud eliminado correctamente');
    }
}