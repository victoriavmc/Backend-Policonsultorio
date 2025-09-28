<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Pacientes::with(['datosPersonales', 'obraSocial'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'particular' => 'required|boolean',
            'numAfiliado' => 'nullable|string|max:45',
            'datosPersonales_iddatosPersonales' => 'required|exists:datosPersonales,iddatosPersonales',
            'obrasSociales_idobrasSociales' => 'required|exists:obrasSociales,idobrasSociales',
        ]);

        $paciente = Pacientes::create($request->all());

        return response()->json($paciente, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pacientes $paciente)
    {
        return $paciente->load(['datosPersonales', 'obraSocial']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pacientes $paciente)
    {
        $request->validate([
            'particular' => 'boolean',
            'numAfiliado' => 'nullable|string|max:45',
            'datosPersonales_iddatosPersonales' => 'exists:datosPersonales,iddatosPersonales',
            'obrasSociales_idobrasSociales' => 'exists:obrasSociales,idobrasSociales',
        ]);

        $paciente->update($request->all());

        return response()->json($paciente, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pacientes $paciente)
    {
        $paciente->delete();

        return response()->json(null, 204);
    }
}