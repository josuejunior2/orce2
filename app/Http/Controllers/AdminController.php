<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Fornecedor;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $request)
    {
        $dados = $request->validated();
        $dados['password'] = !empty($request->input('password')) ? Hash::make($request->input('password')) : Hash::make(explode(' ', $dados['nome'])[0]."@2025");
        $admin = Admin::create($dados);

        return redirect()->route('empresa.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dados = Admin::find($id);

        return view('admin.show', ['colaborador' => $dados]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('admin.edit', ['colaborador' => $admin]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminRequest $request, Admin $admin)
    {
        $dados = $request->validated();
        $admin->update([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'telefone' => $dados['telefone'],
            'empresa_id' => $dados['empresa_id']
        ]);
        if(!empty($request->input('password'))){
            $admin->update([
                'password' => Hash::make($request->input('password')), // atualiza a senha
            ]);
        }

        return redirect()->route('empresa.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('empresa.index');
    }
}
