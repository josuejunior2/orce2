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
            'nome' => 'required|max:60|min:3',
            'email' => 'required|email',
            'telefone' => ['required'], // 'regex:/\(\d{2}\) \d{5}-\d{4}/'],
            'cidades' => 'nullable',
            'cnpj' => ['required'], //, 'regex:/^[0-9]+(\.[0-9]+){0,2}\/[0-9]+-[0-9]+$/'],
            'cidades' => 'nullable',
            'representante' => 'required|min:3|max:60',
            'telefone.regex' => 'O campo telefone deve ter 2 parênteses e 1 hífen.',
            //
        ];
    }
    /**
     * Get the messages array.
     *
     */
    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute deve ser preenchido.',
            'nome.required' => 'O campo nome deve ser preenchido',
            'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres.',
            'nome.max' => 'O campo nome deve ter no máximo 60 caracteres.',
            'representante.min' => 'O campo representante deve ter no mínimo 10 caracteres.',
            'representante.max' => 'O campo representante deve ter no máximo 60 caracteres.',
            'email.email' => 'O campo email deve ser preenchido com um endereço de email.',
            'telefone.regex' => 'O campo telefone deve ter 2 parênteses e 1 hífen.',
            'cnpj.regex' => 'O campo CNPJ deve ter 14 caracteres numéricos, 2 pontos, 1 barra e 1 hífen.',
        ];
    }
    // public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     dd($validator->errors());
    // }
}
