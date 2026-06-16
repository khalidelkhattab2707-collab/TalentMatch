<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOffreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'competences_requises' => ['required', 'array', 'min:1'],
            'competences_requises.*' => ['string', 'max:100'],
            'experience_minimum' => ['required', 'integer', 'min:0', 'max:30'],
        ];
    }
}
