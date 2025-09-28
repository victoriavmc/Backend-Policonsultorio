<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImagenRequest;
use App\Models\Imagen;
use App\Services\ImagenService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImagenController extends Controller
{
    use ApiResponse;

    protected ImagenService $imagenService;

    public function __construct(ImagenService $imagenService)
    {
        $this->imagenService = $imagenService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $imagenes = Imagen::latest()->get()->map(function ($imagen) {
                $exists = $this->imagenService->imageExists($imagen->imagen);

                return [
                    'id' => $imagen->idImagenes,
                    'path' => $imagen->imagen,
                    'url' => $exists ? $this->imagenService->getImageUrl($imagen->imagen) : null,
                    'exists' => $exists,
                    'created_at' => $imagen->created_at,
                    'updated_at' => $imagen->updated_at
                ];
            });

            return $this->successResponse(
                'Lista de imagenes recuperada correctamente',
                ['imagenes' => $imagenes]
            );
        } catch (\Exception $e) {
            Log::error('Error al obtener imagenes', [
                'error' => $e->getMessage()
            ]);

            return $this->errorResponse('Error al obtener las imagenes');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImagenRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $path = $this->imagenService->processImageAndSave(
                $request->file('imagen'),
                'webp'
            );

            $imagen = Imagen::create([
                'imagen' => $path
            ]);

            DB::commit();

            return $this->createdResponse('Imagen creada exitosamente', [
                'id' => $imagen->idImagenes,
                'path' => $path,
                'url' => $this->imagenService->getImageUrl($path),
                'created_at' => $imagen->created_at
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            if (isset($path)) {
                try {
                    $this->imagenService->deleteImage($path);
                } catch (\Exception $cleanupError) {
                    Log::error('Error limpiando imagen tras fallo BD', [
                        'path' => $path,
                        'error' => $cleanupError->getMessage()
                    ]);
                }
            }

            Log::error('Error de base de datos al crear imagen', [
                'error' => $e->getMessage(),
                'input' => $request->validated()
            ]);

            return $this->databaseErrorResponse('Error al crear la imagen');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al crear imagen', [
                'error' => $e->getMessage(),
                'input' => $request->validated()
            ]);

            return $this->errorResponse('Error inesperado. Intenta nuevamente');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $imagen = Imagen::findOrFail($id);

            if (!$this->imagenService->imageExists($imagen->imagen)) {
                Log::warning('Archivo físico no encontrado', [
                    'id' => $id,
                    'path' => $imagen->imagen
                ]);

                return $this->errorResponse('La imagen ya no está disponible en el servidor');
            }

            $imageInfo = $this->imagenService->getImageInfo($imagen->imagen);

            return $this->successResponse('Imagen encontrada', [
                'id' => $imagen->idImagenes,
                'path' => $imagen->imagen,
                'url' => $imageInfo['url'],
                'info' => [
                    'width' => $imageInfo['width'] ?? null,
                    'height' => $imageInfo['height'] ?? null,
                    'size' => $imageInfo['size_human'] ?? null,
                    'mime_type' => $imageInfo['mime_type'] ?? null
                ],
                'created_at' => $imagen->created_at,
                'updated_at' => $imagen->updated_at
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Imagen no encontrada', ['id' => $id]);
            return $this->notFoundResponse('Imagen no encontrada');
        } catch (\Exception $e) {
            Log::error('Error al obtener imagen', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->errorResponse('Error al obtener la imagen');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ImagenRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $imagen = Imagen::findOrFail($id);

            if ($request->hasValidImageFile()) {
                $oldPath = $imagen->imagen;

                $newPath = $this->imagenService->processImageAndSave(
                    $request->getImageFile(),
                    'webp'
                );

                $imagen->update(['imagen' => $newPath]);

                if ($this->imagenService->imageExists($oldPath)) {
                    $this->imagenService->deleteImage($oldPath);
                }

                DB::commit();

                return $this->successResponse('Imagen actualizada exitosamente', [
                    'id' => $imagen->idImagenes,
                    'new_path' => $newPath,
                    'url' => $this->imagenService->getImageUrl($newPath)
                ]);
            }

            DB::commit();
            return $this->successResponse('No hay cambios para aplicar');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error actualizando la imagen');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $imagen = Imagen::findOrFail($id);

            if ($this->imagenService->imageExists($imagen->imagen)) {
                $this->imagenService->deleteImage($imagen->imagen);
            }

            $imagen->delete();

            DB::commit();

            return $this->successResponse('Imagen eliminada exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            Log::warning('Imagen no encontrada para eliminar', ['id' => $id]);
            
            return $this->notFoundResponse('Imagen no encontrada');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al eliminar imagen', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->databaseErrorResponse('Error al eliminar la imagen');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al eliminar imagen', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->errorResponse('Error inesperado. Intenta nuevamente');
        }
    }
}
