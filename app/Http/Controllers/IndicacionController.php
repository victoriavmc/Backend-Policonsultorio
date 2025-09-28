<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Models\Indicacion;
use App\Http\Requests\IndicacionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IndicacionController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $indicaciones = Indicacion::all();

            return $this->successResponse(
                'Lista de indicaciones recuperada correctamente',
                ['indicaciones' => $indicaciones]
            );
        } catch (\Exception $e) {
            Log::error('Error al obtener indicaciones', [
                'error' => $e->getMessage()
            ]);

            return $this->errorResponse('Error al obtener las indicaciones');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IndicacionRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $indicacion = Indicacion::create($request->validated());

            DB::commit();

            return $this->createdResponse('Indicación creada exitosamente', $indicacion);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al crear indicación', [
                'error' => $e->getMessage(),
                'input' => $request->validated()
            ]);

            return $this->databaseErrorResponse('Error al crear la indicación');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al crear indicación', [
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
            $indicacion = Indicacion::findOrFail($id);

            return $this->successResponse('Indicación encontrada', $indicacion);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Indicación no encontrada', ['id' => $id]);

            return $this->notFoundResponse('Indicación no encontrada');
        } catch (\Exception $e) {
            Log::error('Error al obtener indicación', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->errorResponse('Error al obtener la indicación');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IndicacionRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $indicacion = Indicacion::findOrFail($id);
            $indicacion->update($request->validated());

            DB::commit();

            return $this->successResponse('Indicación actualizada exitosamente', $indicacion->fresh());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            Log::warning('Indicación no encontrada para actualizar', ['id' => $id]);

            return $this->notFoundResponse('Indicación no encontrada');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al actualizar indicación', [
                'error' => $e->getMessage(),
                'id' => $id,
                'input' => $request->validated()
            ]);

            return $this->databaseErrorResponse('Error al actualizar la indicación');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al actualizar indicación', [
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

            $indicacion = Indicacion::findOrFail($id);
            $indicacion->delete();

            DB::commit();

            return $this->successResponse('Indicación eliminada exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            Log::warning('Indicación no encontrada para eliminar', ['id' => $id]);

            return $this->notFoundResponse('Indicación no encontrada');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al eliminar indicación', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->databaseErrorResponse('Error al eliminar la indicación');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al eliminar indicación', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->errorResponse('Error inesperado. Intenta nuevamente');
        }
    }
}
