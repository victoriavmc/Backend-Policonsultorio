<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateDatoPersonalRequest extends FormRequest
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
        $idDatosPersonales = $this->route('dato_personal');

        return [
            'nombre' => 'sometimes|string|max:55',
            'apellido' => 'sometimes|string|max:55',
            'documento' => [
                'sometimes',
                'string',
                'max:55',
                Rule::unique('datosPersonales', 'documento')->ignore($idDatosPersonales, 'idDatosPersonales')
            ],
            'tipoDocumento' => 'sometimes|string|max:55',
            'genero' => 'sometimes|string|max:55',
            'fechaNacimiento' => 'sometimes|date|before:today|after:1900-01-01',
            'celular' => [
                'sometimes',
                'string',
                'max:55',
            ],
            'estado' => 'sometimes|string|in:Activo,Inactivo'
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
            'nombre' => 'nombre',
            'apellido' => 'apellido',
            'documento' => 'documento',
            'tipoDocumento' => 'tipo de documento',
            'genero' => 'género',
            'fechaNacimiento' => 'fecha de nacimiento',
            'celular' => 'celular',
            'estado' => 'estado'
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
