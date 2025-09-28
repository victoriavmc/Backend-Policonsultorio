<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    //
    protected $table='horarios';
    protected $primaryKey='idHorarios';
    protected $fillable = ['dia','horaInicio','horaFin','estado'];

    // Envia a HorariosMedicos
    public function HorariosMedico(){
        return $this->hasMany(HorariosMedico::class, 'horarios_idHorarios', 'idHorarios');
    }

}