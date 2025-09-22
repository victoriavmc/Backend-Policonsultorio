<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auditorias extends Model
{
    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'auditorias';

    /**
     * La clave primaria asociada con la tabla.
     */
    protected $primaryKey = 'idAuditorias';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'modulo',
        'accion',
        'valorAnterior',
        'valorNuevo',
        'fecha',
        'idReferente',
        'usuarios_idUsuarios',
    ];

    /**
     * Los atributos que deberían ser convertidos.
     */
    protected $casts = [
        'fecha' => 'date',
        'idReferente' => 'integer',
        'usuarios_idUsuarios' => 'integer',
    ];

    /**
     * Relación: Una auditoría pertenece a un usuario.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuarios_idUsuarios', 'idUsuarios');
    }
}
