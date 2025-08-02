<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['cidade_id', 'nome', 'endereco', 'latitude', 'longitude'];
    
    public function sitesOrcamento(){
        return $this->hasMany('App\Models\SiteOrcamento', 'site_id');
    }
}
