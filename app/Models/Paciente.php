<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pacientes';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idpacientes';

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
        'particular',
        'numAfiliado',
        'datosPersonales_iddatosPersonales',
        'obrasSociales_idobrasSociales',
    ];

    /**
     * Get the personal data associated with the patient.
     */
    public function datosPersonale()
    {
        return $this->belongsTo(DatoPersonal::class, 'datosPersonales_iddatosPersonales');
    }

    /**
     * Get the social work associated with the patient.
     */
    public function obraSocial()
    {
        return $this->belongsTo(ObraSocial::class, 'obrasSociales_idobrasSociales');
    }

    /**
     * Get turnos associated with the patient.
     */
    public function turnos()
    {
        return $this->hasMany(Turno::class, 'pacientes_idPacientes', 'idpacientes');
    }
}