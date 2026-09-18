<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigo' => [
                'required',
                'string',
                'max:30',
                'unique:producto,codigo',
            ],
            'nombre' => [
                'required',
                'string',
                'max:150',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
            'precio' => [
                'required',
                'numeric',
                'min:0',
            ],
            'stock_actual' => [
                'required',
                'integer',
                'min:0',
            ],
            'stock_minimo' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'fecha_vencimiento' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'id_categoria' => [
                'required',
                'integer',
                'exists:categoria,id_categoria',
            ],
            'id_proveedor' => [
                'required',
                'integer',
                'exists:proveedor,id_proveedor',
            ],
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     */
    public function messages(): array
    {
        return [
            'codigo.required' => 'El código del producto es obligatorio.',
            'codigo.string' => 'El código del producto debe ser una cadena de texto.',
            'codigo.max' => 'El código del producto no puede exceder los 30 caracteres.',
            'codigo.unique' => 'El código de producto ya se encuentra registrado en el sistema.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.string' => 'El nombre del producto debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del producto no puede exceder los 150 caracteres.',
            'descripcion.max' => 'La descripción no puede exceder los 255 caracteres.',
            'precio.required' => 'El precio del producto es obligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico válido.',
            'precio.min' => 'El precio no puede ser inferior a 0.',
            'stock_actual.required' => 'El stock actual es obligatorio.',
            'stock_actual.integer' => 'El stock actual debe ser un número entero.',
            'stock_actual.min' => 'El stock actual no puede ser inferior a 0.',
            'stock_minimo.integer' => 'El stock mínimo debe ser un número entero.',
            'stock_minimo.min' => 'El stock mínimo no puede ser inferior a 0.',
            'fecha_vencimiento.date_format' => 'La fecha de vencimiento debe tener el formato YYYY-MM-DD.',
            'id_categoria.required' => 'La categoría del producto es obligatoria.',
            'id_categoria.integer' => 'El identificador de categoría debe ser un número entero.',
            'id_categoria.exists' => 'La categoría seleccionada no existe en la base de datos.',
            'id_proveedor.required' => 'El proveedor del producto es obligatorio.',
            'id_proveedor.integer' => 'El identificador de proveedor debe ser un número entero.',
            'id_proveedor.exists' => 'El proveedor seleccionado no existe en la base de datos.',
        ];
    }

    /**
     * Respuesta JSON estructurada ante fallas de validación.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'codigo' => 422,
            'mensaje' => 'Error de validación en los datos proporcionados',
            'errores' => $validator->errors(),
        ], 422));
    }
}
