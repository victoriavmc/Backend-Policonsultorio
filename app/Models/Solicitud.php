<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'solicitudes';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idSolicitudes';

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
        'descripcion',
        'fecha',
        'tiposSolicitudes_idTiposSolicitudes',
    ];

    /**
     * Get the consultations for the request.
     */
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'solicitudes_idSolicitudes', 'idConsultas');
    }

    // RECIBE DE TIPOSSOLICITUDES
    public function tiposSolicitudes()
    {
        return $this->belongsTo(TipoSolicitud::class, 'tiposSolicitudes_idTiposSolicitudes', 'idTiposSolicitudes');
    }
}