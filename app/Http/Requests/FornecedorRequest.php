<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FornecedorRequest extends FormRequest
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
            'nome' => 'required',
            'email' => 'required|email',
            'telefone' => ['required'], // 'regex:/\(\d{2}\) \d{5}-\d{4}/'],
            'cidades' => 'nullable',
            'cnpj' => ['required'], //, 'regex:/^[0-9]+(\.[0-9]+){0,2}\/[0-9]+-[0-9]+$/'],
            'representante' => 'required',
            //
        ];
    }
    // public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     dd($validator->errors());
    // }
}
