<?php

namespace App\Http\Controllers;

use App\Models\FormularioPDF;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormularioPDFController extends Controller
{
    use ApiResponse;

    private function validaciones(Request $request, bool $isUpdate = false)
    {
        $rules = [
            'tipo' => [$isUpdate ? 'sometimes' : 'nullable', 'string', 'max:45'],
            'nombre' => [$isUpdate ? 'sometimes' : 'nullable', 'string', 'max:45'],
            'formulario' => [$isUpdate ? 'sometimes' : 'nullable', 'string', 'max:255'],
        ];

        return Validator::make($request->all(), $rules);
    }

    public function index()
    {
        $formularios = FormularioPDF::all();
        if ($formularios->isEmpty()) {
            return $this->notFoundResponse('No se encontraron formularios PDF');
        }
        return $this->successResponse('Formularios PDF encontrados', $formularios);
    }

    public function store(Request $request)
    {
        $validator = $this->validaciones($request);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $formularioPDF = FormularioPDF::create($validator->validated());
        return $this->createdResponse('Formulario PDF creado con éxito', $formularioPDF);
    }

    public function show($id)
    {
        $formularioPDF = FormularioPDF::find($id);
        if (!$formularioPDF) {
            return $this->notFoundResponse('Formulario PDF no encontrado');
        }
        return $this->successResponse('Formulario PDF encontrado', $formularioPDF);
    }

    public function update(Request $request, $id)
    {
        $formularioPDF = FormularioPDF::find($id);
        if (!$formularioPDF) {
            return $this->notFoundResponse('Formulario PDF no encontrado');
        }

        $validator = $this->validaciones($request, true);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $formularioPDF->update($validator->validated());
        return $this->successResponse('Formulario PDF actualizado correctamente', $formularioPDF);
    }

    public function destroy($id)
    {
        $formularioPDF = FormularioPDF::find($id);
        if (!$formularioPDF) {
            return $this->notFoundResponse('Formulario PDF no encontrado');
        }

        $formularioPDF->delete();
        return $this->successResponse('Formulario PDF eliminado correctamente');
    }
}