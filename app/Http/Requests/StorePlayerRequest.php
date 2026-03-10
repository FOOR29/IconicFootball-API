<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;  // Cambiar según tu lógica de autorización
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
            'prime_season' => 'required|string|max:255',
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

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'known_as.required' => 'The known as is required',
            'full_name.required' => 'The full name is required',
            'img.required' => 'The image is required',
            'img.url' => 'The image must be a valid URL',
            'preferred_foot.in' => 'The preferred foot must be "left", "right", or "both"',
            'spd.max' => 'The speed must be between 0 and 100',
            // Agrega más mensajes personalizados si quieres
        ];
    }
}
