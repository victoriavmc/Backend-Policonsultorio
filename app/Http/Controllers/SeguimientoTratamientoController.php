<?php

namespace App\Http\Controllers;

use App\Models\SeguimientoTratamiento;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeguimientoTratamientoController extends Controller
{
    use ApiResponse;

    private function validaciones(Request $request, bool $isUpdate = false)
    {
        $rules = [
            'fechaInicio' => [$isUpdate ? 'sometimes' : 'required', 'date'],
            'fechaFin' => [$isUpdate ? 'sometimes' : 'required', 'date', 'after_or_equal:fechaInicio'],
            'descripcion' => [$isUpdate ? 'sometimes' : 'required', 'string'],
        ];

        return Validator::make($request->all(), $rules);
    }

    public function index()
    {
        $seguimientos = SeguimientoTratamiento::all();
        if ($seguimientos->isEmpty()) {
            return $this->notFoundResponse('No se encontraron seguimientos de tratamientos');
        }
        return $this->successResponse('Seguimientos de tratamientos encontrados', $seguimientos);
    }

    public function store(Request $request)
    {
        $validator = $this->validaciones($request);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $seguimiento = SeguimientoTratamiento::create($validator->validated());
        return $this->createdResponse('Seguimiento de tratamiento creado con éxito', $seguimiento);
    }

    public function show($id)
    {
        $seguimientoTratamiento = SeguimientoTratamiento::find($id);
        if (!$seguimientoTratamiento) {
            return $this->notFoundResponse('Seguimiento de tratamiento no encontrado');
        }
        return $this->successResponse('Seguimiento de tratamiento encontrado', $seguimientoTratamiento);
    }

    public function update(Request $request, $id)
    {
        $seguimientoTratamiento = SeguimientoTratamiento::find($id);
        if (!$seguimientoTratamiento) {
            return $this->notFoundResponse('Seguimiento de tratamiento no encontrado');
        }

        $validator = $this->validaciones($request, true);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $seguimientoTratamiento->update($validator->validated());
        return $this->successResponse('Seguimiento de tratamiento actualizado correctamente', $seguimientoTratamiento);
    }

    public function destroy($id)
    {
        $seguimientoTratamiento = SeguimientoTratamiento::find($id);
        if (!$seguimientoTratamiento) {
            return $this->notFoundResponse('Seguimiento de tratamiento no encontrado');
        }

        $seguimientoTratamiento->delete();
        return $this->successResponse('Seguimiento de tratamiento eliminado correctamente');
    }
}