<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\Fornecedor;
use App\Models\Cliente;
use App\Models\Servico;
use App\Http\Requests\OrcamentoRequest;
use App\Http\Requests\StatusRequest;
use App\Http\Requests\OrcamentoRecalculateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\CoordService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Exports\OrcamentoExport;
use Maatwebsite\Excel\Facades\Excel;

class OrcamentoController extends Controller
{
    private $coordService;
    
	public function __construct(CoordService $coordService)
    {
        $this->coordService = $coordService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orcamentos = Orcamento::with('Cliente')->get()->map(function ($orc) {
            return [
                'id' => $orc->id,
                'titulo' => $orc->titulo,
                'nome' => $orc->Cliente->nome,
                'created_at' => $orc->created_at,
                'tipo_link' => $orc->tipo_link,
                'tempo_contrato' => $orc->tempo_contrato,
                'quantidade_sites' => !empty($orc->sitesOrcamento) ? $orc->sitesOrcamento()->whereNull('site_orcamento_id')->count() : 0,
                'status' => \App\Models\Orcamento::getStatusTexto($orc->status),
            ];
        })->toArray();
        return Inertia::render('TabelaOrcamentos', [
            'orcamentos' => $orcamentos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        // dd($clientes);
        $fornecedores = Fornecedor::all();
        return view('orcamento.create', ['fornecedores' => $fornecedores, 'clientes' =>$clientes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrcamentoRequest $request)
    {
        $dados = $request->validated();
        $dados['cadastrar_sites'] = $request->boolean('cadastrar_sites');
        
        DB::transaction(function() use($dados, &$orcamento){
            $dados['gear_noc'] = auth()->user()->Empresa->gear_noc;
            $dados['custo_fixo_percent'] = auth()->user()->Empresa->custo_fixo_percent;
            $orcamento = Orcamento::create($dados);
            Log::channel('main')->info('Novo orçamento cadastrado.', ['orcamento' => $orcamento, 'cliente' => $orcamento->Cliente, 'user' => auth()->user()->nome]);
        });

        if($dados['cadastrar_sites']){
            return redirect()->route('siteOrcamento.create', ['orcamento' => $orcamento]);
        }
        return redirect()->route('orcamento.show', ['orcamento' => $orcamento]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Orcamento $orcamento)
    {
        // $coordenadasDecimal = $this->coordService->toDecimalForView($orcamento->sitesOrcamento()->with('Site')->whereHas('Site', function($q) {
        //     $q->whereNotNull('latitude');
        //     $q->whereNotNull('longitude');
        // }));
        
        // $colunas = ['nome', 'endereco', 'cidade', 'uf', 'latitude', 'longitude', 'vel_solicitada_down', 'vel_solicitada_up', 'barra'];

        // $sitesOrcamento = $orcamento->sitesOrcamento()->whereNull('site_orcamento_id')->get()->sortBy('nome');

        // return view('orcamento.show', ['orcamento' => $orcamento, 'sitesOrcamento' => $sitesOrcamento, 'coordenadasDecimal' => $coordenadasDecimal, 'servicos' => Servico::all(), 'colunas' => $colunas]);

        /**
         * nome do site
         * endereço
         * velocidade
         */
        
        $orcamento->load('Cliente');
        $orcamento->status = Orcamento::getStatusTexto($orcamento->status);


        $sitesOrcamento = $orcamento->sitesOrcamento()->whereDoesntHave('Subestacao')->with('Site')->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'site_id' => $s->Site->id,
                'nome' => $s->Site->nome,
                'endereco' => $s->endereco,
                'cidade' => $s->Site->Cidade->nome,
                'estado' => $s->Site->Cidade->Estado->uf,
                'coords' => $s->Site->latitude . ", " . $s->Site->longitude,
                'vel_solicitada_down' => $s->vel_solicitada_down,
                'vel_solicitada_up' => $s->vel_solicitada_up,
                'barra' => $s->barra,
                'pontas' => $s->pontas->map(function($p){
                    return [
                        'id' => $p->id,
                        'site_id' => $p->Site->id,
                        'nome' => $p->Site->nome,
                        'endereco' => $p->endereco,
                        'cidade' => $p->Site->Cidade->nome,
                        'estado' => $p->Site->Cidade->Estado->uf,
                        'coords' => $p->Site->latitude . ", " . $p->Site->longitude,
                        'vel_solicitada_down' => $p->vel_solicitada_down,
                        'vel_solicitada_up' => $p->vel_solicitada_up,
                        'barra' => $p->barra,
                    ];
                })->toArray(),
                'cotacoes' => $s->cotacoes->map(function($c){
                    return [
                        'selecionar' => '',
                        'id' => $c->id,
                        'fornecedor' => $c->Fornecedor->nome,
                        'custo_ativacao' => $c->custo_ativacao,
                        'velocidade' => $c->vel_down . " Down / " . $c->vel_up . " Up",
                        'tecnologia' => $c->getTecnologiaTexto($c->tecnologia),
                        'adesao_fornecedor' => $c->adesao_fornecedor,
                        'custo_operacional' => $c->custo_operacional,
                        'mensal_imp' => $c->mensal_imp,
                        'mensal_fornecedor' => $c->mensal_fornecedor,
                        'custo_instalacao_imp' => $c->custo_instalacao_imp,
                        'prazo_instalacao' => $c->prazo_instalacao,
                        'prazo_instalacao_fornecedor' => $c->prazo_instalacao_fornecedor,
                        'imposto_mensal' => $c->imposto_mensal,
                        'imposto_adesao' => $c->imposto_adesao,
                        'lucro_adesao' => $c->lucro_adesao,
                        'lucro_liquido' => $c->lucro_liquido,
                        'custo_fixo' => $c->custo_fixo,
                    ];
                })->toArray()
            ];
        })->toArray();
        $servicos = Servico::all()->map(function ($s) {
            return [
                'id' => $s->id,
                'nome' => $s->nome,
            ];
        })->toArray();

        return Inertia::render('OrcamentoShow', [
            'orcamento' => $orcamento,
            'sitesOrcamentoArray' => $sitesOrcamento,
            'servicos' => $servicos
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orcamento $orcamento)
    {
        $clientes = Cliente::all();
        return view('orcamento.edit', ['orcamento' => $orcamento, 'clientes' => $clientes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrcamentoRequest $request, Orcamento $orcamento)
    {
        $orcamento->update($request->validated());

        return redirect()->route('cotacao.index');
    }
    
    /**
     * Muda o status do orçamento na view index
     */
    public function altera_status(StatusRequest $request, Orcamento $orcamento)
    {
        $orcamento->status = $request->input('status');
        $orcamento->save();
        
        return redirect()->route('cotacao.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orcamento $orcamento)
    {
        $this->middleware('permission:excluir orcamento');
        foreach($orcamento->sitesOrcamento as $os){
            foreach($os->cotacoes as $cot){
                $cot->delete();
            }
            $os->delete();
        }
        $orcamento->delete();
        Log::channel('main')->info('Orcamento excluído', ['orcamento' => $orcamento, 'user' => auth()->user()->nome]);
        return redirect()->route('cotacao.index');
    }

    /**
     * Muda o status do orçamento na view index
     */
    public function altera_status_home(Request $request)
    {
        $orcamento = Orcamento::find(explode('drag', $request->orcamentoId)[1]);
        $orcamento->status = $request->status;
        $orcamento->save();
        return response()->json([]);
    }

    public function recalculate(OrcamentoRecalculateRequest $request, Orcamento $orcamento)
    {
        $dados = $request->validated();
        DB::transaction(function() use($orcamento, $dados){
            $orcamento->update([
                'gear_noc' => $dados['gear_noc'],
                'custo_fixo_percent' => $dados['custo_fixo_percent']
            ]);
            foreach($orcamento->sitesOrcamento as $site){
                foreach($site->cotacoes as $cotacao){
                    $porcentagemImposto =  ($orcamento->imposto / 100);
                    $impostoMensal = $cotacao->mensal_imp * $porcentagemImposto;
                    $impostoAdesao = $cotacao->custo_instalacao_imp * $porcentagemImposto;
                    $custoOperacional = $cotacao->mensal_fornecedor + $dados['gear_noc'];
                    $custoFixo = $cotacao->mensal_imp * ($dados['custo_fixo_percent'] / 100);
                    $lucroLiquido = $cotacao->mensal_imp - $custoFixo - $custoOperacional - $impostoMensal;
                    $lucroAdesao = $cotacao->custo_instalacao_imp - $cotacao->adesao_fornecedor - $impostoAdesao;
                    $cotacao->update([
                        'imposto_mensal' => $impostoMensal,
                        'imposto_adesao' => $impostoAdesao,
                        'custo_operacional' => $custoOperacional,
                        'custo_fixo' => $custoFixo,
                        'lucro_liquido' => $lucroLiquido,
                        'lucro_adesao' => $lucroAdesao,
                    ]);
                }
            }
        });
        return redirect()->route('orcamento.show', ['orcamento' => $orcamento])->with('success', 'Recálculo efetuado!');
    }

    public function export_orcamento(Orcamento $orcamento)
    {
        $orcamento->load('sites.cotacoes', 'Cliente', 'sites.Cidade.Estado');
        // dd($orcamento);
        return Excel::download(new OrcamentoExport($orcamento), "orcamento-" . Str::slug($orcamento->titulo) . ".xlsx");
    }

    public function atualizaValoresTotais(Orcamento $orcamento): void
    {
        DB::transaction(function() use($orcamento){
            $lucro_mensal_total = 0;
            $adesao_total = 0;
            foreach($orcamento->sitesOrcamento as $site){
                if($site->cotacoes->isNotEmpty()){
                    foreach($site->cotacoes()->where('status', 'Fechado')->get() as $cotacao){
                        $lucro_mensal_total += $cotacao->lucro_liquido;
                        $adesao_total += $cotacao->custo_instalacao_imp;
                    }
                }
            }
            $orcamento->update([
                'lucro_mensal_total' => $lucro_mensal_total,
                'adesao_total' => $adesao_total,
            ]);
        });
    }
    
    public function getOrcamentos(Request $request)
    {
        $titulo = $request->input('titulo');

        $orcamentos = Orcamento::where('titulo', 'like', '%' . $titulo . '%')
            ->select('id', 'titulo', 'cliente_id', 'status')
            ->with('Cliente', 'sitesOrcamento')
            ->limit(10)
            ->get()->map(function ($o) {
            return [
                'id' => $o->id,
                'tituloDisplay' => $o->titulo . " | " . $o->Cliente->nome . " | " . $o->sitesOrcamento()->whereNull('site_orcamento_id')->count() . " sites",
            ];
        })->toArray();

        return response()->json($orcamentos);
    }

    public function siteImport(Orcamento $orcamento)
    {
        $orcamento->tituloDisplay = $orcamento->titulo . " | " . $orcamento->Cliente->nome . " | " . $orcamento->sitesOrcamento()->whereNull('site_orcamento_id')->count() . " sites";
        
        return Inertia::render('SiteImport', [
            'orcamento' => [
                'id' => $orcamento->id,
                'titulo' => $orcamento->titulo,
                'tituloDisplay' => $orcamento->tituloDisplay,
            ],
        ]);
    }
}
