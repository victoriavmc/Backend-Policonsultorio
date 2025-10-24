<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObservacionesIA extends Model
{
    //
    protected $table = 'observacionesIA';
    protected $primaryKey = 'idObservacionesIA';

    // Recibe imagenes e imagenes ia
    public function imagenes(){
        return $this->belongsTo(Imagen::class, 'imagenes_idImagenes', 'idImagenes');
    }

    public function imagenesIA(){
        return $this->belongsTo(ImagenesIA::class, 'imagenesIA_idImagenesIA', 'idImagenesIA');
    }

    // Envia a diagnostico
    public function diagnosticos() {
        return $this->hasMany(Diagnostico::class, 'observacionesIA_idObservacionesIA', 'idObservacionesIA');
    }

}