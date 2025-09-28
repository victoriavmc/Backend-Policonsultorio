<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientosPago extends Model
{
    //
    protected $table = 'seguimientosPagos';
    protected $primaryKey = 'idSeguimientosPagos';
    protected $fillable = ['estado','fecha','montoParcial', 'montoFinal','observacion','modoPago'];

    // Envia id a turno
    public function Turno(){
        return $this->hasMany(Turno::class, 'seguimientosPagos_idSeguimientosPagos', 'idSeguimientosPagos');
    }

}