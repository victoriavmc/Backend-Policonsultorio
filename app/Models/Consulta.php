<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    //
    protected $table = 'consultas';
    protected $primaryKey = 'idConsultas';
    protected $fillable = [
        'rellenoReceta',
        'rellenoDiagnostico',
        'rellenoOrden',
        'rellenoHistorial',
        'turnos_idTurnos',
        'diagnosticos_idDiagnosticos',
        'solicitudes_idSolicitudes',
        'seguimientoTratamiento_idSeguimientoTratamiento',
        'historialClinicos_idHistorialClinico'
    ];

    // Relaciones recibe

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turnos_idTurnos', 'idTurnos');
    }

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class, 'diagnosticos_idDiagnosticos', 'idDiagnosticos');
    }

    public function solicitud()
    {
        // return $this->belongsTo(Solicitud::class, 'solicitudes_idSolicitudes', 'idSolicitudes');
    }

    public function seguimientoTratamiento()
    {
        // return $this->belongsTo(SeguimientoTratamiento::class, 'seguimientoTratamiento_idSeguimientoTratamiento', 'idSeguimientoTratamiento');
    }

    public function historialClinico()
    {
        return $this->belongsTo(HistorialClinico::class, 'historialClinicos_idHistorialClinico', 'idHistorialClinico');
    }
}