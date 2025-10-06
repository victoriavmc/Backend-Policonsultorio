<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();

        return response()->json([
            'message' => 'Usuarios obtenidos exitosamente',
            'data' => [
                'user' => $user,
                'datosPersonales' => $user->datosPersonales
            ],
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::with('datosPersonales')->findOrFail($id);

            return response()->json([
                'message' => 'Usuario obtenido exitosamente',
                'data' => [
                    'user' => $user,
                    'datosPersonales' => $user->datosPersonales
                ],
                'status' => 'success'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Usuario no encontrado');
        } catch (\Exception $e) {
            Log::error('Error al obtener el usuario: ' . $e->getMessage());

            return $this->errorResponse('Error al obtener el usuario');
        }
    }
}
