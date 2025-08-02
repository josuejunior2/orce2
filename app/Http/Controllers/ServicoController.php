<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use App\Models\SiteOrcamentoServico;
use App\Models\Site;
use App\Models\Orcamento;
use Illuminate\Http\Request;
use App\Http\Requests\StoreServicoRequest;
use Illuminate\Support\Facades\Log;

class ServicoController extends Controller
{
    public function index()
    {
        return view('servico.index', ['servicos' => Servico::all()]);
    }

    public function create()
    {
        return view('servico.create');
    }

    public function store(StoreServicoRequest $request)
    {
        $servico = Servico::create($request->validated());
        Log::channel('main')->info('Novo servico cadastrado', ['servico' => $servico, 'user' => auth()->user()->nome]);
        return redirect()->route('servico.index');
    }

    public function edit(Servico $servico)
    {
        return view('servico.edit', ['servico' => $servico]);
    }

    public function update(StoreServicoRequest $request, Servico $servico)
    {
        $servico->update($request->validated());
        Log::channel('main')->info('Serviço atualizado', ['servico' => $servico, 'user' => auth()->user()->nome]);
        return redirect()->route('servico.index');
    }

    public function destroy(Servico $servico)
    {
        Log::channel('main')->info('Serviço excluído', ['servico' => $servico, 'user' => auth()->user()->nome]);
        $servico->delete();
        return redirect()->route('servico.index');
    }
    
    public function attach_sites(Request $request, Orcamento $orcamento)
    {   
        $sites = Site::whereIn('id', explode(",", $request->sites))->get();
        foreach($sites as $site){
            $servicos = [];
            foreach(explode(",", $request->servicos) as $servico){
                $servicos[] = SiteOrcamentoServico::firstOrCreate([
                    'servico_id' => $servico,
                    'site_id' => $site->id
                ]);
            }
            if(isset($request->reset)) SiteOrcamentoServico::where('site_id', $site->id)->whereNotIn('servico_id', array_column($servicos, 'servico_id'))->delete();
        }
        return redirect()->route('orcamento.show', ['orcamento' => $orcamento])->with(['success' => 'Operação realizada com sucesso!']);
    }
    
    public function detach_site(Request $request, Orcamento $orcamento)
    {   
        SiteOrcamentoServico::where([
            'servico_id' => $request->servico,
            'site_id' => $request->site
        ])->delete();
        return response()->json([]);
    }
}
