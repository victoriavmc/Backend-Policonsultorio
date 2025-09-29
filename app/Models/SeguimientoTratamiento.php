<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientoTratamiento extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seguimientoTratamiento';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idseguimientoTratamiento';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fechaInicio',
        'fechaFin',
        'descripcion',
    ];

    /**
     * Get the consultations for the treatment follow-up.
     */
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'seguimientoTratamiento_idseguimientoTratamiento', 'idconsultas');
    }
}