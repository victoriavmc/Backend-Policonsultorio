<?php

namespace App\Http\Controllers;

use App\Models\ObraSocial;
use Illuminate\Http\Request;

class ObraSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ObraSocial::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:45',
            'estado' => 'required|string|in:Activo,Inactivo,Suspendido,ObraSocialInactiva',
        ]);

        $obraSocial = ObraSocial::create($request->all());

        return response()->json($obraSocial, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ObraSocial $obraSocial)
    {
        return $obraSocial;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ObraSocial $obraSocial)
    {
        $request->validate([
            'nombre' => 'string|max:45',
            'estado' => 'string|in:Activo,Inactivo,Suspendido,ObraSocialInactiva',
        ]);

        $obraSocial->update($request->all());

        return response()->json($obraSocial, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ObraSocial $obraSocial)
    {
        $obraSocial->delete();

        return response()->json(null, 204);
    }
}