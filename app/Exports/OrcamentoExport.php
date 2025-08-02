<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\Cotacao;

class OrcamentoExport implements FromView
{
    protected $orcamento;

    public function __construct($orcamento)
    {
        $this->orcamento = $orcamento;
    }

    public function view(): View
    {
        return view('export.orcamento', [
            'orcamento' => $this->orcamento
        ]);
    }
}
