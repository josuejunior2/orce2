<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Fornecedor;
use Illuminate\Validation\Rule;

class SiteOrcamentoFromTableRequest extends FormRequest
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
            'id_instalacao' => 'nullable',
            'latitude' => ['nullable', 'regex:/^-?\d+(?:\.\d+)?$/'],
            'longitude' => ['nullable', 'regex:/^-?\d+(?:\.\d+)?$/'],
            'vel_solicitada_down' => ['nullable', 'numeric'],
            'vel_solicitada_up' => ['nullable', 'numeric'],
            'servicos' => ['nullable', 'array'],
            'barra' => ['nullable'],
            'site_id' => ['nullable', 'exists:sites,id'],
            'is_subestacao' => 'nullable',
            
            // 'pontas' => ['nullable', 'array'],
            'pontas.*.site_id' => ['nullable'],
            'pontas.*.site_orcamento_id' => ['nullable'],
            'pontas.*.nome' => ['required'],
            'pontas.*.latitude' => ['nullable', 'regex:/^-?\d+(?:\.\d+)?$/'],
            'pontas.*.longitude' => ['nullable', 'regex:/^-?\d+(?:\.\d+)?$/'],
            'pontas.*.cidade_id' => ['required'],
            'pontas.*.endereco' => ['nullable', 'string'],
            'pontas.*.vel_solicitada_up' => ['nullable', 'numeric'],
            'pontas.*.vel_solicitada_down' => ['nullable', 'numeric'],
            'pontas.*.barra' => ['nullable'],
            'pontas.*.servicos' => ['nullable', 'array'],
            'pontas.*.id_instalacao' => ['nullable'],
        ];
    }
}
