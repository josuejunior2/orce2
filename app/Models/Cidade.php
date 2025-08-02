<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['estado_id', 'nome', 'cidade_id'];

    public function Estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function fornecedores_cidade(){
        return $this->hasMany('App\Models\FornecedorCidade', 'cidade_id');
    }
}
