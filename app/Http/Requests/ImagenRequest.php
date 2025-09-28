<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class ImagenRequest extends FormRequest
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
        $rules = [];

        $method = $this->getMethod();

        if ($method === 'POST') {
            $rules['imagen'] = 'required|file|image|mimes:png,jpg,jpeg,webp|max:5120';
        } elseif (in_array($method, ['PUT', 'PATCH'])) {
            $rules['imagen'] = 'nullable|file|image|mimes:png,jpg,jpeg,webp|max:5120';
        }

        return $rules;
    }

    /**
     * Verifica si hay un archivo de imagen válido
     * Método personalizado para evitar problemas con hasFile()
     */
    public function hasValidImageFile(): bool
    {
        return $this->hasFile('imagen') &&
            $this->file('imagen') instanceof UploadedFile &&
            $this->file('imagen')->isValid();
    }

    /**
     * Obtiene el archivo de imagen
     * Método personalizado para obtener el archivo de forma segura
     */
    public function getImageFile(): ?UploadedFile
    {
        if ($this->hasValidImageFile()) {
            return $this->file('imagen');
        }
        return null;
    }

    /**
     * Verifica si la request es para crear (POST)
     */
    public function isCreating(): bool
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * Verifica si la request es para actualizar (PUT/PATCH)
     */
    public function isUpdating(): bool
    {
        return in_array($this->getMethod(), ['PUT', 'PATCH']);
    }
}
