<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Paciente::with(['datosPersonales', 'obraSocial'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'particular' => 'required|boolean',
            'numAfiliado' => 'nullable|string|max:45',
            'datosPersonales_idDatosPersonales' => 'required|exists:datosPersonales,idDatosPersonales',
            'obrasSociales_idObrasSociales' => 'required|exists:obrasSociales,idObrasSociales',
        ]);

        $paciente = Paciente::create($request->all());

        return response()->json($paciente, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {
        return $paciente->load(['datosPersonales', 'obraSocial']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paciente $paciente)
    {
        $request->validate([
            'particular' => 'boolean',
            'numAfiliado' => 'nullable|string|max:45',
            'datosPersonales_idDatosPersonales' => 'exists:datosPersonales,idDatosPersonales',
            'obrasSociales_idObrasSociales' => 'exists:obrasSociales,idObrasSociales',
        ]);

        $paciente->update($request->all());

        return response()->json($paciente, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return response()->json(null, 204);
    }
}