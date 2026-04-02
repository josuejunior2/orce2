<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\SiteOrcamento;
use App\Models\Servico;
use App\Models\Orcamento;
use App\Models\Cidade;
use Illuminate\Http\Request;
use App\Http\Requests\SiteOrcamentoRequest;
use App\Http\Requests\SiteOrcamentoFromTableRequest;
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

    public function downloadModeloPlanilha()
    {
        $filePath = public_path('files/modelo_importacao_sites.xlsx');
        return Response::download($filePath);
    }

    public function storeFromTable(SiteOrcamentoFromTableRequest $request)
    {
        dd($request->validated());
    }
}
