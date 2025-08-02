<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orcamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['cliente_id', 'site_id',  'tempo_contrato', 'tipo_link', 'imposto', 'status', 'quantidade_sites', 'quantidade_pontasA', 'titulo', 'gear_noc', 'custo_fixo_percent', 'lucro_mensal_total', 'adesao_total'];

    const em_cotacao = 1;
    const enviado = 2;
    const aprovado = 3;
    const sem_viabilidade = 4;

    public static function getStatus(): array
    {
        return [
            self::em_cotacao,
            self::enviado,
            self::aprovado,
            self::sem_viabilidade,
        ];
    }

    public static function getStatusTexto(int $tipo): string
    {
        $dados = [
            self::em_cotacao => 'Em cotação',
            self::enviado => 'Enviado',
            self::aprovado => 'Aprovado',
            self::sem_viabilidade => 'Sem viabilidade',
        ];
        return $dados[$tipo];
    }

    public static function getStatusTextoECor(int $tipo): array
    {
        $dados = [
            self::em_cotacao => ['texto' => 'Em cotação', 'cor' => 'yellow'],
            self::enviado => ['texto' => 'Enviado', 'cor' => 'blue'],
            self::aprovado => ['texto' => 'Aprovado', 'cor' => 'green'],
            self::sem_viabilidade => ['texto' => 'Sem viabilidade', 'cor' => 'red'],
        ];
        return $dados[$tipo];
    }

    public function Cliente(){
        return $this->belongsTo('App\Models\Cliente', 'cliente_id'); // orcamento tem 1 cliente, ele olha a FK
    }

    public function Fornecedor(){
        return $this->belongsTo('App\Models\Fornecedor'); // orcamento tem 1 fornecedor, ele olha a FK
    }

    public function sitesOrcamento(){
        return $this->hasMany('App\Models\SiteOrcamento', 'orcamento_id');
    }

    public function tipoLinkTexto(){
        if ($this->tipo_link == 'ld') return 'Link Dedicado';
        if ($this->tipo_link == 'l2l') return 'Lan 2 Lan';
    }
}
