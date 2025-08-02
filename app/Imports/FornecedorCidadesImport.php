<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Fornecedor;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\FornecedorCidade;

class FornecedorCidadesImport implements ToCollection
{
    protected $fornecedorID;

    public function __construct(string $fornecedorID){
        $this->fornecedorID = $fornecedorID;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $estado = Estado::where('uf', $row[1])->first();
            $cidade = Cidade::where('nome', $row[0])->where('estado_id', $estado->id)->firstOr(function () use($row, $estado) {
                return Cidade::create([
                    'nome' => $row[0],
                    'cidade_id' => Cidade::all()->max('cidade_id') + 1,
                    'estado_id' => $estado->id,
                ]);
            });
            $fc = FornecedorCidade::firstOrCreate([
                'fornecedor_id' => $this->fornecedorID,
                'cidade_id' => $cidade->id,
            ]);
        }
    }
}
