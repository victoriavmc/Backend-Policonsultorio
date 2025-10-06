<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MedicoController
{
    use ApiResponse;
    
    private function formatString(?string $pTexto, bool $capitalizeEachWord = false): ?string
    {
        if (!$pTexto) return $pTexto;

        $texto = trim(strtolower($pTexto));

        return $capitalizeEachWord
            ? implode(' ', array_map('ucfirst', explode(' ', $texto)))
            : ucfirst($texto);
    }

    private function validaciones(Request $request, bool $isUpdate = false)
    {
        $data = $request->all();

        // Formatear strings
        if (isset($data['especialidad'])) {
            $data['especialidad'] = $this->formatString($data['especialidad'], true);
        }
        if (isset($data['consultorio'])) {
            $data['consultorio'] = $this->formatString($data['consultorio'], true);
        }

        $reglas = [
            'usuarios_idUsuarios' => ($isUpdate ? 'nullable' : 'required') . '|exists:usuarios,idUsuarios',
            'matricula' => ($isUpdate ? 'nullable' : 'required') . '|string|max:45|unique:medicos,matricula' . ($isUpdate && $request->route('medico') ? ',' . $request->route('medico')->idMedicos . ',idMedicos' : ''),
            'especialidad' => ($isUpdate ? 'nullable' : 'required') . '|string|max:255',
            'consultorio' => ($isUpdate ? 'nullable' : 'required') . '|string|max:45',
            'firmaVirtual' => ($isUpdate ? 'nullable' : 'required') . '|string|max:255',
        ];

        return Validator::make($data, $reglas);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $medico=Medico::all();

        if($medico->isEmpty()){
            return $this->errorResponse('No se encontro medicos',404);
        }
        
        return $this->successResponse('Medicos encontrados',$medico,200);
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
        $userId = $data['usuarios_idUsuarios'];

        // Verificar usuario y relación existente
        $user = User::find($userId);
        if (!$user) {
            return $this->errorResponse('El usuario no existe', 'invalid_user', 404);
        }

        $existMedico = Medico::where('usuarios_idUsuarios', $userId)->first();
        if ($existMedico) {
            return $this->errorResponse('Ya existe un médico asociado a este usuario', 'duplicate_relation', 409);
        }

        // Crear médico
        $medico = Medico::create($data);

        return $this->createdResponse('Médico creado con éxito', $medico);
    }


    /**
     * Display the specified resource.
     */
    public function show(Medico $medico)
    {
        if (!$medico) {
            return $this->notFoundResponse('Médico no encontrado');
        }

        return $this->successResponse('Médico encontrado', $medico->load('usuario'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medico $medico)
    {
        if (!$medico) {
            return $this->notFoundResponse('Médico no encontrado');
        }

        $validator = $this->validaciones($request, true);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $data = $validator->validate();
        $medico->update($data);

        return $this->successResponse('Médico actualizado correctamente', $medico, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medico $medico) 
    {
        // Validar existencia
        if (!$medico) {
            return $this->notFoundResponse('Médico no encontrado');
        }

        // Cargar usuario relacionado
        $usuario = $medico->usuario;

        if (!$usuario) {
            return $this->errorResponse(
                'No se encontró el usuario asociado a este médico.',
                'user_not_found',
                404
            );
        }

        // Cambiar estado del usuario
        $usuario->update(['estado' => 'Inactivo']);

        return $this->successResponse(
            'Usuario asociado al médico desactivado correctamente',
            [
                'idUsuario' => $usuario->idUsuarios,
                'estadoNuevo' => $usuario->estado
            ],
            200
        );
    }
}