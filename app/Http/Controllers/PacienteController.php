<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    use ApiResponse;

    private function validaciones(Request $request, bool $isUpdate = false, ?int $id = null)
    {
        $rules = [
            'particular' => [$isUpdate ? 'sometimes' : 'required', 'boolean'],
            'numAfiliado' => 'nullable|string|max:45',
            'datosPersonales_idDatosPersonales' => [
                $isUpdate ? 'sometimes' : 'required',
                'exists:datosPersonales,idDatosPersonales',
                Rule::unique('pacientes', 'datosPersonales_idDatosPersonales')->ignore($id, 'idPacientes'),
            ],
            'obrasSociales_idObrasSociales' => [$isUpdate ? 'sometimes' : 'required', 'exists:obrasSociales,idObrasSociales'],
        ];

        return Validator::make($request->all(), $rules);
    }

    public function index()
    {
        $pacientes = Paciente::with(['datosPersonales', 'obraSocial'])->get();

        if ($pacientes->isEmpty()) {
            return $this->notFoundResponse('No se encontraron pacientes');
        }

        return $this->successResponse('Pacientes encontrados', $pacientes);
    }

    public function store(Request $request)
    {
        $validator = $this->validaciones($request);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $paciente = Paciente::create($validator->validated());
        return $this->createdResponse('Paciente creado con éxito', $paciente->load(['datosPersonales', 'obraSocial']));
    }

    public function show($id)
    {
        $paciente = Paciente::find($id);

        if (!$paciente) {
            return $this->notFoundResponse('Paciente no encontrado');
        }

        $user = Auth::user();

        if ($user->rol === 'paciente') {
            if ($user->datosPersonales_idDatosPersonales != $paciente->datosPersonales_idDatosPersonales) {
                return $this->errorResponse('No tienes permiso para ver este registro', 'forbidden', 403);
            }
        }

        $paciente->load('datosPersonales', 'obraSocial');

        return $this->successResponse('Paciente encontrado', $paciente);
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return $this->notFoundResponse('Paciente no encontrado');
        }

        $validator = $this->validaciones($request, true, $paciente->idPacientes);
        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $paciente->update($validator->validated());
        return $this->successResponse('Paciente actualizado correctamente', $paciente->load(['datosPersonales', 'obraSocial']));
    }

    public function destroy($id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return $this->notFoundResponse('Paciente no encontrado');
        }

        // A "destroy" on a patient deactivates the associated user
        if ($paciente->datosPersonales && $paciente->datosPersonales->usuarios) {
            $paciente->datosPersonales->usuarios->update(['estado' => 'Inactivo']);
            return $this->successResponse('Paciente (usuario asociado) desactivado correctamente');
        }

        return $this->errorResponse('No se pudo encontrar el usuario asociado a este paciente para desactivarlo.', 'user_not_found', 404);
    }
}
