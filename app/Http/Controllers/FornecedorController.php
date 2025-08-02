<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\FornecedorCidade;
use App\Models\Cidade;
use App\Models\Orcamento;
use App\Models\Cotacao;
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
    public function create(Site $site)
    {
        $cidades = Cidade::all();
        if($site){
            return view('fornecedor.create', ['cidades' => $cidades, 'site' => $site]);
        } else{
            return view('fornecedor.create', ['cidades' => $cidades]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FornecedorRequest $request, Site $site = null)
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
        if($site){
            return redirect()->route('cotacao.create', ['site' => $site, 'fornecedor' => $fornecedor]);
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

}
