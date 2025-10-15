<?php

namespace App\Http\Controllers;

use App\Models\ObraSocial;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ObraSocialController extends Controller
{
    use ApiResponse;

    private function validaciones(Request $request, bool $isUpdate = false, ?int $id = null)
    {
        $rules = [
            'nombre' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:45',
                Rule::unique('obrasSociales', 'nombre')->ignore($id, 'idObrasSociales'),
            ],
            'estado' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                Rule::in(['Activo', 'Inactivo', 'Suspendido']),
            ],
        ];

        return Validator::make($request->all(), $rules);
    }

    public function index()
    {
        $obrasSociales = ObraSocial::where('estado', 'Activo')->get();
        if ($obrasSociales->isEmpty()) {
            return $this->notFoundResponse('No se encontraron obras sociales activas');
        }
        return $this->successResponse('Obras sociales encontradas', $obrasSociales);
    }

    public function getAll()
    {
        $obrasSociales = ObraSocial::all();
        if ($obrasSociales->isEmpty()) {
            return $this->notFoundResponse('No se encontraron obras sociales');
        }
        return $this->successResponse('Obras sociales encontradas', $obrasSociales);
    }

    public function store(Request $request)
    {
        $validator = $this->validaciones($request);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $obraSocial = ObraSocial::create($validator->validated());
        return $this->createdResponse('Obra social creada con éxito', $obraSocial);
    }

    public function show($id)
    {
        $obraSocial = ObraSocial::find($id);
        if (!$obraSocial) {
            return $this->notFoundResponse('Obra social no encontrada');
        }
        return $this->successResponse('Obra social encontrada', $obraSocial);
    }

    public function update(Request $request, $id)
    {
        $obraSocial = ObraSocial::find($id);
        if (!$obraSocial) {
            return $this->notFoundResponse('Obra social no encontrada');
        }

        $validator = $this->validaciones($request, true, $obraSocial->idObrasSociales);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $obraSocial->update($validator->validated());
        return $this->successResponse('Obra social actualizada correctamente', $obraSocial);
    }

    public function destroy($id)
    {
        $obraSocial = ObraSocial::find($id);
        if (!$obraSocial) {
            return $this->notFoundResponse('Obra social no encontrada');
        }

        $obraSocial->update(['estado' => 'Inactivo']);
        return $this->successResponse('Obra social desactivada correctamente');
    }
}