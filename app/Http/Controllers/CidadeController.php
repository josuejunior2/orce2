<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Fornecedor;
use Illuminate\Http\Request;

class CidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fornecedores = Fornecedor::all();
        $cidades = Cidade::select('cidades.*')
                        ->join('fornecedor_cidade', 'fornecedor_cidade.cidade_id', '=', 'cidades.id')
                        ->distinct()
                        ->get();

        return view('cidade.index', ['cidades' => $cidades, 'fornecedores' => $fornecedores]);
    }
}
