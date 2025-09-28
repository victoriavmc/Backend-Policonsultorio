<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecetaRequest extends FormRequest
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
            'nombreMedicame' => 'required|string|max:45',
            'presentacion' => 'required|string|max:45',
            'concentracion' => 'required|string|max:45',
            'cantidad' => 'required|string|max:45',
            'fecha' => 'required|date',
            'vigencia' => 'required|date|after_or_equal:fecha',
            'codigoRecetaElecti' => 'required|string',
            'observacion' => 'nullable|string|max:255',
            'indicaciones_idIndi' => 'required|integer|exists:indicaciones,idIndicaciones',
        ];
    }
}
