<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotacaoServico extends Model
{
    use HasFactory;

    protected $table = 'cotacao_servico';

    protected $fillable = ['cotacao_id', 'servico_id', 'vel_down', 'adesao', 'mensalidade', 'obs', 'vel_up', 'barra'];
}
