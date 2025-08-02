<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedor;
use App\Models\FornecedorCidade;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Orcamento;
use App\Models\Cotacao;
use App\Models\Site;
use App\Http\Requests\UpdateCidadesRequest;
use App\Http\Requests\AttachCidadeRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Imports\FornecedorCidadesImport;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class FornecedorCidadeController extends Controller
{
    public function update_cidades(UpdateCidadesRequest $request, Fornecedor $fornecedor)
    {
        // dd($request->validated());
        $arquivo = $request->validated()['cidades_sheet'];

        try {
            Excel::import(new FornecedorCidadesImport($fornecedor->id), $arquivo);
            // Seu código para importar e processar o arquivo aqui
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['erro' => "Erro: Planilha vazia ou dados repetidos. " . $e->getMessage()]);
        }

        $nomeOriginal = $arquivo->getClientOriginalName();

        $arquivo->move('uploads', $nomeOriginal);

        return redirect()->route('fornecedor.show', ['fornecedor' => $fornecedor])->with('success', 'Operação realizada com sucesso!');
    }

    public function detach_cidade(Request $request)
    {
        // dd($request['fornecedor_id']);
        $fornecedor_cidade = FornecedorCidade::where('cidade_id', $request['cidade_id'])->where('fornecedor_id', $request['fornecedor_id'])->first();
        $fornecedor_cidade->delete();

        return redirect()->back()->with('success', 'Operação realizada com sucesso!');
    }


    public function attach_cidade(AttachCidadeRequest $request, Fornecedor $fornecedor)
    {
        $cidade = Cidade::where('nome', $request->validated()['nome'])->where('estado_id', Estado::where('uf', $request->validated()['estado'])->first()->id)->first();

        try {
            FornecedorCidade::create([
                'fornecedor_id' => $fornecedor->id,
                'cidade_id' => $cidade->id, 
            ]);
        } catch(\Exception $e){
            return redirect()->back()->withErrors(['erro' => 'Erro: Cidade não existe ou incompatível com o estado, tente novamente.']);
        }
        // dd($cidade);
        return redirect()->back()->with('success', 'Operação realizada com sucesso!');
    }
}
