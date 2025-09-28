<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Respuesta exitosa
     */
    protected function successResponse(string $message, $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => 'success'
        ], $code);
    }

    /**
     * Respuesta de error
     */
    protected function errorResponse(string $message, string $status = 'error', int $code = 500): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => null,
            'status' => $status
        ], $code);
    }

    /**
     * Respuesta de recurso creado
     */
    protected function createdResponse(string $message, $data = null): JsonResponse
    {
        return $this->successResponse($message, $data, 201);
    }

    /**
     * Respuesta de recurso no encontrado
     */
    protected function notFoundResponse(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->errorResponse($message, 'not_found', 404);
    }

    /**
     * Respuesta de error de validación
     */
    protected function validationErrorResponse(string $message, $errors = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => [
                'errors' => $errors
            ],
            'status' => 'validation_error'
        ], 422);
    }

    /**
     * Respuesta de error de base de datos
     */
    protected function databaseErrorResponse(string $message = 'Error en la base de datos'): JsonResponse
    {
        return $this->errorResponse($message, 'database_error', 500);
    }

    /**
     * Respuesta de no autorizado
     */
    protected function unauthorizedResponse(string $message = 'No autorizado'): JsonResponse
    {
        return $this->errorResponse($message, 'unauthorized', 401);
    }

    /**
     * Respuesta de prohibido
     */
    protected function forbiddenResponse(string $message = 'Acceso prohibido'): JsonResponse
    {
        return $this->errorResponse($message, 'forbidden', 403);
    }
}
