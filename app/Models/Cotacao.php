<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cotacoes';

    protected $fillable = [
        'fornecedor_id',
        'site_orcamento_id',
        'vel_down',
        'custo_ativacao',
        'tecnologia',
        'adesao_fornecedor',
        'custo_operacional',
        'mensal_fornecedor',
        'mensal_imp',
        'custo_instalacao_imp',
        'prazo_instalacao',
        'prazo_instalacao_fornecedor',
        'imposto_mensal',
        'imposto_adesao',
        'lucro_adesao',
        'lucro_liquido',
        'status',
        'vel_up',
        'barra',
        'custo_fixo',
    ];

    const fibra = 1;
    const radio = 2;

    public static function getTecnologia(): array
    {
        return [
            self::fibra,
            self::radio,
        ];
    }

    public static function getTecnologiaTexto(int $tipo): string
    {
        $dados = [
            self::fibra => 'Fibra',
            self::radio => 'Rádio',
        ];
        return $dados[$tipo];
    }
    
    const em_aberto = 1;
    const fechado = 2;
    
    public static function getStatus(): array
    {
        return [
            self::em_aberto,
            self::fechado,
        ];
    }

    public static function getStatusTexto(int $tipo): string
    {
        $dados = [
            self::em_aberto => 'Em aberto',
            self::fechado => 'Fechado',
        ];
        return $dados[$tipo];
    }

    public function SiteOrcamento(){
        return $this->belongsTo('App\Models\SiteOrcamento', 'site_orcamento_id');
    }

    public function Fornecedor(){
        return $this->belongsTo('App\Models\Fornecedor', 'fornecedor_id');
    }
    
    public function servicos()
    {
        return $this->belongsToMany('App\Models\Servico', 'cotacao_servico', 'cotacao_id', 'servico_id')->withPivot('id', 'vel_down', 'adesao', 'mensalidade', 'obs', 'vel_up', 'barra');
    }
}
