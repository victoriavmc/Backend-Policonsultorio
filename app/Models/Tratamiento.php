<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    //
    protected $table = 'tratamientos';
    protected $primaryKey = 'idTratamientos';
    protected $fillable = ['nombre','descripcion','tiempoEstimado'];

    // Envia id a turno
    public function Turno(){
        return $this->hasMany(Turno::class, 'tratamientos_idTratamientos', 'idTratamientos');
    }
}