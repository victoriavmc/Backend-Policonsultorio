<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecetaRequest;
use App\Models\Receta;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecetaController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $recetas = Receta::all();

        return $this->successResponse('Lista de recetas recuperada correctamente', $recetas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RecetaRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $receta = Receta::create($request->validated());

            DB::commit();

            return $this->createdResponse('Receta creada exitosamente', $receta);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al crear receta', [
                'error' => $e->getMessage(),
                'input' => $request->validated()
            ]);

            return $this->databaseErrorResponse('Error al crear la receta');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al crear receta', [
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
            $receta = Receta::findOrFail($id);

            return $this->successResponse('Receta encontrada', $receta);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Receta no encontrada', ['id' => $id]);

            return $this->notFoundResponse('Receta no encontrada');
        } catch (\Exception $e) {
            Log::error('Error al obtener receta', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->errorResponse('Error al obtener la receta');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RecetaRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $receta = Receta::findOrFail($id);
            $receta->update($request->validated());

            DB::commit();

            return $this->successResponse('Receta actualizada exitosamente', $receta->fresh());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            Log::warning('Receta no encontrada para actualizar', ['id' => $id]);

            return $this->notFoundResponse('Receta no encontrada');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al actualizar receta', [
                'error' => $e->getMessage(),
                'id' => $id,
                'input' => $request->validated()
            ]);

            return $this->databaseErrorResponse('Error al actualizar la receta');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al actualizar receta', [
                'error' => $e->getMessage(),
                'id' => $id,
                'input' => $request->validated()
            ]);

            return $this->errorResponse('Error inesperado. Intenta nuevamente');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $receta = Receta::findOrFail($id);
            $receta->delete();

            DB::commit();

            return $this->successResponse('Receta eliminada exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            Log::warning('Receta no encontrada para eliminar', ['id' => $id]);

            return $this->notFoundResponse('Receta no encontrada');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al eliminar receta', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->databaseErrorResponse('Error al eliminar la receta');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al eliminar receta', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->errorResponse('Error inesperado. Intenta nuevamente');
        }
    }
}
