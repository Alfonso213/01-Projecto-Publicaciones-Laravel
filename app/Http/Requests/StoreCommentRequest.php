<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Reglas de validación aplicadas.
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:2000',
            'post_id' => 'required|exists:posts,id',
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'El comentario no puede estar vacío.',
            'post_id.exists' => 'No puedes comentar en una publicación que no existe.',
        ];
    }
}
