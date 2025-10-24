<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Receta extends Model
{
    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'recetas';

    /**
     * La clave primaria asociada con la tabla.
     */
    protected $primaryKey = 'idRecetas';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombreMedicamente',
        'presentacion',
        'concentracion',
        'cantidad',
        'fecha',
        'vigencia',
        'codigoRecetaElectronica',
        'observacion',
        'indicaciones_idIndicaciones'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'codigoRecetaElectronica',
    ];

    /**
     * Relacion con indicaciones
     */
    public function indicaciones(): BelongsTo
    {
        return $this->belongsTo(Indicacion::class, 'indicaciones_idIndicaciones', 'idIndicaciones');
    }

    /**
     * Relacion con diagnosticos
     */
    public function diagnosticos(): HasOne
    {
        return $this->hasOne(Indicacion::class, 'recetas_idRecetas', 'idRecetas');
    }

}