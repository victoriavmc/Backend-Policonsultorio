<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Indicaciones extends Model
{
        /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'indicaciones';

    /**
     * La clave primaria asociada con la tabla.
     */
    protected $primaryKey = 'idIndicaciones';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'posologia',
        'indicacionEspecial',
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
    public function recetas(): HasMany
    {
        return $this->hasMany(Recetas::class, 'indicaciones_idIndicaciones', 'idIndicaciones');
    }
}
