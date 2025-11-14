<?php

namespace App\Services;


class CoordService
{
    /**
     * Retorna um array com o id do site, nome, latitude e longitude
     */
    public function toDecimalForView($sites)
    {
        $coordDecimal = [];

        $sites->each(function ($item, $i) use (&$coordDecimal) {
            $coordDecimal[$i]['id'] = $item->id;
            $coordDecimal[$i]['nome'] = $item->Site->nome;
            $coordDecimal[$i]['latitude'] = $this->convertToDecimal($item->Site->latitude);
            $coordDecimal[$i]['longitude'] = $this->convertToDecimal($item->Site->longitude);
        });

        return $coordDecimal;
    }
    
    public function convertToDecimal($coord)
    {
        $coord = trim($coord);
        $degrees = $minutes = $seconds = 0;
        $direction = '';

        // Caso decimal simples (sem ° ou ')
        if (!str_contains($coord, "°") && !str_contains($coord, "'")) {
            if (str_contains($coord, ",")) {
                // substitui vírgula por ponto decimal
                return number_format((float)str_replace(',', '.', $coord), 7, '.', '');
            } else {
                return number_format((float)$coord, 7, '.', '');
            }
        }

        $remainder = '';

        if (str_contains($coord, "°")) {
            [$degrees, $remainder] = explode("°", $coord, 2);
            $degrees = (float)str_replace(',', '.', trim($degrees));
        }

        if (str_contains($remainder, "'")) {
            [$minutes, $remainder] = explode("'", $remainder, 2);
            $minutes = (float)str_replace(',', '.', trim($minutes));
        }

        if (str_contains($remainder, "\"")) {
            [$seconds, $direction] = explode("\"", $remainder, 2);
            $seconds = (float)str_replace(',', '.', trim($seconds));
            $direction = trim($direction);
        } else {
            $direction = trim($remainder); // Caso sem segundos, o resto é a direção
            $seconds = 0;
        }

        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);

        // Sinal negativo para Sul (S) e Oeste (O ou W)
        if (str_contains(strtoupper($direction), "S") || str_contains(strtoupper($direction), "O") || str_contains(strtoupper($direction), "W")) {
            $decimal = -$decimal;
        }

        return number_format($decimal, 7, '.', '');
    }

}