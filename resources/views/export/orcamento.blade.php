<table>
    <thead>
        <tr>
            <th><b>Título do Orçamento</b></th>
            <th><b>Cliente</b></th>
            <th><b>Tempo do Contrato</b></th>
            <th><b>Tipo do link</b></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $orcamento->titulo }}</td>
            <td>{{ $orcamento->Cliente->nome }}</td>
            <td>{{ $orcamento->tempo_contrato }}</td>
            <td>{{ ucfirst($orcamento->tipo_link) }}</td>
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th><b>Nome do Projeto</b></th>
            <th><b>Produto</b></th>
            <th><b>Velocidade (down/up)</b></th>
            <th><b>% Banda garantida</b></th>
            <th><b>Range Ip público</b></th>
            <th><b>Tipo</b></th>
            <th><b>Prazo de Contratação</b></th>
            <th><b>Endereço Completo</b></th>
            <th><b>Cidade</b></th>
            <th><b>UF</b></th>
            <th><b>CEP</b></th>
            <th><b>Latitude</b></th>
            <th><b>Longitude</b></th>
            <th><b>Localização (Link Mapa)</b></th>
            <th><b>Atende (Sim/Não)</b></th>
            <th><b>Valor Mensal c/ Impostos</b></th>
            <th><b>Valor Instalação c/ Impostos</b></th>
            <th><b>Prazo P/ Ativação</b></th>
            <th><b>Cpe</b> </th>    
        </tr>
    </thead>
    <tbody>
        @foreach ($orcamento->sitesOrcamento as $site)
            @foreach ($site->cotacoes as $cotacao)
                @foreach ($cotacao->servicos as $index => $servico)
                    <tr>
                        @if ($index === 0)
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ $site->nome }}</td>
                        @endif

                        {{-- Aqui o nome do serviço --}}
                        <td>{{ $servico->nome }}</td>

                        @if ($index === 0)
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ $site->vel_solicitada_down }} / {{ $site->vel_solicitada_up }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}"></td>
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ $site->barra }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ ucfirst($cotacao->tecnologia) }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}"></td>
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ $site->endereco }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ $site->Cidade->nome }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ $site->Cidade->Estado->uf }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}"></td>
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ $site->latitude }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ $site->longitude }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}"></td>
                            <td rowspan="{{ count($cotacao->servicos) }}"></td>
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ number_format($cotacao->mensal_imp, 2, ',', '.') }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ number_format($cotacao->custo_instalacao_imp, 2, ',', '.') }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}">{{ $cotacao->prazo_instalacao }}</td>
                            <td rowspan="{{ count($cotacao->servicos) }}"></td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>