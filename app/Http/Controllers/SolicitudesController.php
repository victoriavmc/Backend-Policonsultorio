<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SolicitudesController extends Controller
{
    use ApiResponse;

    private function validaciones(Request $request, bool $isUpdate = false, ?int $id = null)
    {
        $rules = [
            'descripcion' => [$isUpdate ? 'sometimes' : 'required', 'string'],
            'fecha' => [$isUpdate ? 'sometimes' : 'required', 'date'],
            'tiposSolicitudes_idTiposSolicitudes' => [
                $isUpdate ? 'sometimes' : 'required',
                'exists:tiposSolicitudes,idTiposSolicitudes'
            ],
        ];

        return Validator::make($request->all(), $rules);
    }

    public function index()
    {
        $solicitudes = Solicitud::with('tiposSolicitudes')->get();
        if ($solicitudes->isEmpty()) {
            return $this->notFoundResponse('No se encontraron solicitudes');
        }
        return $this->successResponse('Solicitudes encontradas', $solicitudes);
    }

    public function store(Request $request)
    {
        $validator = $this->validaciones($request);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $solicitud = Solicitud::create($validator->validated());
        return $this->createdResponse('Solicitud creada con éxito', $solicitud->load('tiposSolicitudes'));
    }

    public function show(Solicitud $solicitude)
    {
        return $this->successResponse('Solicitud encontrada', $solicitude->load('tiposSolicitudes'));
    }

    public function update(Request $request, Solicitud $solicitude)
    {
        $validator = $this->validaciones($request, true, $solicitude->idSolicitudes);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $solicitude->update($validator->validated());
        return $this->successResponse('Solicitud actualizada correctamente', $solicitude->load('tiposSolicitudes'));
    }

    public function destroy(Solicitud $solicitude)
    {
        $solicitude->delete();
        return $this->successResponse('Solicitud eliminada correctamente');
    }
}