<?php

namespace App\Http\Requests\PacienteRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreatePacienteRequest extends FormRequest
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
            'particular' => 'required|boolean',
            'numAfiliado' => 'nullable|string|max:45',
            'datosPersonales_idDatosPersonales' => 'required|exists:datosPersonales,idDatosPersonales|unique:pacientes,datosPersonales_idDatosPersonales',
            'obrasSociales_idObrasSociales' => 'required|exists:obrasSociales,idObrasSociales',
            'estado' => 'nullable|string|in:Activo,Inactivo',
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