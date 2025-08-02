<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteOrcamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'site_orcamento';

    protected $fillable = ['orcamento_id', 'site_id', 'site_orcamento_id', 'vel_solicitada_down', 'vel_solicitada_up', 'barra'];

    public function Site(){
        return $this->belongsTo('App\Models\Site');
    }

    public function Cidade(){
        return $this->belongsTo('App\Models\Cidade');
    }

    public function Orcamento(){
        return $this->belongsTo('App\Models\Orcamento', 'orcamento_id');
    }

    public function cotacoes(){
        return $this->hasMany('App\Models\Cotacao', 'site_orcamento_id'); // model e fk no model que aponta para o site
    }
    
    public function servicosSolicitados()
    {
        return $this->belongsToMany('App\Models\Servico', 'site_servico', 'site_orcamento_id', 'servico_id');
    }

    public function pontas()
    {
        return $this->hasMany(SiteOrcamento::class, 'site_orcamento_id');
    }

    public function SubEstacao()
    {
        return $this->hasOne(SiteOrcamento::class, 'site_orcamento_id');
    }
}
