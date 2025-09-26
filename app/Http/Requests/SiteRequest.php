<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest
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
            'nome' => 'required',
            'cidade_id' => 'required',
            'endereco' => 'nullable',
            'latitude' => ['nullable', 'regex:/^-?\d+(?:\.\d+)?$/'],
            'longitude' => ['nullable', 'regex:/^-?\d+(?:\.\d+)?$/'],
            'id_instalacao' => 'nullable',
            'is_subestacao' => 'nullable',
        ];
    }
}
