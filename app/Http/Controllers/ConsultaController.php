<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class ConsultaController extends Controller
{
    use ApiResponse;

    /**
     * Normaliza texto (minúsculas o capitalización).
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
     * Validación general.
     */
    private function validar(Request $request, bool $isUpdate = false)
    {
        $data = $request->all();

        // Normalización
        foreach (['rellenoReceta', 'rellenoDiagnostico', 'rellenoOrden', 'rellenoHistorial'] as $campo) {
            if (isset($data[$campo])) {
                $data[$campo] = $this->formatString($data[$campo]);
            }
        }

        $rules = [
            'rellenoReceta'                     => 'nullable|string|max:255',
            'rellenoDiagnostico'                => 'nullable|string|max:255',
            'rellenoOrden'                      => 'nullable|string|max:255',
            'rellenoHistorial'                  => 'nullable|string|max:255',
            'turnos_idTurnos'                   => ($isUpdate ? 'nullable' : 'required') . '|exists:turnos,idTurnos',
            'diagnosticos_idDiagnosticos'       => ($isUpdate ? 'nullable' : 'required') . '|exists:diagnosticos,idDiagnosticos',
            'solicitudes_idSolicitudes'         => ($isUpdate ? 'nullable' : 'required') . '|exists:solicitudes,idSolicitudes',
            'seguimientoTratamiento_idSeguimientoTratamiento' => 'nullable|exists:seguimientoTratamiento,idSeguimientoTratamiento',
            'historialClinicos_idHistorialClinico' => ($isUpdate ? 'nullable' : 'required') . '|exists:historialClinicos,idHistorialClinico',
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Listar todas las consultas.
     */
    public function index()
    {
        $consultas = Consulta::all();

        if ($consultas->isEmpty()) {
            return $this->errorResponse('No se encontraron consultas registradas', 404);
        }

        return $this->successResponse('Consultas encontradas correctamente', $consultas, 200);
    }

    /**
     * Crear una nueva consulta.
     */
    public function store(Request $request)
    {
        $validator = $this->validar($request);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $consulta = Consulta::create($validator->validate());

        return $this->createdResponse('Consulta creada correctamente', $consulta);
    }

    /**
     * Mostrar una consulta específica.
     */
    public function show(Consulta $consulta)
    {
        if (!$consulta) {
            return $this->notFoundResponse('Consulta no encontrada');
        }

        $consulta->load([
            'turno', 'diagnostico', 'solicitud', 'seguimientoTratamiento', 'historialClinico'
        ]);

        return $this->successResponse('Consulta encontrada correctamente', $consulta, 200);
    }

    /**
     * Actualizar una consulta.
     */
    public function update(Request $request, Consulta $consulta)
    {
        if (!$consulta) {
            return $this->notFoundResponse('Consulta no encontrada');
        }

        $validator = $this->validar($request, true);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $consulta->update($validator->validate());

        return $this->successResponse('Consulta actualizada correctamente', $consulta, 200);
    }

    /**
     * Eliminar una consulta (soft-delete o definitiva según necesidad).
     */
    public function destroy(Consulta $consulta)
    {
        if (!$consulta) {
            return $this->notFoundResponse('Consulta no encontrada');
        }

        $consulta->delete();

        return $this->successResponse('Consulta eliminada correctamente', null, 200);
    }
}