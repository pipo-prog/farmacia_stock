<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMovimientoStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_producto' => ['required', 'integer', 'exists:producto,id_producto'],
            'tipo' => ['required', 'string', 'in:ENTRADA,SALIDA'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_producto.required' => 'El producto es obligatorio.',
            'id_producto.exists' => 'El producto especificado no existe.',
            'tipo.required' => 'El tipo de movimiento es obligatorio.',
            'tipo.in' => 'El tipo de movimiento debe ser ENTRADA o SALIDA.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1 unidad.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'codigo' => 422,
            'mensaje' => 'Error de validación en el movimiento de stock',
            'errores' => $validator->errors(),
        ], 422));
    }
}
