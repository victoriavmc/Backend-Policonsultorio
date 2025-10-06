<?php

namespace App\Http\Controllers;

use App\Models\Diagnostico;
use App\Models\ObservacionesIA;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiagnosticosController extends Controller
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

        // Normalización de texto
        foreach (['tipo', 'descripcion', 'observacion', 'clasificacion', 'contenidoFormulario'] as $campo) {
            if (isset($data[$campo])) {
                $data[$campo] = $this->formatString($data[$campo]);
            }
        }

        $rules = [
            'fecha'                       => ($isUpdate ? 'nullable' : 'required') . '|date_format:Y-m-d',
            'tipo'                        => 'nullable|string|max:255',
            'descripcion'                 => 'nullable|string|max:255',
            'observacion'                 => 'nullable|string|max:255',
            'clasificacion'               => ($isUpdate ? 'nullable' : 'required') . '|string|max:255',
            'contenidoFormulario'         => 'nullable|string|max:255', //Guardar despues esto
            'observacionesIA_idObservacionesIA' => 'nullable|exists:observacionesIA,idObservacionesIA',
            'recetas_idRecetas'           => 'nullable|exists:recetas,idRecetas',
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Mostrar todos los diagnósticos.
     */
    public function index()
    {
        $diagnosticos = Diagnostico::with(['observacionIA', 'receta'])->get();

        if ($diagnosticos->isEmpty()) {
            return $this->errorResponse('No se encontraron diagnósticos registrados.', 404);
        }

        return $this->successResponse('Diagnósticos obtenidos correctamente.', $diagnosticos, 200);
    }

    /**
     * Crear un nuevo diagnóstico.
     */
    public function store(Request $request)
    {
        $validator = $this->validar($request);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación.', $validator->errors());
        }

        $data = $validator->validated();

        // Llamar receta y de la ia imagenes

        $diagnostico = Diagnostico::create($data);

        return $this->createdResponse('Diagnóstico registrado correctamente.', $diagnostico);
    }

    /**
     * Mostrar un diagnóstico específico.
     */
    public function show(Diagnostico $diagnostico)
    {
        if (!$diagnostico) {
            return $this->notFoundResponse('Diagnóstico no encontrado.');
        }

        $diagnostico->load(['observacionIA', 'receta']);

        return $this->successResponse('Diagnóstico obtenido correctamente.', $diagnostico, 200);
    }

    /**
     * Actualizar un diagnóstico existente.
     */
    public function update(Request $request, Diagnostico $diagnostico)
    {
        if (!$diagnostico) {
            return $this->notFoundResponse('Diagnóstico no encontrado.');
        }

        $validator = $this->validar($request, true);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación.', $validator->errors());
        }

        $diagnostico->update($validator->validated());

        return $this->successResponse('Diagnóstico actualizado correctamente.', $diagnostico, 200);
    }

    /**
     * Eliminar un diagnóstico.
     */
    public function destroy(Diagnostico $diagnostico)
    {
        if (!$diagnostico) {
            return $this->notFoundResponse('Diagnóstico no encontrado.');
        }

        $diagnostico->delete();

        return $this->successResponse('Diagnóstico eliminado correctamente.', null, 200);
    }
}