<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TratamientoController extends Controller
{
    use ApiResponse;

    /**
     * Formatea texto (minúsculas, mayúsculas, trim).
     */
    private function formatString(?string $texto, bool $capitalizar = false): ?string
    {
        if (!$texto) return null;

        $texto = trim(strtolower($texto));

        // Si se desea capitalizar tipo título (nombre propio)
        if ($capitalizar) {
            return implode(' ', array_map('ucfirst', explode(' ', $texto)));
        }

        // Si hay puntos, capitaliza cada oración
        $oraciones = array_map('trim', explode('.', $texto));
        $oracionesFormateadas = array_map(fn($oracion) => ucfirst($oracion), $oraciones);

        return implode('. ', $oracionesFormateadas);
    }

    /**
     * Valida y normaliza los datos de entrada.
     */
    private function validar(Request $request, bool $isUpdate = false)
    {
        $data = $request->all();

        // Normalización de texto
        if (isset($data['nombre'])) {
            $data['nombre'] = $this->formatString($data['nombre'], true);
        }

        if (isset($data['descripcion'])) {
            $data['descripcion'] = $this->formatString($data['descripcion']);
        }

        $rules = [
            'nombre'         => ($isUpdate ? 'nullable' : 'required') . '|string|max:45',
            'descripcion'    => 'nullable|string|max:255',
            'tiempoEstimado' => ($isUpdate ? 'nullable' : 'required') . '|date_format:H:i:s',
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Mostrar todos los tratamientos.
     */
    public function index()
    {
        $tratamientos = Tratamiento::all();

        if ($tratamientos->isEmpty()) {
            return $this->errorResponse('No se encontraron tratamientos registrados', 404);
        }

        return $this->successResponse('Tratamientos encontrados correctamente', $tratamientos, 200);
    }

    /**
     * Crear un nuevo tratamiento.
     */
    public function store(Request $request)
    {
        $validator = $this->validar($request);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $tratamiento = Tratamiento::create($validator->validate());

        return $this->createdResponse('Tratamiento creado correctamente', $tratamiento);
    }

    /**
     * Mostrar un tratamiento específico.
     */
    public function show(Tratamiento $tratamiento)
    {
        if (!$tratamiento) {
            return $this->notFoundResponse('Tratamiento no encontrado');
        }

        return $this->successResponse('Tratamiento encontrado', $tratamiento, 200);
    }

    /**
     * Actualizar un tratamiento.
     */
    public function update(Request $request, Tratamiento $tratamiento)
    {
        if (!$tratamiento) {
            return $this->notFoundResponse('Tratamiento no encontrado');
        }

        $validator = $this->validar($request, true);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $tratamiento->update($validator->validate());

        return $this->successResponse('Tratamiento actualizado correctamente', $tratamiento, 200);
    }

    /**
     * Eliminar un tratamiento
     */
    public function destroy(Tratamiento $tratamiento)
    {
        if (!$tratamiento) {
            return $this->notFoundResponse('Tratamiento no encontrado');
        }

        $tratamiento->delete();

        return $this->successResponse('Tratamiento eliminado correctamente', null, 200);
    }
}