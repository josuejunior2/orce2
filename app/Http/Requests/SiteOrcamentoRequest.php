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
            'latitude' => ['nullable', 'regex:/^(-?\d{1,3}°\d{1,2}\'\d{1,2}(\.\d+)?\"[NS])|(\'?-?\d{1,2}[.,]\d{1,30})$/', Rule::unique('sites')->withoutTrashed()],
            'longitude' => ['nullable', 'regex:/^(-?\d{1,3}°\d{1,2}\'\d{1,2}(\.\d+)?\"[EO])|(\'?-?\d{1,3}[.,]\d{1,30})$/', Rule::unique('sites')->withoutTrashed()],
            'vel_solicitada_down' => ['nullable', 'numeric'],
            'vel_solicitada_up' => ['nullable', 'numeric'],
            'servicos' => ['nullable', 'exists:servicos,id'],
            'barra' => ['nullable'],
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
            'latitude.max' => 'O valor máximo para latitude é 99.',
            'longitude.max' => 'O valor máximo para longitude é 99.',
            'latitude.regex' => 'O formato da latitude deve ser válido, como 36°23\'08.3"S ou -30.046750555651297.',
            'longitude.regex' => 'O formato da longitude deve ser válido, como 36°23\'08.3"W ou -30.046750555651297.',
            'latitude.unique' => 'Não é permitido cadastrar sites com a mesma latitude.',
            'longitude.unique' => 'Não é permitido cadastrar sites com a mesma longitude.',
        ];
    }

    // public function withValidator($validator){
    //     $validator->after(function ($validator) {

    //     $cidadeId = $this->input('cidade_id');
    //     $fornecedorId = $this->input('fornecedor_id');

    //     $fornecedor = Fornecedor::find($fornecedorId);
    //     $cidades = [];
    //     foreach ($fornecedor->cidades as $fc) {
    //         $cidades[] = $fc->nome; // Adiciona o nome da cidade ao array
    //     }
    //     $stringCidade = implode(', ', $cidades);
    //     // dd($stringCidade);

    //     $achou = false;
    //     foreach($fornecedor->cidades as $f){
    //         if ($f->id == $cidadeId) {
    //             // Fornecedor atende à cidade, continuar com a validação
    //             $achou = true;
    //             break;
    //         }
    //     }
    //     if(!$achou){
    //         $validator->errors()->add('cidade_id', 'O fornecedor selecionado não atende a cidade fornecida. '.$fornecedor->nome.' atende na(s) cidade(s): '.$stringCidade );
    //     }
    //     });
    // }

    // public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     dd($validator->errors());
    // }
}
