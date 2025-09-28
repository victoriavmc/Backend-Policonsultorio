<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditoriaRequest;
use App\Models\Auditoria;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuditoriasController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $auditorias = Auditoria::all();

        return $this->successResponse(
            'Lista de auditorías recuperada correctamente',
            ['auditorias' => $auditorias]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuditoriaRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $auditoria = Auditoria::create($request->validated());

            DB::commit();

            return $this->createdResponse('Auditoría creada exitosamente', $auditoria);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al crear auditoría', [
                'error' => $e->getMessage(),
                'input' => $request->validated()
            ]);

            return $this->databaseErrorResponse('Error al crear la auditoría');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al crear auditoría', [
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
            $auditoria = Auditoria::findOrFail($id);

            return $this->successResponse('Auditoría encontrada', $auditoria);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Auditoría no encontrada', ['id' => $id]);

            return $this->notFoundResponse('Auditoría no encontrada');
        } catch (\Exception $e) {
            Log::error('Error al obtener auditoría', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->errorResponse('Error al obtener la auditoría');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AuditoriaRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $auditoria = Auditoria::findOrFail($id);
            $auditoria->update($request->validated());

            DB::commit();

            return $this->successResponse('Auditoría actualizada exitosamente', $auditoria->fresh());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            Log::warning('Auditoría no encontrada para actualizar', ['id' => $id]);

            return $this->notFoundResponse('Auditoría no encontrada');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al actualizar auditoría', [
                'error' => $e->getMessage(),
                'id' => $id,
                'input' => $request->validated()
            ]);

            return $this->databaseErrorResponse('Error al actualizar la auditoría');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al actualizar auditoría', [
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

            $auditoria = Auditoria::findOrFail($id);
            $auditoria->delete();

            DB::commit();

            return $this->successResponse('Auditoría eliminada exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            Log::warning('Auditoría no encontrada para eliminar', ['id' => $id]);

            return $this->notFoundResponse('Auditoría no encontrada');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al eliminar auditoría', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->databaseErrorResponse('Error al eliminar la auditoría');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al eliminar auditoría', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->errorResponse('Error inesperado. Intenta nuevamente');
        }
    }
}
