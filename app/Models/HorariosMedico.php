<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorariosMedico extends Model
{
    //
    protected $table='horariosMedicos';
    protected $primaryKey='idHorariosMedicos';
    protected $fillable = ['disponible','medicos_idMedicos', 'horarios_idHorarios'];

    // Recibe de Medicos y Usuarios
    public function medicos(){
        return $this->belongsTo(Medico::class, 'medicos_idMedicos', 'idMedicos');
    }

    public function horario(){
        return $this->belongsTo(Horario::class, 'horarios_idHorarios', 'idHorarios');
    }
}