<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlayerRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'known_as' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'img' => 'required|url',
            'prime_season' => 'required|string|max:20',
            'prime_position' => 'required|string|max:255',
            'preferred_foot' => 'required|string|in:left,right,both',
            'spd' => 'required|integer|min:0|max:100',
            'sho' => 'required|integer|min:0|max:100',
            'pas' => 'required|integer|min:0|max:100',
            'dri' => 'required|integer|min:0|max:100',
            'def' => 'required|integer|min:0|max:100',
            'phy' => 'required|integer|min:0|max:100',
            'prime_rating' => 'required|integer|min:0|max:100',
            'club_id' => 'required|integer|exists:clubs,id',
            'country_id' => 'required|integer|exists:countries,id',
        ];
    }

    public function messages(): array
    {
        return [
            'known_as.required' => 'El nombre conocido es obligatorio',
            'full_name.required' => 'El nombre completo es obligatorio',
            // Personaliza los que quieras
        ];
    }
}
