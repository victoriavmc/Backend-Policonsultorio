<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorarioController
{
    use ApiResponse;

    // Formateo para que los dias puedan ingresar en minuscula y luego quede Asi. (tambien si se encuentra disponible o no)
    private function formatString(?string $pTexto, $dato=false):?string
    {
        if (!$pTexto) return null;
        if($dato){
            // Convierte a minúsculas, quita espacios, y capitaliza cada palabra
            return implode(' ', array_map('ucfirst', explode(' ', strtolower(trim($pTexto)))));
        }
        return ucfirst(strtolower(trim($pTexto)));
    }

    private function validaciones($request, $isUpdate=false){
        $data=$request->all();


        // Pongo en mayuscula minuscula los textos.
        if(isset($data['dia'])){
            $data['dia']= $this->formatString( $data['dia']);
        }

        // Pongo en mayuscula minuscula los textos.
        if(isset($data['disponible'])){
            $data['disponible']= $this->formatString( $data['disponible'], true);
        }

        // Defino las reglas con iternario
        $reglas = [
            'dia'=> ($isUpdate ? 'nullable' : 'required'). '|string|max:45|in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo',
            'horaInicio'=>($isUpdate ? 'nullable' : 'required').'|date_format:H:i',
            'horaFin'=>($isUpdate ? 'nullable' : 'required').'|date_format:H:i|after:horaInicio',
            'disponible'=> ($isUpdate ? 'nullable' : 'required'). '|in:Disponible,No Disponible',
        ];

        return Validator::make($data, $reglas);
    }

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
        $validator = $this->validaciones($request);

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
        $validator = $this->validaciones($request, true);
        
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