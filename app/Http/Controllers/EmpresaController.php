<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Requests\EmpresaRequest;
use App\Http\Requests\EmpresaParamsRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresa = auth()->user()->Empresa ?? null;
        if($empresa){
            return redirect()->route('empresa.show', ['empresa' => $empresa]);
        } else{
            return redirect()->route('empresa.create');
        }
    }

    public function create()
    {
        return view('empresa.create');
    }

    public function store(EmpresaRequest $request)
    {
        DB::transaction(function() use($request, &$empresa){
            $empresa = Empresa::create($request->validated());
            auth()->user()->update(['empresa_id' => $empresa->id]);
            Log::channel('main')->info('Empresa cadastrada.', [ 'empresa' => $empresa, 'user' => auth()->user()->nome]);
        });

        return redirect()->route('empresa.show', ['empresa' => $empresa])->with('success', 'Operação realizada com sucesso!');
    }

    public function show(Empresa $empresa)
    {
        return view('empresa.show', ['empresa' => $empresa, 'colaboradores' => Admin::where('empresa_id', $empresa->id)->get()]);
    }

    public function edit(Empresa $empresa)
    {
        return view('empresa.edit', ['empresa' => $empresa]);
    }
    
    public function update(EmpresaRequest $request, Empresa $empresa)
    {
        DB::transaction(function() use($request, &$empresa){
            $empresa->update($request->validated());
            Log::channel('main')->info('Empresa atualizada.', [ 'empresa' => $empresa, 'user' => auth()->user()->nome]);
        });

        return redirect()->route('empresa.show', ['empresa' => $empresa])->with('success', 'Operação realizada com sucesso!');
    }

    public function updateParams(EmpresaParamsRequest $request, Empresa $empresa)
    {
        DB::transaction(function() use($request, &$empresa){
            $dados = $request->validated();
            $empresa->update([
                'gear_noc' => $dados['gear_noc'],
                'custo_fixo_percent' => $dados['custo_fixo_percent']
            ]);
            Log::channel('main')->info('Parâmetros da Empresa alterados.', [ 'empresa' => $empresa, 'user' => auth()->user()->nome]);
        });

        return redirect()->back()->with('success', 'Parâmetros atualizados com sucesso!');
    }
}
