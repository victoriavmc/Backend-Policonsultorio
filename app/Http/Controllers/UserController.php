<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select([
            'idUsuarios',
            'email',
            'rol',
            'estado',
            'datosPersonales_idDatosPersonales'
        ])
        ->with('datosPersonales')
        ->get();

        return $this->successResponse('Usuarios obtenidos exitosamente', [
            'usuarios' => $users,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::select([
            'idUsuarios',
            'email',
            'rol',
            'estado',
            'datosPersonales_idDatosPersonales'
        ])
        ->with('datosPersonales')->findOrFail($id);

            return $this->successResponse('Usuario obtenido exitosamente', $user);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Usuario no encontrado');
        } catch (\Exception $e) {
            Log::error('Error al obtener el usuario: ' . $e->getMessage());

            return $this->errorResponse('Error al obtener el usuario');
        }
    }
}
