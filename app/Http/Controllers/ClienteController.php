<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\ClienteRequest;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('cliente.index', ['clientes' => $clientes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cliente.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClienteRequest $request)
    {
        $cliente = Cliente::create($request->validated());
        Log::channel('main')->info('Novo cliente cadastrado', ['cliente' => $cliente, 'user' => auth()->user()->nome]);
        return redirect()->route('cliente.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $orcamentos = Orcamento::where('cliente_id', $cliente->id)->get();
        return view('cliente.show', ['cliente' => $cliente, 'orcamentos' => $orcamentos]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('cliente.edit', ['cliente' => $cliente]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $antigo = $cliente->getOriginal();

        $cliente->update($request->validated());

        // Comparar os valores antigos com os novos valores após a atualização
        $valores_alterados = [];
        foreach ($antigo as $key => $valor) {
            if ($valor != $cliente->{$key}) {
                $valores_alterados[$key] = [
                    'antigo' => $valor,
                    'novo' => $cliente->{$key},
                ];
            }
        }

        // Verificar se houve alterações e registrar no log
        if (!empty($valores_alterados)) {
            Log::channel('main')->info('Cadastro de cliente alterado', [
                'cliente' => $cliente->nome,
                'user' => auth()->user()->nome,
                'valores_alterados' => $valores_alterados,
            ]);
        }

        return redirect()->route('cliente.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $this->middleware('permission:excluir cliente');
        $cliente->delete(); // tá excluindo todos os orçamentos, pois coloquei ->onDelete('cascade'); na migration
        Log::channel('main')->info('Cliente excluído', ['cliente' => $cliente, 'user' => auth()->user()->nome]);
        return redirect()->route('cliente.index');
    }

    
    public function getClientes(Request $request)
    {
        $nome = $request->input('nome');

        $clientes = Cliente::where('nome', 'like', '%' . $nome . '%')
            ->select('id', 'nome', 'email')
            ->limit(10)
            ->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'nomeDisplay' => $c->nome . (!empty($c->email) ? " | " . $c->email : ""),
            ];
        })->toArray();

        return response()->json($clientes);
    }
}
