<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorarioController
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Muestra todos los horarios
        $horario=Horario::all();

        if($horario->isEmpty()){
            return $this->errorResponse('No se encontro horarios',404);
        }
        
        return $this->successResponse('Horarios encontrados',$horario,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Agregar Horario
        $validator=Validator::make($request->all(),[
            'dia'=>'required|string|max:45|in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo',
            'horaInicio'=>'required|date_format:H:i',
            'horaFin'=>'required|date_format:H:i|after:horaInicio',
            'disponible'=>'required|in:Disponible,No Disponible',
        ]);

        if($validator->fails()){
            return $this->validationErrorResponse('Error de Validacion', $validator->errors());
        }

        $horario=Horario::create($validator->validate());
        
        return $this->createdResponse('Horario creado con exito', $horario);
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        // Mostrar en especifico
        if (!$horario){
            return $this->notFoundResponse('Horario no encontrado');
        }
        return $this->successResponse('Horario encontrado',$horario,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        // Buscar en especifico
        if (!$horario){
            return $this->notFoundResponse('Horario no encontrado');
        }

        // Modificar Horario
        $validator=Validator::make($request->all(),[
            'dia'=>'nullable|string|max:45|in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo',
            'horaInicio'=>'nullable|date_format:H:i',
            'horaFin'=>'nullable|date_format:H:i|after:horaInicio',
            'disponible'=>'nullable|in:Disponible,No Disponible',
        ]);
        
        if($validator->fails()){
            return $this->validationErrorResponse('Error de Validacion', $validator->errors());
        }

        $horario->update($validator->validate());

        return $this->successResponse('Horario actualizado correctamente',$horario,200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        // (no logico)
        // Buscar en especifico
        if (!$horario){
            return $this->notFoundResponse('Horario no encontrado');
        }

        $horario->delete();

        return $this->successResponse('Horario borrado correctamente', null,200);
    }
}