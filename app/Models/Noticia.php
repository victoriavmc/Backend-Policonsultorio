<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    //
    protected $table = 'noticias';
    protected $primaryKey = 'idNoticias';
    protected $fillable = ['titulo','descripcion','fecha','imagen','url'];

    // Envia a AgrupacionNoticiasImagenes
    public function agrupacionNoticiasImagen(){
       return $this->hasMany(AgrupacionNoticiaImagen::class, 'noticias_idNoticias', 'idNoticias');
    }

    public function imagenes()
    {
        return $this->belongsToMany(
            Imagen::class,
            'agrupacionNoticiasImagenes',
            'noticias_idNoticias',
            'imagenes_idImagenes',
            'idNoticias',
            'idImagenes'
        );
    }
}