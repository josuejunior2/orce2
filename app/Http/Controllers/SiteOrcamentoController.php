<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\SiteOrcamento;
use App\Models\Servico;
use App\Models\Orcamento;
use App\Models\Cidade;
use Illuminate\Http\Request;
use App\Http\Requests\SiteOrcamentoRequest;
use App\Http\Requests\SiteSheetRequest;
use App\Imports\SiteOrcamentoImport;
use App\Models\SiteOrcamentoServico;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SiteOrcamentoController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Orcamento $orcamento)
    {
        $cidades = Cidade::all()->map(function ($c) {
            return [
                'id' => $c->id,
                'nome' => $c->nome . " - " . $c->Estado->uf,
            ];
        })->toArray();
        $servicos = Servico::all()->map(function ($s) {
            return [
                'id' => $s->id,
                'nome' => $s->nome,
            ];
        })->toArray();
        $orcamento->load('Cliente');
        $orcamento->status = Orcamento::getStatusTexto($orcamento->status);
        
        return Inertia::render('SiteForm', [
            'cidades' => $cidades,
            'servicos' => $servicos,
            'orcamento' => $orcamento
        ]);
        // return view('site.create', ['orcamento' => $orcamento, 'cidades' => $cidades, 'botao' => $botao, 'servicos' => Servico::all()]);
    }
    
    public function getSites(Request $request)
    {
        $nome = $request->input('nome');
        $idOrcamento = $request->input('orcamento_id');

        $sites = Site::where('nome', 'like', '%' . $nome . '%')
            ->select('id', 'nome', 'cidade_id', 'endereco', 'latitude', 'longitude', 'id_instalacao')
            ->limit(10)
            ->get()->map(function ($s) use($idOrcamento) {
            return [
                'id' => $s->id,
                'nomeDisplay' => $s->nome . (!empty($s->id_instalacao) ? " | " . $s->id_instalacao : "") . (!empty($s->endereco) ? " | " . $s->endereco : "") . " | " . $s->Cidade->nome . " - " . $s->Cidade->Estado->uf,
                'nome' => $s->nome,
                'endereco' => $s->endereco,
                'latitude' => $s->latitude,
                'longitude' => $s->longitude,
                'cidade_id' => $s->cidade_id,
                'orcado'    => $s->sitesOrcamento()->where('orcamento_id', $idOrcamento)->exists()
            ];
        })->toArray();

        return response()->json($sites);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(SiteOrcamentoRequest $request)
    {
        $dados = $request->validated();
        // dd($dados);
        DB::transaction(function() use($dados, &$siteOrcamento){
            $site = !empty($dados['site_id']) ? Site::find($dados['site_id']) : Site::create($dados);

            $dados['site_id'] = $site->id;
            $siteOrcamento = SiteOrcamento::create($dados);

            if(!empty($dados['servicos'])) {
                foreach($dados['servicos'] as $servico){
                    SiteOrcamentoServico::create(['site_orcamento_id' => $siteOrcamento->id, 'servico_id' => $servico]);
                }
            }
            
            if(!empty($dados['pontas'])) {
                foreach($dados['pontas'] as $ponta) {
                    $sitePonta = Site::create($ponta);
                    $ponta['site_id'] = $sitePonta->id;
                    $ponta['site_orcamento_id'] = $siteOrcamento->id;
                    $ponta['orcamento_id'] = $dados['orcamento_id'];
                    $siteOrcamentoPonta = SiteOrcamento::create($ponta);
                    if(!empty($ponta['servicos'])) {
                        foreach($ponta['servicos'] as $servico){
                            SiteOrcamentoServico::create(['site_orcamento_id' => $siteOrcamentoPonta->id, 'servico_id' => $servico]);
                        }
                    }
                }
            }

            Log::channel('main')->info('Novo site cadastrado.', [ 'cliente' => $siteOrcamento->Orcamento->Cliente, 'orcamento' => $siteOrcamento->Orcamento, 'site' => $site, 'user' => auth()->user()->nome]);
        });

        // if($dados['cadastrar_mais']) {
        //     return redirect()->route('siteOrcamento.create', ['orcamento' => $site->Orcamento]);
        // }
        return Inertia::location(route('orcamento.show', $siteOrcamento->Orcamento));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_sheet(SiteSheetRequest $request, Orcamento $orcamento)
    {
        $dados = $request->validated();
        $arquivo = $dados['sites_sheet'];
        
        try {
            $sites = Excel::import(new SiteOrcamentoImport($orcamento, $dados['colunas']), $arquivo);
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
    public function storel2l(SiteOrcamentoRequest $request)
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

            return redirect()->route('siteOrcamento.create.l2l', ['orcamento' => $site->Orcamento, 'botao' => $botao, 'isPontaA' => $isPontaA]);
        } else if($qtdeRestantePontasA == 1){
            if($site->Orcamento->quantidade_sites > $quantidadeRealSitesNormais){ $botao = 'Finalizar cadastro de pontas A ('.$site->Orcamento->quantidade_sites.' sites restantes)'; }
            if($site->Orcamento->quantidade_sites == $quantidadeRealSitesNormais){ $botao = 'Finalizar cadastro de pontas A'; }
            $isPontaA = true;

            return redirect()->route('siteOrcamento.create.l2l', ['orcamento' => $site->Orcamento, 'botao' => $botao, 'isPontaA' => $isPontaA]);
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

            return redirect()->route('siteOrcamento.create.l2l', ['orcamento' => $site->Orcamento, 'botao' => $botao]);
        } else if($qtdeRestante == -1){ // se for -1, significa que o user cadastra pelo botão adicionar site.
            $site->Orcamento->quantidade_sites += 1;
            $site->Orcamento->save();

            return redirect()->route('orcamento.show', $site->Orcamento);
        } else{ // se a qtdeRestante > 1, vai mandando pra rota create que uma hora fica == 1
            return redirect()->route('siteOrcamento.create.l2l', ['orcamento' => $site->Orcamento, 'botao' => $botao]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteOrcamento $siteOrcamento)
    {
        $cidades = Cidade::all()->map(function ($c) {
            return [
                'id' => $c->id,
                'nome' => $c->nome . " - " . $c->Estado->uf,
            ];
        })->toArray();
        $servicos = Servico::all()->map(function ($s) {
            return [
                'id' => $s->id,
                'nome' => $s->nome,
            ];
        })->toArray();
        $orcamento = $siteOrcamento->Orcamento->load('Cliente');
        
        return Inertia::render('SiteForm', [
            'cidades' => $cidades,
            'servicos' => $servicos,
            'orcamento' => $orcamento,
            'siteOrcamento' => $siteOrcamento->load(['Site', 'servicosSolicitados']),
            'pontas' => $siteOrcamento->pontas->load(['Site', 'servicosSolicitados']),
        ]);        
        // $cidades = Cidade::all();
        // return view('site.edit', ['site' => $site, 'cidades' => $cidades, 'servicos' => Servico::all()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SiteOrcamentoRequest $request, SiteOrcamento $siteOrcamento)
    {
        $dados = $request->validated();

        DB::transaction(function() use($dados, &$siteOrcamento){
            $siteOrcamento->Site->update($dados);

            $dados['site_id'] = $siteOrcamento->Site->id;
            $siteOrcamento->update($dados);

            if(!empty($dados['servicos'])) {
                SiteOrcamentoServico::where('site_orcamento_id', $siteOrcamento->id)->delete();
                foreach($dados['servicos'] as $servico){
                    SiteOrcamentoServico::create(['site_orcamento_id' => $siteOrcamento->id, 'servico_id' => !empty($servico['id']) ? $servico['id'] : $servico]);
                }
            }
            
            if(!empty($dados['pontas'])) {
                $idsPontas = [];
                foreach($dados['pontas'] as $ponta) {
                    if(!empty($ponta['site_id']) && !empty($ponta['site_orcamento_id'])) {
                        $sitePonta = Site::find($ponta['site_id']);
                        $siteOrcamentoPonta = SiteOrcamento::find($ponta['site_orcamento_id']);
                        $ponta['site_orcamento_id'] = $siteOrcamento->id;
                        $sitePonta->update($ponta);
                        $siteOrcamentoPonta->update($ponta);
                        if(!empty($ponta['servicos'])) {
                            SiteOrcamentoServico::where('site_orcamento_id', $siteOrcamentoPonta->id)->delete();
                            foreach($ponta['servicos'] as $servico){
                                SiteOrcamentoServico::create(['site_orcamento_id' => $siteOrcamentoPonta->id, 'servico_id' => !empty($servico['id']) ? $servico['id'] : $servico]);
                            }
                        }
                    } else {
                        $sitePonta = Site::create($ponta);
                        $ponta['site_id'] = $sitePonta->id;
                        $ponta['site_orcamento_id'] = $siteOrcamento->id;
                        $ponta['orcamento_id'] = $dados['orcamento_id'];
                        $siteOrcamentoPonta = SiteOrcamento::create($ponta);
                        if(!empty($ponta['servicos'])) {
                            foreach($ponta['servicos'] as $servico){
                                SiteOrcamentoServico::create(['site_orcamento_id' => $siteOrcamentoPonta->id, 'servico_id' => $servico]);
                            }
                        }
                    }
                    $idsPontas[] = $siteOrcamentoPonta->id;
                }
                $siteOrcamento->pontas()->whereNotIn('id', $idsPontas)->delete();
            }

            Log::channel('main')->info('Novo site cadastrado.', [ 'cliente' => $siteOrcamento->Orcamento->Cliente, 'orcamento' => $siteOrcamento->Orcamento, 'site' => $siteOrcamento, 'user' => auth()->user()->nome]);
        });

        // if($dados['cadastrar_mais']) {
        //     return redirect()->route('siteOrcamento.create', ['orcamento' => $site->Orcamento]);
        // }
        return Inertia::location(route('orcamento.show', $siteOrcamento->Orcamento));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SiteOrcamento $siteOrcamento)
    {
        $this->middleware('permission:excluir site');
        
        DB::transaction(function() use($siteOrcamento, &$orcamento){
            $siteOparaLog = clone $siteOrcamento;
            SiteOrcamentoServico::where('site_orcamento_id', $siteOrcamento->id)->delete();
            $orcamento = clone $siteOrcamento->Orcamento;
            foreach($siteOrcamento->cotacoes as $cotacao){
                $cotacao->delete();
            }
            foreach($siteOrcamento->pontas as $ponta){
                $ponta->delete();
            }
            $siteOrcamento->Orcamento()->dissociate();
            $siteOrcamento->delete();
            $orcamento->save();

            Log::channel('main')->info('SiteOrcamento excluido.', ['siteOrcamento' => $siteOparaLog, 'user' => auth()->user()->nome]);
        });
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
