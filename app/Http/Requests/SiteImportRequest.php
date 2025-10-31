<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(auth()->guard('admin')->check()){ // + alguma verificação?
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'colunas' => 'array|required',
            'colunas.*' => 'required',
            'sites_sheet' => 'required|mimes:xlsx'
        ];
    }

    /**
     * Get the messages array.
     *
     */
    public function messages(): array
    {
        return [
            'sites_sheet.mimes' => 'O arquivo deve ter a extensão .xlsx.',
        ];
    }
}
