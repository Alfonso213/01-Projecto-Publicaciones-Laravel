<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        return true; // Solo usuarios autenticados (manejado por middleware auth)
    }

    /**
     * Reglas de validación que se aplicarán a la petición.
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:5000',
        ];
    }
    
    /**
     * Mensajes de error personalizados (opcional).
     */
    public function messages(): array
    {
        return [
            'body.required' => 'El contenido de la publicación no puede estar vacío.',
            'body.max' => 'La publicación es demasiado larga (máximo 5000 caracteres).',
        ];
    }
}
