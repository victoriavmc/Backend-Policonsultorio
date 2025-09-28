<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DatoPersonal extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'datos_personales';

    /**
     * La clave primaria asociada con la tabla.
     */
    protected $primaryKey = 'idDatosPersonales';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'tipoDocumento',
        'telefono',
        'direccion',
        'fechaNacimiento',
        'genero',
    ];

    /**
     * Los atributos que deberían ser convertidos.
     */
    protected $casts = [
        'fechaNacimiento' => 'date',
        'documento' => 'string',
        'telefono' => 'string',
    ];

    /**
     * Relación: Datos personales tiene un usuario.
     */
    public function usuarios(): HasOne
    {
        return $this->hasOne(User::class, 'datosPersonales_idDatosPersonales', 'idDatosPersonales');
    }
     /**
     * Relación: Datos personales tiene un paciente.
     */
    public function pacientes(): HasOne
    {
        return $this->hasOne(Paciente::class, 'datosPersonales_iddatosPersonales', 'idDatosPersonales');
    }
}
