<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    //
    protected $table='medicos';
    protected $primaryKey='idMedicos';
    protected $fillable = ['matricula','especialidad','consultorio','firmaVirtual', 'usuarios_idUsuarios'];

    // Recibe de usuario
    public function usuario(){
        return $this->belongsTo(User::class, 'usuarios_idUsuarios', 'idUsuarios');
    }

    // Envia a turnos y horariosmedicos
    public function turnos(){
        return $this->hasMany(Turno::class, 'medicos_idMedicos', 'idMedicos');
    }

    public function HorariosMedico(){
        return $this->hasMany(HorariosMedico::class, 'medicos_idMedicos', 'idMedicos');
    }
}