<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatoPersonalRequest\CreateDatoPersonalRequest;
use App\Http\Requests\DatoPersonalRequest\UpdateDatoPersonalRequest;
use App\Services\DatoPersonalService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatoPersonalController extends Controller
{
    use ApiResponse;

    protected $datoPersonalService;

    public function __construct(DatoPersonalService $datoPersonalService)
    {
        $this->datoPersonalService = $datoPersonalService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $datosPersonales = $this->datoPersonalService->getAllDatosPersonales();

        return $this->successResponse(
            'Lista de datos personales recuperada correctamente',
            ['datosPersonales' => $datosPersonales]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDatoPersonalRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $datoPersonal = $this->datoPersonalService->createDatoPersonal($request->validated());

            DB::commit();

            return $this->createdResponse('Dato personal creado exitosamente', $datoPersonal);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al crear dato personal', [
                'error' => $e->getMessage(),
                'input' => $request->validated()
            ]);

            return $this->databaseErrorResponse('Error al crear el dato personal');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al crear el dato personal', [
                'error' => $e->getMessage(),
                'input' => $request->validated()
            ]);

            return $this->errorResponse('Error inesperado. Intenta nuevamente');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $datoPersonal = $this->datoPersonalService->getDatoPersonal((int)$id);

            return $this->successResponse('Dato personal encontrado', $datoPersonal);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Dato personal no encontrado');
        } catch (\Exception $e) {
            Log::error('Error inesperado al recuperar dato personal', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->errorResponse('Error inesperado. Intenta nuevamente');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatoPersonalRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $datoPersonal = $this->datoPersonalService->getDatoPersonal((int)$id);

            $datoPersonalActualizado = $this->datoPersonalService->updateDatoPersonal($datoPersonal, $request->validated());

            DB::commit();

            return $this->successResponse('Dato personal actualizado exitosamente', $datoPersonalActualizado);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return $this->notFoundResponse('Dato personal no encontrado');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al actualizar dato personal', [
                'error' => $e->getMessage(),
                'input' => $request->validated(),
                'id' => $id
            ]);

            return $this->databaseErrorResponse('Error al actualizar el dato personal');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al actualizar dato personal', [
                'error' => $e->getMessage(),
                'input' => $request->validated(),
                'id' => $id
            ]);

            return $this->errorResponse('Error inesperado. Intenta nuevamente');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $datoPersonal = $this->datoPersonalService->getDatoPersonal((int)$id);

            $this->datoPersonalService->deleteDatoPersonal($datoPersonal);

            DB::commit();

            return $this->successResponse('Dato personal eliminado exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return $this->notFoundResponse('Dato personal no encontrado');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            Log::error('Error de base de datos al eliminar dato personal', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->databaseErrorResponse('Error al eliminar el dato personal');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error inesperado al eliminar dato personal', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return $this->errorResponse('Error inesperado. Intenta nuevamente');
        }
    }
}
