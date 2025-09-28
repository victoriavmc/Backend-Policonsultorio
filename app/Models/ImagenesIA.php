<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenesIA extends Model
{
    //
    protected $table = 'imagenesIA';
    protected $primaryKey = 'idImagenesIA';
    protected $fillable = ['descripcionIA','observacionMedica'];

    // Envia a observacion
    public function observaciones() {
        return $this->hasMany(ObservacionesIA::class, 'imagenesIA_idImagenesIA', 'idImagenesIA');
    }
}