<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Support\Facades\DB;

class TurnoController extends Controller
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
     * Valida y normaliza los datos de entrada.
     */
    private function validar(Request $request, bool $isUpdate = false, $idTurno = null)
    {
        $data = $request->all();

        if (isset($data['prioridad'])) {
            $data['prioridad'] = $this->formatString($data['prioridad'], true);
        }
        if (isset($data['estado'])) {
            $data['estado'] = $this->formatString($data['estado'], true);
        }

        if (isset($data['particular'])) {
            if (in_array(strtolower($data['particular']), ['si', 'true', '1'])) {
                $data['particular'] = true;
            } elseif (in_array(strtolower($data['particular']), ['no', 'false', '0'])) {
                $data['particular'] = false;
            }
        }

        if (!empty($data['nombreObraSocial'])) {
            $data['particular'] = false;
        } elseif (isset($data['particular']) && $data['particular'] === true) {
            $data['nombreObraSocial'] = null;
        }
        
        $rules = [
            'fecha'                       => ($isUpdate ? 'nullable' : 'required') . '|date_format:Y-m-d',
            'hora'                        => ($isUpdate ? 'nullable' : 'required') . '|date_format:H:i:s',
            'particular'                  => ($isUpdate ? 'nullable' : 'required') . '|boolean',
            'nombreObraSocial'            => 'nullable|string|max:45',
            'prioridad'                   => ($isUpdate ? 'nullable' : 'required') . '|in:Con Turno,Sin Turno',
            'estado'                      => ($isUpdate ? 'nullable' : 'required') . '|in:Pendiente,Confirmado,En espera,En consulta,Finalizado,Cancelado,En espera sin Turno',
            'duracion'                    => 'nullable|date_format:H:i:s',
            'horaLlegada'                 => 'nullable|date_format:H:i:s',
            'horaSalida'                  => 'nullable|date_format:H:i:s',
            'pacientes_idPacientes'       => ($isUpdate ? 'nullable' : 'required') . '|exists:pacientes,idPacientes',
            'medicos_idMedicos'           => ($isUpdate ? 'nullable' : 'required') . '|exists:medicos,idMedicos',
            'tratamientos_idTratamientos' => ($isUpdate ? 'nullable' : 'required') . '|exists:tratamientos,idTratamientos',
            'seguimientosPagos_idSeguimientosPagos' => ($isUpdate ? 'nullable' : 'required') . '|exists:seguimientosPagos,idSeguimientosPagos',
        ];

        // --- Validador
        $validator = Validator::make($data, $rules);

        // --- Regla personalizada: médico no puede tener turno duplicado
        $validator->after(function ($validator) use ($data, $idTurno) {
            if (!empty($data['medicos_idMedicos']) && !empty($data['fecha']) && !empty($data['hora'])) {
                $existe = DB::table('turnos')
                    ->where('medicos_idMedicos', $data['medicos_idMedicos'])
                    ->where('fecha', $data['fecha'])
                    ->where('hora', $data['hora'])
                    ->when($idTurno, fn($q) => $q->where('idTurnos', '!=', $idTurno))
                    ->exists();

                if ($existe) {
                    $validator->errors()->add(
                        'hora',
                        'El médico ya tiene un turno asignado en esa fecha y hora.'
                    );
                }
            }
        });

        return $validator;
    }



    /**
     * Mostrar todos los turnos.
     */
    public function index()
    {
        $turnos = Turno::with(['paciente', 'medico', 'tratamiento', 'seguimientoPago'])->get();

        if ($turnos->isEmpty()) {
            return $this->errorResponse('No se encontraron turnos registrados', 404);
        }

        return $this->successResponse('Turnos encontrados correctamente', $turnos, 200);
    }

    /**
     * Crear un nuevo turno.
     */
    public function store(Request $request)
    {
        $validator = $this->validar($request);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $turno = Turno::create($validator->validate());

        return $this->createdResponse('Turno creado correctamente', $turno);
    }

    /**
     * Mostrar un turno específico.
     */
    public function show(Turno $turno)
    {
        if (!$turno) {
            return $this->notFoundResponse('Turno no encontrado');
        }

        $turno->load(['paciente', 'medico', 'tratamiento', 'seguimientoPago']);

        return $this->successResponse('Turno encontrado', $turno, 200);
    }

    /**
     * Actualizar un turno existente.
     */
    public function update(Request $request, Turno $turno)
    {
        if (!$turno) {
            return $this->notFoundResponse('Turno no encontrado');
        }

        $validator = $this->validar($request, true);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $turno->update($validator->validate());

        return $this->successResponse('Turno actualizado correctamente', $turno, 200);
    }

    /**
     * Eliminar un turno.
     */
    public function destroy(Turno $turno)
    {
        if (!$turno) {
            return $this->notFoundResponse('Turno no encontrado');
        }

        // Si ya está cancelado, evitamos repetir acción
        if ($turno->estado === 'Cancelado') {
            return $this->errorResponse('El turno ya se encuentra cancelado.', 400);
        }

        // Cambiar el estado a Cancelado
        $turno->estado = 'Cancelado';
        $turno->save();

        return $this->successResponse('El turno fue cancelado correctamente.', $turno, 200);
    }

    
}