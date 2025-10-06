<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObraSocial extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'obrasSociales';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idObrasSociales';

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
        'nombre',
        'estado',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'estado' => 'string',
    ];

    // ENVIA A PACINETES
    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'obrasSociales_idObrasSociales', 'idObrasSociales');
    }
}