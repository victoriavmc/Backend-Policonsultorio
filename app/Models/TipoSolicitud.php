<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSolicitud extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tiposSolicitudes';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idTiposSolicitudes';

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
        'tipoSolicitud',
        'nombre',
    ];

    // ENVIA A SOLICITUDES
}