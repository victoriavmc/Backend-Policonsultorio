<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialClinico extends Model
{
    //
    protected $table = 'historialClinicos';
    protected $primaryKey = 'idHistorialClinico';
    protected $fillable = [
        'descripcion',
        'causaConsulta',
        'fecha',
        'contenidoFormulario'
    ];

    // Envia a consultas
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'historialClinicos_idHistorialClinico', 'idHistorialClinico');
    }
}