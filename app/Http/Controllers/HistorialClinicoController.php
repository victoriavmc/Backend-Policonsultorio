<?php

namespace App\Http\Controllers;

use App\Models\HistorialClinico;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistorialClinicoController extends Controller
{
    use ApiResponse;

    /**
     * Formatea texto (minúsculas, mayúsculas, trim).
     */
    private function formatString(?string $texto, bool $capitalizar = false): ?string
    {
        if (!$texto) return null;

        $texto = trim(strtolower($texto));

        if ($capitalizar) {
            return implode(' ', array_map('ucfirst', explode(' ', $texto)));
        }

        $oraciones = array_map('trim', explode('.', $texto));
        $oracionesFormateadas = array_map(fn($o) => ucfirst($o), $oraciones);

        return implode('. ', $oracionesFormateadas);
    }

    /**
     * Validación y normalización de datos.
     */
    private function validar(Request $request, bool $isUpdate = false)
    {
        $data = $request->all();

        // Normalizar texto
        foreach (['descripcion', 'causaConsulta', 'contenidoFormulario'] as $campo) {
            if (isset($data[$campo])) {
                $data[$campo] = $this->formatString($data[$campo]);
            }
        }

        $rules = [
            'descripcion'         => 'nullable|string|max:255',
            'causaConsulta'       => 'nullable|string|max:255',
            'fecha'               => ($isUpdate ? 'nullable' : 'required') . '|date_format:Y-m-d',
            'contenidoFormulario' => 'nullable|string|max:255', // Guardar pdf
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Mostrar todos los historiales clínicos.
     */
    public function index()
    {
        $historiales = HistorialClinico::all();

        if ($historiales->isEmpty()) {
            return $this->errorResponse('No se encontraron historiales clínicos registrados.', 404);
        }

        return $this->successResponse('Historiales clínicos obtenidos correctamente.', $historiales, 200);
    }

    /**
     * Crear un nuevo historial clínico.
     */
    public function store(Request $request)
    {
        $validator = $this->validar($request);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación.', $validator->errors());
        }

        $historial = HistorialClinico::create($validator->validated());

        return $this->createdResponse('Historial clínico creado correctamente.', $historial);
    }

    /**
     * Mostrar un historial clínico específico.
     */
    public function show(HistorialClinico $historialClinico)
    {
        if (!$historialClinico) {
            return $this->notFoundResponse('Historial clínico no encontrado.');
        }

        return $this->successResponse('Historial clínico obtenido correctamente.', $historialClinico, 200);
    }

    /**
     * Actualizar un historial clínico existente.
     */
    public function update(Request $request, HistorialClinico $historialClinico)
    {
        if (!$historialClinico) {
            return $this->notFoundResponse('Historial clínico no encontrado.');
        }

        $validator = $this->validar($request, true);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación.', $validator->errors());
        }

        $historialClinico->update($validator->validated());

        return $this->successResponse('Historial clínico actualizado correctamente.', $historialClinico, 200);
    }

    /**
     * Eliminar un historial clínico.
     */
    public function destroy(HistorialClinico $historialClinico)
    {
        if (!$historialClinico) {
            return $this->notFoundResponse('Historial clínico no encontrado.');
        }

        $historialClinico->delete();

        return $this->successResponse('Historial clínico eliminado correctamente.', null, 200);
    }
}