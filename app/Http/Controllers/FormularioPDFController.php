<?php

namespace App\Http\Controllers;

use App\Models\FormularioPDF;
use Illuminate\Http\Request;

class FormularioPDFController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FormularioPDF::all();
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

        $formularioPDF = FormularioPDF::create($request->all());

        return response()->json($formularioPDF, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FormularioPDF $formularioPDF)
    {
        return $formularioPDF;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormularioPDF $formularioPDF)
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
    public function destroy(FormularioPDF $formularioPDF)
    {
        $formularioPDF->delete();

        return response()->json(null, 204);
    }
}