<?php

namespace App\Http\Controllers;

use App\Models\FormulariosPDF;
use Illuminate\Http\Request;

class FormulariosPDFController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FormulariosPDF::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'nullable|string|max:45',
            'nombre' => 'nullable|string|max:45',
            'formulario' => 'nullable|string|max:255',
        ]);

        $formularioPDF = FormulariosPDF::create($request->all());

        return response()->json($formularioPDF, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FormulariosPDF $formularioPDF)
    {
        return $formularioPDF;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormulariosPDF $formularioPDF)
    {
        $request->validate([
            'tipo' => 'nullable|string|max:45',
            'nombre' => 'nullable|string|max:45',
            'formulario' => 'nullable|string|max:255',
        ]);

        $formularioPDF->update($request->all());

        return response()->json($formularioPDF, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormulariosPDF $formularioPDF)
    {
        $formularioPDF->delete();

        return response()->json(null, 204);
    }
}