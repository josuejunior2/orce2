<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteOrcamentoServico extends Model
{
    use HasFactory;

    protected $table = "site_orcamento_servico";

    protected $fillable = ['site_orcamento_id', 'servico_id'];

    public function SiteOrcamento()
    {
        return $this->belongsTo(SiteOrcamento::class, 'site_orcamento_id');
    }
    
    public function Servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }
}
