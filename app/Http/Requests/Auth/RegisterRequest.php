<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:usuarios,email'
            ],
            'password' => [
                'required',
                'string',
            ],

            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'documento' => [
                'required',
                'string',
                'max:20',
                'unique:datosPersonales,documento'
            ],
            'tipoDocumento' => ['required', 'string', 'max:50', 'in:DNI,PASAPORTE'],
            'celular' => ['required', 'string', 'max:20'],
            'fechaNacimiento' => ['required', 'date', 'before:today'],
            'genero' => ['required', 'string', 'max:50', 'in:Masculino,Femenino,Otro'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'documento.unique' => 'Este documento ya está registrado.',
            'fechaNacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        ];
    }
}
