<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    //
    protected $table = 'diagnosticos';
    protected $primaryKey = 'idDiagnosticos';
    protected $fillable = ['fecha','tipo','descripcion','observacion','clasificacion','contenidoFormulario','observacionesIA_idObservacionesIA','recetas_idRecetas'];

     // Relación con ObservacionIA
    public function observacionIA()
    {
        return $this->belongsTo(
            ObservacionesIA::class,
            'observacionesIA_idObservacionesIA',
            'idObservacionesIA'
        );
    }

    // Relación con Receta
    public function receta()
    {
        return $this->belongsTo(
            Receta::class,
            'recetas_idRecetas',
            'idRecetas'
        );
    }

    // Relación con Consultas
    public function consultas()
    {
        return $this->hasMany(
            Consulta::class,
            'diagnosticos_idDiagnosticos',
            'idDiagnosticos'
        );
    }
}