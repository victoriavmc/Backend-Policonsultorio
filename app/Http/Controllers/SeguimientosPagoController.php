<?php

namespace App\Http\Controllers;

use App\Models\SeguimientosPago;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeguimientosPagoController
{
    use ApiResponse;

    /**
     * Formatea texto (minúsculas, mayúsculas, trim).
     */
    private function formatString(?string $texto, bool $capitalizar = false): ?string
    {
        if (!$texto) return null;

        // Elimina espacios extra y convierte todo a minúsculas
        $texto = trim(strtolower($texto));

        // Si se desea capitalizar tipo título (palabras con mayúscula)
        if ($capitalizar) {
            return implode(' ', array_map('ucfirst', explode(' ', $texto)));
        }

        // Si hay puntos, capitaliza cada oración
        $oraciones = array_map('trim', explode('.', $texto));
        $oracionesFormateadas = array_map(function ($oracion) {
            return ucfirst($oracion);
        }, $oraciones);

        // Une nuevamente con puntos y espacio
        return implode('. ', $oracionesFormateadas);
    }


    /**
     * Valida los datos de entrada.
     */
    private function validar(Request $request, bool $isUpdate = false)
    {
        $data = $request->all();

        // Normalización de texto
        if (isset($data['estado'])) {
            $data['estado'] = $this->formatString($data['estado'], true);
        }

        if (isset($data['modoPago'])) {
            $data['modoPago'] = $this->formatString($data['modoPago'], true);
        }

        if (isset($data['observacion'])) {
            $data['observacion'] = $this->formatString($data['observacion']);
        }

        $rules = [
            'estado'       => ($isUpdate ? 'nullable' : 'required') . '|in:Pendiente,Finalizado',
            'fecha'        => ($isUpdate ? 'nullable' : 'required') . '|date_format:Y-m-d',
            'modoPago'     => ($isUpdate ? 'nullable' : 'required') . '|string|max:45',
            'observacion'  => 'nullable|string|max:255',
            'montoParcial' => 'nullable|numeric|min:0',
            'montoFinal'   => ($isUpdate ? 'nullable' : 'required') . '|numeric|min:0',
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Mostrar todos los seguimientos de pagos.
     */
    public function index()
    {
        $pagos = SeguimientosPago::all();

        if ($pagos->isEmpty()) {
            return $this->errorResponse('No se encontraron seguimientos de pagos', 404);
        }

        return $this->successResponse('Seguimientos de pagos encontrados', $pagos, 200);
    }

    /**
     * Crear un nuevo seguimiento de pago.
     */
    public function store(Request $request)
    {
        $validator = $this->validar($request);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $pago = SeguimientosPago::create($validator->validate());

        return $this->createdResponse('Seguimiento de pago creado con éxito', $pago);
    }

    /**
     * Mostrar un seguimiento de pago específico.
     */
    public function show(SeguimientosPago $seguimientoPago)
    {
        if (!$seguimientoPago) {
            return $this->notFoundResponse('Seguimiento de pago no encontrado');
        }

        return $this->successResponse('Seguimiento de pago encontrado', $seguimientoPago, 200);
    }

    /**
     * Actualizar un seguimiento de pago.
     */
    public function update(Request $request, SeguimientosPago $seguimientoPago)
    {
        if (!$seguimientoPago) {
            return $this->notFoundResponse('Seguimiento de pago no encontrado');
        }

        $validator = $this->validar($request, true);

        if ($validator->fails()) {
            return $this->validationErrorResponse('Error de validación', $validator->errors());
        }

        $seguimientoPago->update($validator->validate());

        return $this->successResponse('Seguimiento de pago actualizado correctamente', $seguimientoPago, 200);
    }

    /**
     * Eliminar (borrado lógico o físico) un seguimiento de pago.
     */
    public function destroy(SeguimientosPago $seguimientoPago)
    {
        if (!$seguimientoPago) {
            return $this->notFoundResponse('Seguimiento de pago no encontrado');
        }

        $seguimientoPago->delete();

        return $this->successResponse('Seguimiento de pago eliminado correctamente', null, 200);
    }
}