<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\FornecedorCidade;
use App\Models\Cidade;
use App\Models\Orcamento;
use App\Models\Cotacao;
use App\Models\SiteOrcamento;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Http\Requests\FornecedorRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fornecedores = Fornecedor::all();
        // dd($fornecedores->cidades);
        return view('fornecedor.index', ['fornecedores' => $fornecedores]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(SiteOrcamento $siteOrcamento)
    {
        $cidades = Cidade::all();
        // dd($siteOrcamento);
        if($siteOrcamento){
            return view('fornecedor.create', ['cidades' => $cidades, 'siteOrcamento' => $siteOrcamento]);
        } else{
            return view('fornecedor.create', ['cidades' => $cidades]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FornecedorRequest $request, SiteOrcamento $siteOrcamento = null)
    {
        $fornecedor = Fornecedor::create($request->validated());
        if(!empty($request->input('cidades'))){
            foreach(explode(",", $request->input('cidades')) as $cidadeID){
                FornecedorCidade::create([
                    'fornecedor_id' => $fornecedor->id, // Substitua pelo ID real do fornecedor
                    'cidade_id' => $cidadeID, // Substitua pelo ID real da cidade
                ]);
            }
        }
        Log::channel('main')->info('Novo fornecedor cadastrado', ['fornecedor' => $fornecedor->nome, 'user' => auth()->user()->nome]);
        if(!empty($siteOrcamento)){
            return redirect()->route('cotacao.create', ['siteOrcamento' => $siteOrcamento, 'fornecedor' => $fornecedor]);
        } else {
            return redirect()->route('fornecedor.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Fornecedor $fornecedor)
    {
        return view('fornecedor.show', ['fornecedor' => $fornecedor]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fornecedor $fornecedor)
    {
        return view('fornecedor.edit', ['fornecedor' => $fornecedor]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FornecedorRequest $request, Fornecedor $fornecedor)
    {
        // Obter os valores antigos do fornecedor antes da atualização
        $antigo = $fornecedor->getOriginal();
        // dd($request->input('cidades'));
        // Atualizar o fornecedor com os dados validados do request
        $fornecedor->update($request->validated());

        // Comparar os valores antigos com os novos valores após a atualização
        $valores_alterados = [];
        foreach ($antigo as $key => $valor) {
            if ($valor != $fornecedor->{$key}) {
                $valores_alterados[$key] = [
                    'antigo' => $valor,
                    'novo' => $fornecedor->{$key},
                ];
            }
        }

        // Verificar se houve alterações e registrar no log
        if (!empty($valores_alterados)) {
            Log::channel('main')->info('Cadastro de fornecedor alterado', [
                'fornecedor' => $fornecedor->nome,
                'user' => auth()->user()->nome,
                'valores_alterados' => $valores_alterados,
            ]);
        }


        return redirect()->route('fornecedor.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fornecedor $fornecedor)
    {
        $this->middleware('permission:excluir fornecedor');
        $fornecedor->cidades()->detach();
        $fornecedor->cotacoes()->delete();
        $fornecedor->delete();
        Log::channel('main')->info('Fornecedor excluído', ['fornecedor' => $fornecedor, 'user' => auth()->user()->nome]);
        return redirect()->route('fornecedor.index');
    }

    public function getFornecedores(Request $request)
    {
        $nome = $request->input('nome');
        $fornecedores = Fornecedor::where('nome', 'like', '%' . $nome . '%')->get()->map(function ($fornecedor) {
            return [
                'id' => $fornecedor->id,
                'nome' => $fornecedor->nome . (!empty($fornecedor->cnpj) ? " (" . $fornecedor->cnpj . ")" : ""),
            ];
        });
        return response()->json($fornecedores);
    }
    

    public function getFornecedoresDisponiveisParaCidade(Request $request)
    {
        $cidade_id = $request->input('cidade_id');

        $fornecedores = Fornecedor::whereHas('cidades', function (Builder $query) use ($cidade_id) {
            $query->where('fornecedor_cidade.cidade_id', $cidade_id);
        })->get()->map(function ($fornecedor) {
            return [
                'id' => $fornecedor->id,
                'nome' => $fornecedor->nome . (!empty($fornecedor->cnpj) ? " (" . $fornecedor->cnpj . ")" : ""),
            ];
        });

        return response()->json(['fornecedores' => $fornecedores]);
    }
}
