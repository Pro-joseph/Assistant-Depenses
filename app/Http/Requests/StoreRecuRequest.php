<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRecuRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'texte_brut' => ['nullable', 'string', 'max:10000', 'required_without:image'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240', 'required_without:texte_brut'],
        ];
    }

    public function messages(): array
    {
        return [
            'texte_brut.required_without' => 'Le texte brut est obligatoire si aucune image n\'est fournie.',
            'image.required_without' => 'L\'image est obligatoire si aucun texte brut n\'est fourni.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format JPG, PNG ou WebP.',
            'image.max' => 'L\'image ne doit pas dépasser 10 Mo.',
            'texte_brut.max' => 'Le texte brut ne doit pas dépasser 10 000 caractères.',
        ];
    }
}
