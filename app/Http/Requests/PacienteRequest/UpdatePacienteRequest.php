<?php

namespace App\Http\Requests\PacienteRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdatePacienteRequest extends FormRequest
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
        $idPaciente = $this->route('id');

        return [
            'particular' => 'sometimes|boolean',
            'numAfiliado' => 'sometimes|nullable|string|max:45',
            'datosPersonales_idDatosPersonales' => [
                'sometimes',
                'exists:datosPersonales,idDatosPersonales',
                Rule::unique('pacientes', 'datosPersonales_idDatosPersonales')->ignore($idPaciente, 'idPacientes')
            ],
            'obrasSociales_idObrasSociales' => 'sometimes|exists:obrasSociales,idObrasSociales',
            'estado' => 'sometimes|string|in:Activo,Inactivo',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'particular' => 'particular',
            'numAfiliado' => 'número de afiliado',
            'datosPersonales_idDatosPersonales' => 'datos personales',
            'obrasSociales_idObrasSociales' => 'obra social',
            'estado' => 'estado',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}