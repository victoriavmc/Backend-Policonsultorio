<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendPinRequest;
use App\Http\Requests\Auth\VerifyPinRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $loginUser = $this->authService->login($request->validated());

        if (!$loginUser) {
            return response()->json(['message' => 'Error al autenticar el usuario'], 500);
        }

        return response()->json([
            'message' => 'Usuario logeado exitosamente',
            'data' => [
                'email' => $loginUser['email'],
                'token' => $loginUser['token'],
            ]
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        if (!$this->authService->logout($request)) {
            return response()->json(['message' => 'Error al cerrar sesion o token invalido'], 401);
        }

        return response()->json(['message' => 'Sesion cerrada exitosamente'], 200);
    }

    public function sendPin(SendPinRequest $request): JsonResponse
    {

        $validated = $request->validated();

        try {
            $result = $this->authService->requestPasswordResetPin($validated->email);

            return response()->json([
                'status' => 200,
                'message' => $result['message'],
                'data' => [
                    'expires_in_minutes' => $result['expires_in_minutes'] ?? null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 429,
                'message' => $e->getMessage()
            ], 429);
        }
    }

    public function verifyPin(VerifyPinRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $result = $this->authService->verifyPin($validated->email);

            return response()->json([
                'status' => $result['valid'] ? 200 : 400,
                'message' => $result['message'],
                'data' => [
                    'valid' => $result['valid'],
                    'expires_at' => $result['expires_at'] ?? null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $result = $this->authService->resetPasswordWithPin(
                $validated->email,
                $validated->pin,
                $validated->password,
            );

            return response()->json([
                'status' => 200,
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}