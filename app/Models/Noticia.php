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
    public function AgrupacionNoticiasImagen(){
       return $this->hasMany(AgrupacionNoticiaImagen::class, 'noticias_idNoticias', 'idNoticias');
    }
}