<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\HorariosMedico;
use App\Models\Medico;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorariosMedicoController
{
    use ApiResponse;

    private function formatString(?string $pTexto, $dato=false):?string
        {
            if (!$pTexto) return null;
            
            if($dato){
                // Convierte a minúsculas, quita espacios, y capitaliza cada palabra
                return implode(' ', array_map('ucfirst', explode(' ', strtolower(trim($pTexto)))));
            }
            return ucfirst(strtolower(trim($pTexto)));
        }
    
    private function validaciones(Request $request, bool $isUpdate = false)
    {
        $data = $request->all();

        //Formateo el disponible
        if (isset($data['disponible'])) {
            $data['disponible'] = $this->formatString($data['disponible'], true);
        }

        $reglas = [
            'medicos_idMedicos' => ($isUpdate ? 'nullable' : 'required') . '|exists:medicos,idMedicos',
            'horarios_idHorarios' => ($isUpdate ? 'nullable' : 'required') . '|exists:horarios,idHorarios',
            'disponible' => ($isUpdate ? 'nullable' : 'required') . '|in:Disponible,No Disponible',
        ];

        return Validator::make($data, $reglas);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $horariosMedico=HorariosMedico::all();
                
        if($horariosMedico->isEmpty()){
            return $this->errorResponse('No se encontro horarios medicos',404);
        }
        
        return $this->successResponse('Horarios medicos encontrados',$horariosMedico,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validaciones($request);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $data = $validator->validate();

        // Verificar existencia real
        $medico = Medico::find($data['medicos_idMedicos']);
        $horario = Horario::find($data['horarios_idHorarios']);

        if (!$medico || !$horario) {
            return $this->errorResponse('El médico o el horario no existen', 'invalid_relation', 404);
        }

        // Evitar duplicado
        $existe = HorariosMedico::where('medicos_idMedicos', $data['medicos_idMedicos'])
                                ->where('horarios_idHorarios', $data['horarios_idHorarios'])
                                ->first();

        if ($existe) {
            return $this->errorResponse('El médico ya tiene asignado este horario', 'duplicate_relation', 409);
        }

        // Crear asignación
        $horarioMedico = HorariosMedico::create($data);

        return $this->createdResponse('Horario asignado correctamente al médico', $horarioMedico);
    }


    /**
     * Display the specified resource.
     */
    public function show(HorariosMedico $horariosMedico)
    {
        if (!$horariosMedico) {
            return $this->notFoundResponse('Asignación de horario no encontrada');
        }
        $horariosMedico->load(['medico', 'horario']);
        return $this->successResponse('Asignación encontrada', $horariosMedico, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HorariosMedico $horariosMedico)
    {
        if (!$horariosMedico) {
            return $this->notFoundResponse('Asignación de horario no encontrada');
        }

        $validator = $this->validaciones($request, true);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $horariosMedico->update($validator->validate());

        return $this->successResponse('Horario de médico actualizado correctamente', $horariosMedico, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HorariosMedico $horariosMedico)
    {
        if (!$horariosMedico) {
            return $this->notFoundResponse('Asignación de horario no encontrada');
        }

        $horariosMedico->delete();

        return $this->successResponse('Asignación de horario médico eliminada correctamente', null, 200);
    }
}