<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuditoriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'modulo' => 'required|string|max:45',
            'accion' => 'required|string|max:255',
            'valorAnterior' => 'nullable|string|max:255',
            'valorNuevo' => 'nullable|string|max:255',
            'fecha' => 'required|date|before_or_equal:now',
            'idReferente' => 'nullable|integer|min:1',
            'usuarios_idUsuarios' => 'required|numeric|exists:usuarios,idUsuarios',
        ];
    }

    public function messages(): array
    {
        return [
            'usuarios_idUsuarios.required' => 'El campo usuario es obligatorio.',
            'usuarios_idUsuarios.numeric' => 'El campo usuario debe ser una opcion válida.',
            'usuarios_idUsuarios.exists' => 'El usuario seleccionado no existe en el sistema.',
        ];
    }
}
