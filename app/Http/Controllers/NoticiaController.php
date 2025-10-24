<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Imagen;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class NoticiaController extends Controller
{
    use ApiResponse;

    /**
     * Formatea texto (minúsculas, mayúsculas, trim).
     */
    private function formatString(?string $texto, bool $capitalizar = false): ?string
    {
        if (!$texto) return null;

        $texto = trim(strtolower($texto));

        if ($capitalizar) {
            return implode(' ', array_map('ucfirst', explode(' ', $texto)));
        }

        $oraciones = array_map('trim', explode('.', $texto));
        $oracionesFormateadas = array_map(fn($o) => ucfirst($o), $oraciones);

        return implode('. ', $oracionesFormateadas);
    }

    /**
     * Validar datos de entrada.
     */
    private function validar(Request $request, bool $isUpdate = false)
    {
        $data = $request->all();

        foreach (['titulo', 'descripcion'] as $campo) {
            if (isset($data[$campo])) {
                $data[$campo] = $this->formatString($data[$campo]);
            }
        }

       $rules = [
            'titulo'      => ($isUpdate ? 'nullable' : 'required') . '|string|max:255',
            'descripcion' => ($isUpdate ? 'nullable' : 'required') . '|string|max:255',
            'imagen'      => 'nullable|sometimes|image|mimes:jpeg,png,jpg,webp|max:4096',
            'url'         => 'nullable|sometimes|string|max:255|url',
            'fecha'       => ($isUpdate ? 'nullable' : 'required') . '|date_format:Y-m-d',
            'imagenes'    => 'nullable|array',
            'imagenes.*'  => 'exists:imagenes,idImagenes',
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Mostrar todas las noticias con sus imágenes.
     */
    public function index()
    {
        $noticias = Noticia::with('imagenes')->get();

        if ($noticias->isEmpty()) {
            return $this->errorResponse('No se encontraron noticias registradas.', 404);
        }

        return $this->successResponse('Listado de noticias obtenido correctamente.', $noticias, 200);
    }

    /**
     * Crear una nueva noticia.
     */
    public function store(Request $request)
    {
        $validator = $this->validar($request);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación.', $validator->errors());
        }

        $data = $validator->validated();

        // Guardar imagen principal
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('noticias', 'public');
            $data['imagen'] = $path;
        }

        $noticia = Noticia::create($data);

        // Asociar imágenes secundarias (si se enviaron)
        if (!empty($data['imagenes'])) {
            $noticia->imagenes()->syncWithoutDetaching($data['imagenes']);
        }

        return $this->createdResponse('Noticia creada correctamente.', $noticia->load('imagenes'));
    }

    /**
     * Mostrar una noticia específica con sus imágenes.
     */
    public function show($id)
    {
        $noticia = Noticia::with('imagenes')->find($id);

        if (!$noticia) {
            return $this->notFoundResponse('Noticia no encontrada.');
        }

        return $this->successResponse('Noticia obtenida correctamente.', $noticia, 200);
    }


    /**
     * Actualizar una noticia existente.
     */
    public function update(Request $request, Noticia $noticia)
    {
        $validator = $this->validar($request, true);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación.', $validator->errors());
        }

        $data = $validator->validated();

        if ($request->hasFile('imagen')) {
            if ($noticia->imagen && Storage::disk('public')->exists($noticia->imagen)) {
                Storage::disk('public')->delete($noticia->imagen);
            }
            $path = $request->file('imagen')->store('noticias', 'public');
            $data['imagen'] = $path;
        }

        $noticia->update($data);

        if (!empty($data['imagenes'])) {
            $noticia->imagenes()->syncWithoutDetaching($data['imagenes']);
        }

        return $this->successResponse('Noticia actualizada correctamente.', $noticia->load('imagenes'), 200);
    }


    /**
     * Eliminar una noticia.
     */
    public function destroy(Noticia $noticia)
    {
        if (!$noticia) {
            return $this->notFoundResponse('Noticia no encontrada.');
        }

        // Eliminar imagen principal del storage
        if ($noticia->imagen && Storage::disk('public')->exists($noticia->imagen)) {
            Storage::disk('public')->delete($noticia->imagen);
        }

        // Desvincular imágenes secundarias
        $noticia->imagenes()->detach();

        $noticia->delete();

        return $this->successResponse('Noticia eliminada correctamente.', null, 200);
    }

    /**
     * Asociar imágenes a una noticia existente (sin duplicar las anteriores).
     */
    public function agregarImagenes(Request $request, $id)
    {
        $noticia = Noticia::find($id);

        if (!$noticia) {
            return $this->notFoundResponse('Noticia no encontrada.');
        }

        $validator = Validator::make($request->all(), [
            'imagenes' => 'sometimes|array',
            'imagenes.*' => 'exists:imagenes,idImagenes',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación.', $validator->errors());
        }

        $noticia->imagenes()->syncWithoutDetaching($request->imagenes);

        return $this->successResponse('Imágenes asociadas correctamente a la noticia.', $noticia->load('imagenes'));
    }

    /**
     * Eliminar una imagen asociada a una noticia (pivot).
     */
    public function eliminarImagen($idNoticia, $idImagen)
    {
        $noticia = Noticia::find($idNoticia);

        if (!$noticia) {
            return $this->notFoundResponse('Noticia no encontrada.');
        }

        $noticia->imagenes()->detach($idImagen);

        return $this->successResponse('Imagen desvinculada correctamente de la noticia.', $noticia->load('imagenes'));
    }
}