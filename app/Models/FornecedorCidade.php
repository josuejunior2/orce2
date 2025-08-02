<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FornecedorCidade extends Model
{
    use HasFactory;

    protected $table = 'fornecedor_cidade';
    protected $fillable = ['fornecedor_id', 'cidade_id'];

}
