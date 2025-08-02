<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Servico;
use App\Models\Orcamento;
use App\Models\Cidade;
use Illuminate\Http\Request;
use App\Http\Requests\SiteRequest;
use App\Http\Requests\SiteSheetRequest;
use App\Imports\SiteImport;
use App\Models\SiteOrcamentoServico;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Orcamento $orcamento, $botao = null)
    {
        $cidades = Cidade::all();

        return view('site.create', ['orcamento' => $orcamento, 'cidades' => $cidades, 'botao' => $botao, 'servicos' => Servico::all()]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(SiteRequest $request)
    {
        $dados = $request->validated();
        
        DB::transaction(function() use($dados, &$qtdeRestante, &$site, &$botao){
            $site = Site::create($dados);
            if(!empty($dados['servicos'])) {
                foreach(explode(",", $dados['servicos']) as $servico){
                    SiteOrcamentoServico::create(['site_id' => $site->id, 'servico_id' => $servico]);
                }
            }
            dd($dados, $site);
            Log::channel('main')->info('Novo site cadastrado.', [ 'cliente' => $site->Orcamento->Cliente, 'orcamento' => $site->Orcamento, 'site' => $site, 'user' => auth()->user()->nome]);
        });

        if($dados['cadastrar_mais']) {
            return redirect()->route('site.create', ['orcamento' => $site->Orcamento]);
        }
        return redirect()->route('orcamento.show', $site->Orcamento);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_sheet(SiteSheetRequest $request, Orcamento $orcamento)
    {
        $dados = $request->validated();
        $arquivo = $dados['sites_sheet'];
        
        try {
            $sites = Excel::import(new SiteImport($orcamento, $dados['colunas']), $arquivo);
        } catch (\Exception $e) {
            Log::channel('main')->error($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
        $nomeOriginal = $arquivo->getClientOriginalName();

        $arquivo->move('uploads', $nomeOriginal);
        
        return redirect()->back()->with('success', 'Operação realizada com sucesso!');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function createl2l(Orcamento $orcamento, $botao = null, $isPontaA = null)
    {
        if($isPontaA == null) { $isPontaA = false; }
        $cidades = Cidade::all();
        if(is_null($botao)){
            return view('site.createL2L', ['orcamento' => $orcamento, 'cidades' => $cidades, 'isPontaA' => $isPontaA]);
        }else {
            return view('site.createL2L', ['orcamento' => $orcamento, 'cidades' => $cidades, 'botao' => $botao, 'isPontaA' => $isPontaA]);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function createMais1PontaA(Orcamento $orcamento, $isPontaA)
    {
        $cidades = Cidade::all();
        return view('site.createL2L', ['orcamento' => $orcamento, 'cidades' => $cidades, 'isPontaA' => $isPontaA]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storel2l(SiteRequest $request)
    {
        $dados = $request->validated();
        $site = Site::create($dados);
        // dd($site);
        Log::channel('main')->info('Novo site cadastrado.', [ 'cliente' => $site->Orcamento->Cliente, 'orcamento' => $site->Orcamento, 'site' => $site, 'user' => auth()->user()->nome]);

        $quantidadeRealPontasA = $site->Orcamento->sitesOrcamento->filter(function ($site) { return Str::startsWith($site->nome, '(Ponta A)'); })->count();
        $quantidadeRealSitesNormais = $site->Orcamento->sitesOrcamento->filter(function ($site) { return !Str::startsWith($site->nome, '(Ponta A)'); })->count();

        $qtdeRestantePontasA = $site->Orcamento->quantidade_pontasA - $quantidadeRealPontasA;
        if($qtdeRestantePontasA > 1){
            $botao = 'Cadastrar próxima Ponta A ('.($qtdeRestantePontasA - 1).' restantes)';
            $isPontaA = true;

            return redirect()->route('site.create.l2l', ['orcamento' => $site->Orcamento, 'botao' => $botao, 'isPontaA' => $isPontaA]);
        } else if($qtdeRestantePontasA == 1){
            if($site->Orcamento->quantidade_sites > $quantidadeRealSitesNormais){ $botao = 'Finalizar cadastro de pontas A ('.$site->Orcamento->quantidade_sites.' sites restantes)'; }
            if($site->Orcamento->quantidade_sites == $quantidadeRealSitesNormais){ $botao = 'Finalizar cadastro de pontas A'; }
            $isPontaA = true;

            return redirect()->route('site.create.l2l', ['orcamento' => $site->Orcamento, 'botao' => $botao, 'isPontaA' => $isPontaA]);
        } else if($qtdeRestantePontasA == -1){ // nao ta funcionando TESTAR
            $site->Orcamento->quantidade_pontasA += 1;
            $site->Orcamento->save();
            return redirect()->route('orcamento.show', $site->Orcamento);
        }

        $qtdeRestante = $site->Orcamento->quantidade_sites - $site->Orcamento->sitesOrcamento->count() + $site->Orcamento->quantidade_pontasA;


        $botao = 'Cadastrar próximo site ('.$qtdeRestante.' restantes)';
        // dd($botao);
        if($qtdeRestante == 0){ // se nao tiver mais nenhum para cadastrar, mandar para o show orçamento
            return redirect()->route('orcamento.show', $site->Orcamento);
        } elseif($qtdeRestante == 1){
            $botao = 'Finalizar Cadastro'; // porque o que estará na tela será o último
            // $isPontaA = false;
            // dd($isPontaA);

            return redirect()->route('site.create.l2l', ['orcamento' => $site->Orcamento, 'botao' => $botao]);
        } else if($qtdeRestante == -1){ // se for -1, significa que o user cadastra pelo botão adicionar site.
            $site->Orcamento->quantidade_sites += 1;
            $site->Orcamento->save();

            return redirect()->route('orcamento.show', $site->Orcamento);
        } else{ // se a qtdeRestante > 1, vai mandando pra rota create que uma hora fica == 1
            return redirect()->route('site.create.l2l', ['orcamento' => $site->Orcamento, 'botao' => $botao]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site)
    {
        $cidades = Cidade::all();
        return view('site.edit', ['site' => $site, 'cidades' => $cidades, 'servicos' => Servico::all()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SiteRequest $request, Site $site)
    {
        $dados = $request->validated();
        DB::transaction(function() use($dados, &$site){
            $site->update($dados);

            SiteOrcamentoServico::where(['site_id' => $site->id])->delete();
            foreach(explode(",", $dados['servicos']) as $servico){
                SiteOrcamentoServico::create(['site_id' => $site->id, 'servico_id' => $servico]);
            }
        });
        return redirect()->route('orcamento.show', ['orcamento' => $site->Orcamento]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        $this->middleware('permission:excluir site');
        
        SiteOrcamentoServico::where('site_id', $site->id)->delete();
        $orcamento = $site->Orcamento;
        foreach($site->cotacoes as $cotacao){
            $cotacao->delete();
        }
        $site->Orcamento()->dissociate();
        if (Str::startsWith($site->nome, '(Ponta A)')) {
            $orcamento->quantidade_pontasA -= 1;
        } else {
            $orcamento->quantidade_sites -= 1;
        }
        $site->delete();
        $orcamento->save();
        return redirect()->route('orcamento.show', ['orcamento' => $orcamento]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function downloadModeloPlanilha()
    {
        $filePath = public_path('files/modelo_importacao_sites.xlsx');
        return Response::download($filePath);
    }
}
