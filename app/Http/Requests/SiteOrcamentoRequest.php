<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Fornecedor;
use Illuminate\Validation\Rule;

class SiteOrcamentoRequest extends FormRequest
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
            'orcamento_id' => 'required',
            'cidade_id' => 'required',
            'nome' => 'required',
            'endereco' => 'nullable',
            'latitude' => ['nullable', 'regex:/^-?\d+(?:\.\d+)?$/'],
            'longitude' => ['nullable', 'regex:/^-?\d+(?:\.\d+)?$/'],
            'vel_solicitada_down' => ['nullable', 'numeric'],
            'vel_solicitada_up' => ['nullable', 'numeric'],
            'servicos' => ['nullable', 'exists:servicos,id'],
            'barra' => ['nullable'],
            'site_id' => ['nullable', 'exists:sites,id'],
            
            // 'pontas' => ['nullable', 'array'],
            'pontas.*.nome' => ['required'],
            'pontas.*.latitude' => ['nullable', 'regex:/^-?\d+(?:\.\d+)?$/'],
            'pontas.*.longitude' => ['nullable', 'regex:/^-?\d+(?:\.\d+)?$/'],
            'pontas.*.cidade_id' => ['required'],
            'pontas.*.endereco' => ['nullable', 'string'],
            'pontas.*.vel_solicitada_up' => ['nullable', 'numeric'],
            'pontas.*.vel_solicitada_down' => ['nullable', 'numeric'],
            'pontas.*.barra' => ['nullable'],
            'pontas.*.servicos' => ['nullable', 'exists:servicos,id'],
        ];
    }
}
