<?php

namespace App\Http\Controllers;

use App\Models\Cotacao;
use App\Models\CotacaoServico;
use App\Models\Fornecedor;
use App\Models\Site;
use App\Models\Servico;
use Illuminate\Http\Request;
use App\Http\Requests\CotacaoRequest;
use App\Http\Requests\StatusRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\OrcamentoController;

class CotacaoController extends Controller
{
    protected $orcamentoController;

    public function __construct(OrcamentoController $orcamentoController)
    {
        $this->orcamentoController = $orcamentoController;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cotacoes = Cotacao::all();
        $sites = Site::all();
        // dd($sites);
        return view('cotacao.index', ['cotacoes' => $cotacoes, 'sites' => $sites]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Site $site, Fornecedor $fornecedor = null)
    {
        $empresa = auth()->user()->Empresa;
        if(empty($empresa->gear_noc) || empty($empresa->custo_fixo_percent)){
            return redirect()->route('empresa.show', ['empresa' =>$empresa])->with('error', 'Parametrize o GearNoc e a % do Custo fixo!');
        }
        if (Fornecedor::whereHas('cidades', function (Builder $query) use ($site) {
            $query->where('fornecedor_cidade.cidade_id', $site->cidade_id);
        })->exists()){
            $fornecedores = Fornecedor::whereHas('cidades', function (Builder $query) use ($site) {
                $query->where('fornecedor_cidade.cidade_id', $site->cidade_id);
            })->get();
            // dd($fornecedores);
            return view('cotacao.create', ['site' => $site, 'fornecedores' => $fornecedores, 'fornecedor' => $fornecedor, 'servicos' => Servico::all()]);
        } else{
            return redirect()->route('fornecedor.create', ['site' => $site, 'fornecedor' => $fornecedor]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CotacaoRequest $request)
    {
        $dados = $request->validated();
        DB::transaction(function() use($dados, &$cotacao){
            $cotacao = Cotacao::create($dados);
            if(!empty($dados['servicos'])){
                foreach($dados['servicos'] as $servico){
                    $servico['cotacao_id'] = $cotacao->id;
                    CotacaoServico::create($servico);
                }
            }
            if(empty($cotacao->Site->Orcamento->gear_noc)){
                $cotacao->Site->Orcamento->update(['gear_noc' => auth()->user()->Empresa->gear_noc]);
            }
            if(empty($cotacao->Site->Orcamento->custo_fixo_percent)){
                $cotacao->Site->Orcamento->update(['custo_fixo_percent' => auth()->user()->Empresa->custo_fixo_percent]);
            }
            $this->orcamentoController->atualizaValoresTotais($cotacao->Site->Orcamento);
            Log::channel('main')->info('Nova cotação cadastrada', ['cotacao' => $cotacao, 'user' => auth()->user()->nome]);
            
            session()->flash('success', 'Cotação cadastrada com sucesso!');
        });
        return response()->json([
            'success' => true,
            'redirect_url' => route('orcamento.show', ['orcamento' => $cotacao->Site->Orcamento]),
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cotacao $cotacao)
    {
        $empresa = auth()->user()->Empresa;
        if(empty($empresa->gear_noc) || empty($empresa->custo_fixo_percent)){
            return redirect()->route('empresa.show', ['empresa' =>$empresa])->with('error', 'Parametrize o GearNoc e a % do Custo fixo!');
        }
        $site = $cotacao->Site;
        if (Fornecedor::whereHas('cidades', function (Builder $query) use ($site) {
            $query->where('fornecedor_cidade.cidade_id', $site->cidade_id);
        })->exists()){
            $fornecedores = Fornecedor::whereHas('cidades', function (Builder $query) use ($site) {
                $query->where('fornecedor_cidade.cidade_id', $site->cidade_id);
            })->get();
            // dd($fornecedores);
            return view('cotacao.edit', ['cotacao' => $cotacao, 'fornecedores' => $fornecedores, 'servicos' => Servico::all()]);
        } else{
            return redirect()->route('fornecedor.create');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CotacaoRequest $request, Cotacao $cotacao)
    {
        $antigo = $cotacao->getOriginal();

        $dados = $request->validated();

        DB::transaction(function() use($dados, &$cotacao){
            $cotacao->update($dados);
            
            if(!empty($dados['servicos'])){
                CotacaoServico::where('cotacao_id', $cotacao->id)->delete();
                foreach($dados['servicos'] as $servico){
                    $servico['cotacao_id'] = $cotacao->id;
                    CotacaoServico::create($servico);
                }
            }
            
            if(empty($cotacao->Site->Orcamento->gear_noc)){
                $cotacao->Site->Orcamento->update(['gear_noc' => auth()->user()->Empresa->gear_noc]);
            }
            if(empty($cotacao->Site->Orcamento->custo_fixo_percent)){
                $cotacao->Site->Orcamento->update(['custo_fixo_percent' => auth()->user()->Empresa->custo_fixo_percent]);
            }
            $this->orcamentoController->atualizaValoresTotais($cotacao->Site->Orcamento);
        });

        // Comparar os valores antigos com os novos valores após a atualização
        $valores_alterados = [];
        foreach ($antigo as $key => $valor) {
            if ($valor != $cotacao->{$key}) {
                $valores_alterados[$key] = [
                    'antigo' => $valor,
                    'novo' => $cotacao->{$key},
                ];
            }
        }

        // Verificar se houve alterações e registrar no log
        if (!empty($valores_alterados)) {
            Log::channel('main')->info('Cadastro de cotacao alterado', [
                'cotacao' => $cotacao->nome,
                'user' => auth()->user()->nome,
                'valores_alterados' => $valores_alterados,
            ]);
        }

        return redirect()->route('orcamento.show', ['orcamento' => $cotacao->Site->Orcamento]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cotacao $cotacao)
    {
        $this->middleware('permission:excluir cotacao');
        $orcamento = $cotacao->Site->Orcamento;
        if($cotacao->status == 'Fechado'){
            $orcamento->lucro_mensal_total -= $cotacao->lucro_liquido;
            $orcamento->adesao_total -= $cotacao->custo_instalacao_imp;
            $orcamento->save();
        }

        $cotacao->Site()->dissociate();
        $cotacao->Fornecedor()->dissociate();
        $cotacao->delete();
        CotacaoServico::where('cotacao_id', $cotacao->id)->delete();
        Log::channel('main')->info('Cotação excluída', ['cotacao' => $cotacao, 'user' => auth()->user()->nome]);
        return redirect()->route('orcamento.show', ['orcamento' => $orcamento]);
    }

    /**
     * Muda o status do orçamento na view index
     */
    public function altera_status(StatusRequest $request, Cotacao $cotacao)
    {
        $dados = $request->validated();
        
        DB::transaction(function() use($dados, &$cotacao){
            $cotacao->update(['status' => $dados['status']]);
            
            $this->orcamentoController->atualizaValoresTotais($cotacao->Site->Orcamento);
        });

        return redirect()->route('orcamento.show', ['orcamento' => $cotacao->Site->Orcamento]);
    }
}
