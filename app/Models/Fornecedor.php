<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Cidade;

class Fornecedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fornecedores';
    protected $fillable = ['nome', 'cnpj', 'telefone', 'email', 'representante'];

    public function cidades()
    {
        return $this->belongsToMany('App\Models\Cidade', 'fornecedor_cidade', 'fornecedor_id', 'cidade_id');
    }

    public function cotacoes()
    {
        return $this->hasMany('App\Models\Cotacao', 'fornecedor_id');
    }
}
