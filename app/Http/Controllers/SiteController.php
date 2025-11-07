<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\Site;
use App\Models\Cidade;
use App\Models\Orcamento;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SearchSitesRequest;
use App\Http\Requests\SiteImportRequest;
use App\Http\Requests\SiteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Imports\SiteImport;

class SiteController extends Controller
{
    public function index()
    {
        $cidades = Cidade::all()->map(function ($c) {
            return [
                'id' => $c->id,
                'nome' => $c->nome . " - " . $c->Estado->uf,
            ];
        })->toArray();
        $sites = Site::with('sitesOrcamento', 'Cidade')->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'nome' => $s->nome,
                'cidade' => $s->Cidade->nome,
                'estado_uf' => $s->Cidade->Estado->uf,
                'endereco' => $s->endereco,
                'latitude' => $s->latitude,
                'longitude' => $s->longitude,
            ];
        })->toArray();
        return Inertia::render('SiteIndex', [
            'sites' => $sites,
            'cidades' => $cidades,
        ]);
    }

    public function table(SearchSitesRequest $request)
    {
        $dados = $request->validated();
        $dados['perPage'] = $dados['perPage'] ?? 10;

        $query = Site::with('Cidade.Estado', 'sitesOrcamento.Orcamento.Cliente');

        if (!empty($dados['search'])) {
            $query->where('nome', 'like', "%{$dados['search']}%")
                ->orWhereHas('cidade', function ($q) use ($dados) {
                    $q->where('nome', 'like', "%{$dados['search']}%");
                });
        }

        if (!empty($dados['sortBy']) && isset($dados['sortBy'][0]['key'])) {
            $direction = $dados['sortBy'][0]['order'] ?? 'asc';
            $query->orderBy($dados['sortBy'][0]['key'], $direction);
        }

        $paginator = $query->paginate($dados['perPage']);

        return response()->json([
            'items' => $paginator->items(),
            'total' => $paginator->total(),
            'next_id' => (Site::max('id') ?? 0) + 1,
        ]);
    }

    public function storeFromTable(SiteRequest $request)
    {
        $dados = $request->validated();
        
        DB::transaction(function() use($dados){
            $site = Site::create($dados);

            Log::channel('main')->info('Novo site cadastrado.', ['site' => $site, 'user' => auth()->user()->nome]);
        });

        return back()->with(['success' => 'Site cadastrado com sucesso!']);
    }

    public function updateFromTable(SiteRequest $request, Site $site)
    {
        $dados = $request->validated();
        
        DB::transaction(function() use($dados, $site){
            $site->update($dados);

            Log::channel('main')->info('Site atualizado.', ['site' => $site, 'user' => auth()->user()->nome]);
        });

        return back()->with(['success' => 'Site atualizado com sucesso!']);
    }

    public function destroyFromTable(Site $site)
    {
        DB::transaction(function() use($site){
            $site->delete();

            Log::channel('main')->info('Site excluido.', ['site' => $site, 'user' => auth()->user()->nome]);
        });

        return back()->with(['success' => 'Site excluído com sucesso!']);
    }

    public function indexImport()
    {
        return Inertia::render('SiteImport');
    }

    public function storeImport(SiteImportRequest $request)
    {
        $dados = $request->validated();
        $arquivo = $dados['sites_sheet'];
        $dados['orcamento_id'] = $dados['orcamento_id'] ?? '';

        if(!empty($dados['novo_orcamento'])) {
            DB::transaction(function() use(&$dados){
                $dados['novo_orcamento']['gear_noc'] = auth()->user()->Empresa->gear_noc;
                $dados['novo_orcamento']['custo_fixo_percent'] = auth()->user()->Empresa->custo_fixo_percent;                
                $orcamento = Orcamento::create($dados['novo_orcamento']);
                $dados['orcamento_id'] = $orcamento->id;
                Log::channel('main')->info('Novo Orcamento cadastrado pela importacao de sites.', ['orcamento' => $orcamento, 'user' => auth()->user()->nome]);
            });
        }

        try {
            $sites = Excel::import(new SiteImport($dados['orcamento_id'], $dados['colunas']), $arquivo);
        } catch (\Exception $e) {
            Log::channel('main')->error($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
        $nomeOriginal = $arquivo->getClientOriginalName();

        $arquivo->move('uploads', $nomeOriginal);
        
        return back()->with('success', 'Importação realizada com sucesso!');
    }
}
