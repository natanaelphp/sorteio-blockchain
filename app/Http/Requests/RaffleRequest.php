<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class RaffleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'participants' => ['required', 'array', 'min:2'],
            'participants.*' => ['required', 'string', 'max:120', 'distinct'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Informe o título do sorteio.',
            'participants.required' => 'Informe os participantes do sorteio.',
            'participants.min' => 'Informe pelo menos dois participantes.',
            'participants.*.required' => 'Informe o nome de todos os participantes.',
            'participants.*.max' => 'O nome de cada participante deve ter no máximo 120 caracteres.',
            'participants.*.distinct' => 'A lista possui participantes repetidos.',
        ];
    }
}
