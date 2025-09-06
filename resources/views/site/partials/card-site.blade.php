<div class="card m-3">
    <div class="card-header">
        <h3 class="card-title">{{ $siteOrcamento->Site->nome }}</h3>
    </div>
    <div class="card-body">
        <div class="datagrid">
            <div class="datagrid-item">
                <div class="datagrid-title">Latitude</div>
                <div class="datagrid-content">{{ $siteOrcamento->Site->latitude }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Longitude</div>
                <div class="datagrid-content">{{ $siteOrcamento->Site->longitude }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Cidade</div>
                <div class="datagrid-content">{{ $siteOrcamento->Site->Cidade->nome }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Endereço detalhado</div>
                <div class="datagrid-content">{{ $siteOrcamento->Site->endereco }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Velocidade solicitada</div>
                <div class="datagrid-content">
                    <div class="btn p-1 pe-none user-select-all">{{ intval($site->vel_solicitada_down) }} <small class="form-hint">Mbps</small><span class="badge bg-blue ms-2 text-white user-select-all">Down</span></div>
                    <div class="btn p-1 pe-none user-select-all">{{ intval($site->vel_solicitada_up) }} <small class="form-hint">Mbps</small><span class="badge bg-red ms-2 text-white user-select-all">Up</span></div>
                    <div class="btn p-1 pe-none user-select-all">/{{ intval($site->barra) }}</div>
                </div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Serviços solicitados</div>                                
                <div class="datagrid-content">
                    @foreach($siteOrcamento->servicosSolicitados as $servico) 
                        <span class="badge badge-outline" title="{{ $servico->descricao }}" data-bs-toggle="tooltip" data-bs-placement="bottom" data-site="{{ $siteOrcamento->id }}" data-servico="{{ $servico->id }}" data-nome-servico="{{ $servico->nome }}" data-nome-site="{{ $siteOrcamento->Site->nome }}" onmouseover="$(this).addClass('text-danger')" onmouseout="$(this).removeClass('text-danger')">{{$servico->nome}}</span> 
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>