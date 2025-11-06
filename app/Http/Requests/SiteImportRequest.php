<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Orcamento;

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
            'sites_sheet' => 'required|mimes:xlsx',
            'orcamento_id' => 'nullable|exists:orcamentos,id',
            'novo_orcamento' => 'nullable|array',
            'novo_orcamento.titulo' => 'nullable',
            'novo_orcamento.cliente_id' => 'nullable|exists:clientes,id',
            'novo_orcamento.status' => ['nullable', Rule::in(Orcamento::getStatus())],
            'novo_orcamento.imposto' => 'nullable',
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

    /**
     * Get the messages array.
     *
     */
    public function attributes(): array
    {
        return [
            'sites_sheet' => 'arquivo',
        ];
    }
}
