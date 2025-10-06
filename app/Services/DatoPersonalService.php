<?php

namespace App\Services;

use App\Models\DatoPersonal;

class DatoPersonalService
{
    /**
     * Recupera todos los datos personales activos.
     */
    public function getAllDatosPersonales()
    {
        return DatoPersonal::where('estado', 'Activo')->get();
    }

    /**
     * Crea un nuevo dato personal.
     * 
     * @param array $data Datos para crear el dato personal.
     * @return DatoPersonal EL dato personal creado.
     */
    public function createDatoPersonal(array $data): DatoPersonal
    {
        return DatoPersonal::create($data);
    }

    /**
     * Recupera un dato personal por su ID.
     */
    public function getDatoPersonal(int $id)
    {
        return DatoPersonal::findOrFail($id);
    }

    /**
     * Actualiza un dato personal existente.
     */
    public function updateDatoPersonal(DatoPersonal $datoPersonal, array $data)
    {
        $datoPersonal->update($data);
        return $datoPersonal->fresh();
    }

    /**
     * Elimina un dato personal (soft delete).
     */
    public function deleteDatoPersonal(DatoPersonal $datoPersonal)
    {
        $datoPersonal->estado = 'Inactivo';
        $datoPersonal->save();
    }
}
