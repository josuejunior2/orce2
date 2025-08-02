<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CotacaoRequest extends FormRequest
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
            'fornecedor_id' => 'required',
            'site_id' => 'required',
            'vel_down' => 'nullable|numeric',
            'vel_up' => 'nullable|numeric',
            'tecnologia' => 'required|string|max:255',
            'adesao_fornecedor' => 'required|numeric',
            'mensal_fornecedor' => 'required|numeric',
            'mensal_imp' => 'nullable|numeric',
            'custo_instalacao_imp' => 'nullable|numeric',
            'prazo_instalacao' => 'nullable',
            'prazo_instalacao_fornecedor' => 'required',
            'custo_operacional' => 'nullable|min:0|numeric',
            'custo_ativacao' => 'required|numeric',
            'imposto_mensal' => 'nullable|numeric',
            'imposto_adesao' => 'nullable|numeric',
            'lucro_adesao' => 'nullable|numeric',
            'lucro_liquido' => 'nullable|numeric',
            'status' => 'required|in:Em aberto,Fechado',
            'barra' => 'nullable|numeric|min:0|max:32',
            'servicos.*.servico_id' => 'required|exists:servicos,id',
            'servicos.*.vel_down' => 'nullable|numeric|min:0',
            'servicos.*.vel_up' => 'nullable|numeric|min:0',
            'servicos.*.barra' => 'nullable|numeric|min:0|max:32',
            'servicos.*.adesao' => 'nullable|numeric|min:0',
            'servicos.*.mensalidade' => 'nullable|numeric|min:0',
            'servicos.*.obs' => 'nullable',
            'custo_fixo' => 'nullable|numeric',
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
        ];
    }
}
