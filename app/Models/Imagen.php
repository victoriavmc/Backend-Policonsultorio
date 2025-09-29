<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Imagen extends Model
{
    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'imagenes';

    /**
     * La clave primaria asociada con la tabla.
     */
    protected $primaryKey = 'idImagenes';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'imagen',
    ];

    /**
     * Relacion uno a muchos con agrupacionNoticiasImagen
     */
    public function agrupacionNoticiasImagen(): HasMany
    {
        return $this->hasMany(AgrupacionNoticiaImagen::class, 'imagenes_idImagenes', 'idImagenes');
    }

    /**
     * Relación uno a muchos con observacionIA
     */
    public function observacionesIA(): HasMany
    {
        return $this->hasMany(ObservacionesIA::class, 'imagenes_idImagenes', 'idImagenes');
    }
}