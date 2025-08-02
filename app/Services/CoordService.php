<?php

namespace App\Services;


class CoordService
{
    /**
     * Retorna um array com o id do site, nome, latitude e longitude
     */
    public function toDecimalForView($sites)
    {
        $coordenadas = $sites->whereNotNull('latitude')->whereNotNull('longitude');

        $coordDecimal = [];

        $coordenadas->each(function ($item, $i) use (&$coordDecimal) {
            $convertToDecimal = function ($coord) {
                $degrees = $minutes = $seconds = 0;
                $direction = '';

                if(!str_contains($coord, "°") || !str_contains($coord, "'")){
                    if(str_contains($coord, ",")) return number_format((float) str_replace(',', '.', $coord), 7, '.', '');
                    else return number_format((float) $coord, 7, '.', '');
                }
                
                if (str_contains($coord, "°")) {
                    [$degrees, $remainder] = explode("°", $coord);
                }
                if (str_contains($remainder, "'")) {
                    [$minutes, $remainder] = explode("'", $remainder);
                }
                if (str_contains($remainder, "\"")) {
                    [$seconds, $direction] = explode("\"", $remainder);
                } else {
                    $direction = $remainder; // Para casos sem segundos, o restante é a direção
                }

                // Calcula a coordenada decimal
                $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
                // dd($degrees . '     ' . ($minutes / 60) . '     ' . ($seconds / 3600) . '       '. $coord     .  ' ==' . $decimal);

                // Ajusta o sinal de acordo com a direção (Sul ou Oeste)
                if (str_contains($direction, "S") || str_contains($direction, "O") || str_contains($direction, "W")) {
                    $decimal = -$decimal;
                }

                return number_format($decimal, 7, '.', '');
            };
        
            // Converte latitude e longitude
            $coordDecimal[$i]['id'] = $item['id'];
            $coordDecimal[$i]['nome'] = $item['nome'];
            $coordDecimal[$i]['latitude'] = $convertToDecimal($item['latitude']);
            $coordDecimal[$i]['longitude'] = $convertToDecimal($item['longitude']);
        });
        // dd($coordDecimal);
        return $coordDecimal;
    }
}