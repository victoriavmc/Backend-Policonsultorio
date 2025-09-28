<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

class ImagenService
{
    protected ImageManager $imageManager;
    protected string $disk = 'public';
    protected string $directory = 'imagenes';

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Procesa y guarda una imagen
     */
    public function processImageAndSave(UploadedFile $file, string $encode): string
    {
        try {
            // Leer imagen
            $image = $this->imageManager->read($file);

            // Redimensionar manteniendo proporción
            $image->scale(800, 600);

            // Generar nombre único
            $filename = Str::uuid() . '.' . $encode;
            $path = $this->directory . '/' . $filename;

            // Codificar según formato
            if ($encode === 'webp') {
                $encodedImage = $image->toWebp(90);
            } else {
                $encodedImage = $image->toJpeg(90);
            }

            // Guardar en storage
            Storage::disk($this->disk)->put($path, $encodedImage);

            Log::info('Imagen procesada y guardada', [
                'original_name' => $file->getClientOriginalName(),
                'saved_path' => $path,
                'size' => $file->getSize()
            ]);

            return $path;
        } catch (\Exception $e) {
            Log::error('Error procesando imagen', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);
            throw $e;
        }
    }

    /**
     * Obtiene la URL pública de una imagen
     */
    public function getImageUrl(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Verifica si existe una imagen
     */
    public function imageExists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Elimina una imagen del storage
     */
    public function deleteImage(string $path): bool
    {
        try {
            if ($this->imageExists($path)) {
                $result = Storage::disk($this->disk)->delete($path);

                Log::info('Imagen eliminada', ['path' => $path]);

                return $result;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error eliminando imagen', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getImageInfo(string $path): ?array
    {
        if (!$this->imageExists($path)) {
            return null;
        }

        $fullPath = Storage::disk('public')->path($path);
        $imageInfo = getimagesize($fullPath);

        return [
            'path' => $path,
            'url' => $this->getImageUrl($path),
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'mime_type' => $imageInfo['mime'],
            'size_human' => $this->formatBytes(Storage::disk('public')->size($path))
        ];
    }
}
