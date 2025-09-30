<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgrupacionNoticiaImagen extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agrupacionNoticiasImagenes';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idAgrupacionNoticiasImagenes';

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
        'noticias_idNoticias',
        'imagenes_idImagenes',
    ];

    // RECIBE
    public function noticias()
    {
        return $this->belongsTo(Noticia::class, 'noticias_idNoticias', 'idNoticias');
    }

    public function imagenes()
    {
        return $this->belongsTo(Imagen::class, 'imagenes_idImagenes', 'idImagenes');
    }
}