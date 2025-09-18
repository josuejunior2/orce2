<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SearchSitesRequest;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
            'sites' => $sites
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function table(SearchSitesRequest $request)
    {
        $dados = $request->validated();
        $dados['perPage'] = $dados['perPage'] ?? 10;

        $query = Site::with('Cidade.Estado', 'sitesOrcamento');

        // filtro
        if (!empty($dados['search'])) {
            $query->where('nome', 'like', "%{$dados['search']}%")
                ->orWhereHas('cidade', function ($q) use ($dados) {
                    $q->where('nome', 'like', "%{$dados['search']}%");
                });
        }

        // ordenação
        if (!empty($dados['sortBy']) && isset($dados['sortBy'][0]['key'])) {
            $direction = $dados['sortBy'][0]['order'] ?? 'asc';
            $query->orderBy($dados['sortBy'][0]['key'], $direction);
        }

        $paginator = $query->paginate($dados['perPage']);
// dd($paginator->items(), $request->all());
        return response()->json([
            'items' => $paginator->items(),
            'total' => $paginator->total(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        //
    }
}
