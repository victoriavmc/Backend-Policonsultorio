<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateDatoPersonalRequest extends FormRequest
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
            'nombre' => 'required|string|max:55',
            'apellido' => 'required|string|max:55',
            'documento' => 'required|string|max:55|unique:datosPersonales,documento',
            'tipoDocumento' => 'required|string|max:55',
            'genero' => 'required|string|max:55',
            'fechaNacimiento' => 'required|date|before:today|after:1900-01-01',
            'celular' => [
                'required',
                'string',
                'max:55',
            ],
            'estado' => 'nullable|string|in:Activo,Inactivo'
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
