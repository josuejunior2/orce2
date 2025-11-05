<?php

namespace App\Imports;

use App\Models\Site;
use App\Models\SiteOrcamento;
use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SiteImport implements ToCollection, SkipsEmptyRows
{
    use Importable;

    protected $colunas;
    protected $cidades;
    protected $estados;
    protected $orcamentoId;

    public function __construct($orcamentoId, $colunas)
    {
        $this->colunas = $colunas;
        $this->orcamentoId = $orcamentoId;
        $this->cidades = Cidade::all();
        $this->estados = Estado::all();
    }
    
    // public function prepareForValidation($data)
    // {
    //     if(str_contains($data['latitude'], ' ')) $data['latitude'] = str_replace(" ", "", $data['latitude']);
    //     if(str_contains($data['longitude'], ' ')) $data['longitude'] = str_replace(" ", "", $data['longitude']);
        
    //     return $data;
    // }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $errors = '';

        foreach ($rows as $row) {           
            if($row->filter()->isNotEmpty()){
                $dados = [];
                foreach($this->colunas as $key => $value) {
                    $dados[$value] = $row[$key];
                }

                if(array_key_exists('uf', $dados)){
                    $estado = $this->estados->where('uf', $dados['uf'])->first();
                }

                $cidade = null;
                if(array_key_exists('cidade', $dados)){
                    if(array_key_exists('uf', $dados) && !empty($estado)){
                        try{
                            $cidade = Cidade::where('nome', $dados['cidade'])->where('estado_id', $estado->id)->firstOr(function () use($dados, $estado) {
                                return Cidade::create([
                                    'nome' => $dados['cidade'],
                                    'cidade_id' => $this->cidades->max('cidade_id') + 1,
                                    'estado_id' => $estado->id,
                                ]);
                            });
                        } catch(\Exception $e) {
                            $errors .= $e->getMessage();
                        }
                    } else {
                        $cidade = Cidade::where('nome', $dados['cidade'])->first();
                    }
                    $dados['cidade'] = $cidade->id;
                }

                if(array_key_exists('latitude', $dados)){
                    if(str_contains($dados['latitude'], ' ')) {
                        $dados['latitude'] = str_replace(" ", "", $dados['latitude']);
                    }
                    if(str_contains($dados['latitude'], '\'')) {
                        $dados['latitude'] = explode('\'', $dados['latitude'])[1];
                    }
                }

                if(array_key_exists('longitude', $dados)){
                    if(str_contains($dados['longitude'], ' ')) {
                        $dados['longitude'] = str_replace(" ", "", $dados['longitude']);
                    }
                    if(str_contains($dados['longitude'], '\'')) {
                        $dados['longitude'] = explode('\'', $dados['longitude'])[1];
                    }
                }
                

                DB::transaction(function () use ($cidade, $dados) {
                    $site = Site::updateOrCreate(
                        [
                            'id_instalacao' => $dados['id_instalacao'],
                            'nome'          => $dados['nome'],
                            'cidade_id'     => $dados['cidade'],
                        ],
                        [
                            'latitude'  => $dados['latitude'],
                            'longitude' => $dados['longitude'],
                            'endereco'  => $dados['endereco'] ?? null,
                        ]);

                    if(!empty($this->orcamentoId)) {
                        SiteOrcamento::create([
                            'orcamento_id'          =>      $this->orcamentoId,
                            'site_id'               =>      $site->id,
                            'vel_solicitada_down'   =>      !empty($dados['vel_solicitada_down']) ? $dados['vel_solicitada_down'] : '',
                            'vel_solicitada_up'     =>      !empty($dados['vel_solicitada_up']) ? $dados['vel_solicitada_up'] : '',
                            'barra'                 =>      !empty($dados['barra']) ? $dados['barra'] : '',
                        ]);
                    }
                });
            }
            
        }
        if (!empty($errors)) {
            throw new \Exception($errors);
        }
    }
}
