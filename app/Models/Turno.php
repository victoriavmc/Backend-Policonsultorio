<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $table = 'turnos';
    protected $primaryKey = 'idTurnos';
    protected $fillable = [
        'fecha',
        'hora',
        'particular',
        'nombreObraSocial',
        'prioridad',
        'estado',
        'duracion',
        'horaLlegada',
        'horaSalida',
        'pacientes_idPacientes',
        'medicos_idMedicos',
        'tratamientos_idTratamientos',
        'seguimientosPagos_idSeguimientosPagos'
    ];

    // Relaciones
    public function paciente() {
        return $this->belongsTo(Paciente::class, 'pacientes_idPacientes', 'idPacientes');
    }

    public function medico() {
        return $this->belongsTo(Medico::class, 'medicos_idMedicos', 'idMedicos');
    }

    public function tratamiento() {
        return $this->belongsTo(Tratamiento::class, 'tratamientos_idTratamientos', 'idTratamientos');
    }

    public function seguimientoPago() {
        return $this->belongsTo(SeguimientoPago::class, 'seguimientosPagos_idSeguimientosPagos', 'idSeguimientosPagos');
    }
}