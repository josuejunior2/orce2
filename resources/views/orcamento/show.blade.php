@extends('layouts.admin')

@include('orcamento.modal.alert-destroy-orcamento')
@include('orcamento.modal.create-site-sheet')
@include('orcamento.modal.attach-servicos')
@include('orcamento.modal.recalculate')
@section('content')

    <div class="card m-3 mb-1">
        @if (session('error'))
            <div class="alert alert-danger m-3">
                {{ session('error') }}
            </div>
        @endif
        <div class="card-header justify-content-between">
            <h3 class="card-title">@if(isset($orcamento->titulo)) {{ $orcamento->titulo }} @else Orcamento de {{ $orcamento->Cliente->nome }} @endif</h3>
            <div class="d-flex justify-content-between col-auto">
                <div>
                    <a class="btn btn-outline-success me-2" href="{{ route('export.orcamento', ['orcamento' => $orcamento->id]) }}" title="Exportar para excel">
                        <svg  xmlns="http://www.w3.org/2000/svg" width="18"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-spreadsheet m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                    </a>
                </div>
                <div class="me-2">
                    @if($orcamento->gear_noc != auth()->user()->Empresa->gear_noc || $orcamento->custo_fixo_percent != auth()->user()->Empresa->custo_fixo_percent)
                        <a href="#" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#modal-recalculate">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-alert-triangle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 1.67c.955 0 1.845 .467 2.39 1.247l.105 .16l8.114 13.548a2.914 2.914 0 0 1 -2.307 4.363l-.195 .008h-16.225a2.914 2.914 0 0 1 -2.582 -4.2l.099 -.185l8.11 -13.538a2.914 2.914 0 0 1 2.491 -1.403zm.01 13.33l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -7a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z" /></svg>
                            Gear Noc ou % Custo Fixo desatualizados!
                        </a>
                    @else
                        <a href="#" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#modal-recalculate">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-math-symbols"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12l18 0" /><path d="M12 3l0 18" /><path d="M16.5 4.5l3 3" /><path d="M19.5 4.5l-3 3" /><path d="M6 4l0 4" /><path d="M4 6l4 0" /><path d="M18 16l.01 0" /><path d="M18 20l.01 0" /><path d="M4 18l4 0" /></svg>
                            Recalcular
                        </a>
                    @endif
                </div>
                @if ($orcamento->tipo_link == 'l2l')
                    <div>
                        <a href="{{ route('site.create.+1.PontaA', ['orcamento' => $orcamento, 'isPontaA' => true]) }}" class="btn me-2 btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Ponta A
                        </a>
                    </div>
                @endif
                <div>
                    <a href="{{ route('site.create', ['orcamento' => $orcamento]) }}" class="btn me-2 btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Site
                    </a>
                </div>
                <div>
                    <a href="{{ route('orcamento.edit', ['orcamento' => $orcamento->id]) }}" class="btn me-2 btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18"  height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                            <path d="M13.5 6.5l4 4" />
                        </svg>
                    </a>
                </div>
                @can('excluir orcamento')
                    <div>
                        <form id="form_destroy_orcamento{{ $orcamento->id }}" method="post" action="{{ route('orcamento.destroy', ['orcamento' => $orcamento->id]) }}">
                            @method('DELETE')
                            @csrf
                            <a href="#" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modal-destroy-orcamento">
                                <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18"  height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </a>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
        {{-- 'cliente_id', 'site_id', 'velocidade', 'tempo_contrato', 'tecnologia', 'tipo_link', 'imposto', 'status']; --}}
        <div class="card-body">
            <div class="datagrid">
                <div class="datagrid-item">
                    <div class="datagrid-title">Razão Social do Cliente</div>
                    <div class="datagrid-content"><a href="{{ route('cliente.show', ['cliente' => $orcamento->Cliente]) }}">{{ $orcamento->Cliente->nome }}</a></div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Tempo do contrato</div>
                    <div class="datagrid-content">{{ $orcamento->tempo_contrato }} meses</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Tipo do link</div>
                    <div class="datagrid-content">
                        @if ($orcamento->tipo_link == 'l2l')
                            LAN to LAN (L2L) - {{ $orcamento->quantidade_pontasA }} Pontas A - {{ $orcamento->quantidade_sites }} sites
                        @elseif ($orcamento->tipo_link == 'ld')
                            Link Dedicado - {{ $orcamento->quantidade_sites }} sites
                        @endif
                    </div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Imposto</div>
                    <div class="datagrid-content">{{ $orcamento->imposto }}%</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Status</div>
                    <div class="datagrid-content">
                        @if ($orcamento->status == 'Sem viabilidade')
                        <span class="badge bg-red text-white">Sem viabilidade</span>
                        @elseif ($orcamento->status == 'Em cotação')
                        <span class="badge bg-yellow text-white">Em cotação</span>
                        @elseif ($orcamento->status == 'Enviado')
                        <span class="badge bg-blue text-white">Enviado</span>
                        @elseif ($orcamento->status == 'Aprovado')
                        <span class="badge bg-green text-white">Aprovado</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if($orcamento->lucro_mensal_total != 0)
            @can('precificar orcamento')
                <div class="card-footer">
                    <div class="card-table table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>Adesão total</th>
                                    <th>Lucro mensal total</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td><h2>R$ {{ number_format($orcamento->adesao_total, 2, ',', '.') }}</h2></td>
                                        <td><h2 class="@if($orcamento->lucro_mensal_total > 0) text-info @else text-danger @endif">R$ {{ number_format($orcamento->lucro_mensal_total, 2, ',', '.') }}</h2></td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endcan
        @endif
    </div>
    <div class="card m-3">
        <div class="card-header justify-content-between">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" style="background: unset">
                <li class="nav-item">
                    <a href="#tab-sites" class="nav-link active" data-bs-toggle="tab" onclick="$('.mapa').show();"><h3>Lista de sites</h3></a>
                </li>
                <li class="nav-item">
                    <a href="#tab-import" class="nav-link" data-bs-toggle="tab" onclick="$('.mapa').hide();"><h3>Importação</h3></a>
                </li>
            </ul>
            <h3 class="card-title"></h3>
            <div class="d-flex justify-content-between col-auto">
                @if ($orcamento->tipo_link == 'l2l')
                    <span class="form-help align-self-center me-2" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="<p>Caso deseje adicionar Pontas A, clique no botão 'Adicionar Ponta A' na parte superior.</p>" data-bs-html="true">?</span>
                @endif
                <a href="#" class="btn btn-secondary w-100 mb-1 me-2" data-bs-toggle="modal" data-bs-target="#modal-attach-servicos">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plug"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.785 6l8.215 8.215l-2.054 2.054a5.81 5.81 0 1 1 -8.215 -8.215l2.054 -2.054z" /><path d="M4 20l3.5 -3.5" /><path d="M15 4l-3.5 3.5" /><path d="M20 9l-3.5 3.5" /></svg>
                    Atribuir serviços
                </a>
                {{-- <a href="#" class="btn btn-success w-100 mb-1" data-bs-toggle="modal" data-bs-target="#modal-create-site-sheet">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-table-import"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21h-7a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8" /><path d="M3 10h18" /><path d="M10 3v18" /><path d="M19 22v-6" /><path d="M22 19l-3 -3l-3 3" /></svg>
                    Sites via planilha
                </a> --}}
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane active show" id="tab-sites">
                @if ($orcamento->sitesOrcamento->isEmpty())
                    <div class="form-control-plaintext m-3">Esse Orçamento não tem nenhum site.</div>
                @else
                <div class="accordion accordion-tabs" id="accordion-tabs">
                    @foreach ($orcamento->sitesOrcamento->sortBy('nome') as $key => $site) {{-- pq aí os que forem (Ponta A) vem primeiro --}}
                    @include('orcamento.modal.alert-destroy-site', ['site' => $site])
                    {{-- @include('orcamento.modal.create-cotacao-sheet', ['site' => $site]) --}}
                    <div class="accordion-item m-3 mb-0">
                        <div class="accordion-header d-flex justify-content-between align-items-center">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $site->id }}-tabs" aria-expanded="false">
                                {{ $site->nome }}
                            </button>
                            {{-- <div class="d-flex justify-content-between col-auto"> --}}
                                {{-- <div>
                                    <a href="#" class="btn btn-success w-100 mb-1" data-bs-toggle="modal" data-bs-target="#modal-create-cotacao-sheet">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-table-import"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21h-7a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8" /><path d="M3 10h18" /><path d="M10 3v18" /><path d="M19 22v-6" /><path d="M22 19l-3 -3l-3 3" /></svg>
                                        </svg>
                                        Adicionar cotações via planilha
                                    </a>
                                </div> --}}
                                <div class="btn-group pb-2 pe-2" role="group">
                                    <a href="{{ route('cotacao.create', ['site' => $site]) }}" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                        Cotação
                                    </a>
                                    <a href=" {{ route('site.edit', ['site' => $site->id]) }}" class="btn btn-outline-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18"  height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                            <path d="M13.5 6.5l4 4" />
                                        </svg>
                                    </a>
                                    @can('excluir site')
                                    <a href="#" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#modal-destroy-site-{{ $site->id }}">
                                        <form id="form_{{ $site->id }}" method="post" action="{{ route('site.destroy', ['site' => $site->id]) }}" class="m-0">
                                            @method('DELETE')
                                            @csrf
                                                <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18"  height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </form>
                                        </a>
                                    @endcan
                                </div>
                            {{-- </div> --}}
                        </div>
                        <div id="collapse-{{ $site->id }}-tabs" class="accordion-collapse collapse" data-bs-parent="#accordion-tabs">
                            <div class="accordion-body pt-0">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Endereço detalhado</div>
                                        <div class="datagrid-content">{{ $site->endereco }}</div>
                                    </div>
                                    @if(!empty($site->cidade_id))
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Cidade</div>
                                            <div class="datagrid-content">{{ $site->Cidade->nome }} - {{ $site->Cidade->Estado->uf }}</div>
                                        </div>
                                    @endif
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Latitude</div>
                                        <div class="datagrid-content">{{ $site->latitude }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Longitude</div>
                                        <div class="datagrid-content">{{ $site->longitude }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Velocidade solicitada</div>
                                        <div class="datagrid-content">
                                            <div class="btn p-1 pe-none user-select-all">{{ intval($site->vel_solicitada_down) }} <small class="form-hint">Mbps</small><span class="badge bg-blue ms-2 text-white user-select-all">Down</span></div>
                                            <div class="btn p-1 pe-none user-select-all">{{ intval($site->vel_solicitada_up) }} <small class="form-hint">Mbps</small><span class="badge bg-red ms-2 text-white user-select-all">Up</span></div>
                                            <div class="btn p-1 pe-none user-select-all">/{{ intval($site->barra) }}</div>
                                            {{-- Down: {{ intval($site->vel_solicitada_down) }} Mbps up: </div> --}}
                                        </div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Serviços solicitados</div>                                
                                        <div class="datagrid-content">
                                            @foreach($site->servicosSolicitados as $servico) 
                                                <span class="badge badge-outline" title="{{ $servico->descricao }}" role='button'
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" data-site="{{ $site->id }}" data-servico="{{ $servico->id }}" data-nome-servico="{{ $servico->nome }}" data-nome-site="{{ $site->nome }}" onclick="confirmaDetachServico(this)" onmouseover="$(this).addClass('text-danger')" onmouseout="$(this).removeClass('text-danger')">{{$servico->nome}}</span> 
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @if ($site->cotacoes->isNotEmpty())
                                    @foreach ($site->cotacoes as $cotacao)
                                    @include('orcamento.modal.edit-status-cotacao', ['cotacao' => $cotacao])
                                    @include('orcamento.modal.alert-destroy-cotacao', ['cotacao' => $cotacao])
                                        <div class="accordion accordion-tabs" id="accordion-{{ $cotacao->id }}">
                                            <div class="accordion-item m-3">
                                                <div class="accordion-header d-flex justify-content-between" id="heading-1">
                                                    <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-collapse-{{ $cotacao->id }}" aria-expanded="false">
                                                        {{ $cotacao->Fornecedor->nome }}
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <a href="#" class="btn-icon" data-bs-toggle="modal" data-bs-target="#modal-edit-status-cotacao-{{$cotacao->id}}">
                                                                    @if ($cotacao->status == 'Em aberto')
                                                                    <span class="badge bg-yellow text-white d-flex justify-content-center align-items-center ms-3">Em aberto<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil ms-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                                                    @elseif ($cotacao->status == 'Fechado')
                                                                    <span class="badge bg-green text-white d-flex justify-content-center align-items-center ms-3">Fechado<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil ms-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                                                    @endif
                                                                </a>
                                                            </div>
                                                    </button>
                                                    <div class="d-flex justify-content-between col-auto">
                                                        <div class="btn-group" role="group">
                                                            <a class="btn btn-outline-primary" href="{{ route('cotacao.edit', ['cotacao' => $cotacao->id]) }}">
                                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="18"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                            </a>
                                                            <a class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-destroy-cotacao-{{$cotacao->id}}">
                                                                <form id="form_{{ $cotacao->id }}" class="m-0" method="post" action="{{ route('cotacao.destroy', ['cotacao' => $cotacao->id]) }}">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <svg  xmlns="http://www.w3.org/2000/svg" width="18"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                                </form>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="accordion-collapse-{{ $cotacao->id }}" class="accordion-collapse collapse" data-bs-parent="#accordion-{{ $cotacao->id }}">
                                                    <div class="accordion-body pt-1">
                                                        <div class="row">
                                                            <div class="col-sm-1">
                                                                <div class="datagrid" style="--tblr-datagrid-item-width: 7rem; --tblr-datagrid-padding: 1rem;">                                                           
                                                                    <div class="datagrid-item">
                                                                        <div class="datagrid-title">Email</div>
                                                                        <div class="datagrid-content">
                                                                            <a href="mailto:{{ $cotacao->Fornecedor->email }}" class="text-reset">{{ $cotacao->Fornecedor->email }}</a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="datagrid-item">
                                                                        <div class="datagrid-title">Telefone</div>
                                                                        <div class="datagrid-content"><a target="_blank" href="https://wa.me/55{{ str_replace(['(', ')', ' ', '-'], '', $cotacao->Fornecedor->telefone) }}" class="text-primary"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-whatsapp"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>{{ $cotacao->Fornecedor->telefone }}</a></div>
                                                                    </div>
                                                                    <div class="datagrid-item">
                                                                        <div class="datagrid-title">Velocidade</div>
                                                                        <div class="datagrid-content">
                                                                            <div class="btn p-1 pe-none user-select-all">{{ intval($cotacao->vel_down) }} <small class="form-hint">Mbps</small><span class="badge bg-blue ms-2 text-white user-select-all">Down</span></div>
                                                                            <div class="btn p-1 pe-none user-select-all">{{ intval($cotacao->vel_up) }} <small class="form-hint">Mbps</small><span class="badge bg-red ms-2 text-white user-select-all">Up</span></div>
                                                                            <div class="btn p-1 pe-none user-select-all">/{{ intval($cotacao->barra) }}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="datagrid-item">
                                                                        <div class="datagrid-title">Tecnologia</div>
                                                                        <div class="datagrid-content">{{ $cotacao->tecnologia }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if(!empty($cotacao->servicos) && $cotacao->servicos->count() > 0)
                                                                <div class="col-sm-5">
                                                                    <div class="">
                                                                        <div class="card-table table-responsive">
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Serviço</th>
                                                                                        <th>Velocidade</th>
                                                                                        <th>Adesao</th>
                                                                                        <th>Mensalidade</th>
                                                                                        <th>Obs.</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($cotacao->servicos as $servico)
                                                                                        <tr>
                                                                                            <td>{{ $servico->nome }}</td>
                                                                                            <td>
                                                                                                <div class="btn p-1 pe-none user-select-all">{{ intval($servico->pivot->vel_down) }} <small class="form-hint">Mbps</small><span class="badge bg-blue ms-2 text-white user-select-all">Down</span></div>
                                                                                                <div class="btn p-1 pe-none user-select-all">{{ intval($servico->pivot->vel_up) }} <small class="form-hint">Mbps</small><span class="badge bg-red ms-2 text-white user-select-all">Up</span></div>
                                                                                                <div class="btn p-1 pe-none user-select-all">/{{ intval($servico->pivot->barra) }}</div>
                                                                                            </td>
                                                                                            <td>R$ {{ number_format($servico->pivot->adesao, 2, ',', '.') }}</td>
                                                                                            <td>R$ {{ number_format($servico->pivot->mensalidade, 2, ',', '.') }}</td>
                                                                                            <td>{{ $servico->pivot->obs }}</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="@if(!empty($cotacao->servicos) && $cotacao->servicos->count() > 0) col-sm-3 @else col-sm-6 @endif">
                                                                <div class="card-table table-responsive">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr><th colspan="3"><center>Preço do fornecedor SEM IMPOSTO</center></th></tr>
                                                                            <tr>
                                                                                <th>Adesão</th>
                                                                                <th>Mensalidade</th>
                                                                                <th>Prazo</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                                <tr>
                                                                                    <td>R$ {{ number_format($cotacao->adesao_fornecedor, 2, ',', '.') }}</td>
                                                                                    <td>R$ {{ number_format($cotacao->mensal_fornecedor, 2, ',', '.') }}</td>
                                                                                    <td>{{ $cotacao->prazo_instalacao_fornecedor }}</td>
                                                                                </tr>
                                                                        </tbody>
                                                                        @can('precificar orcamento')
                                                                            <thead>
                                                                                <tr><th colspan="3"><center>Impostos</center></th></tr>
                                                                                <tr>
                                                                                    <th>Imp. adesão</th>
                                                                                    <th>Mensalidade c/ imp.</th>
                                                                                    <th>Imp. mensal</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                    <tr>
                                                                                        <td>R$ {{ number_format($cotacao->imposto_adesao, 2, ',', '.') }}</td>
                                                                                        <td>R$ {{ number_format($cotacao->mensal_imp, 2, ',', '.') }}</td>
                                                                                        <td>R$ {{ number_format($cotacao->imposto_mensal, 2, ',', '.') }}</td>
                                                                                    </tr>
                                                                            </tbody>
                                                                        @endcan
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="@if(!empty($cotacao->servicos) && $cotacao->servicos->count() > 0) col-sm-3 @else col-sm-5 @endif">
                                                                <div class="card-table table-responsive">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th colspan="2"><center>Custos</center></th>
                                                                                @can('precificar orcamento') <th>Instalação</th> @endcan
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Ativação</th>
                                                                                <th>Operacional</th>
                                                                                @can('precificar orcamento')
                                                                                    <th>c/ Imposto</th>            
                                                                                @endcan
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                                <tr>
                                                                                    <td>R$ {{ number_format($cotacao->custo_ativacao, 2, ',', '.') }}</td>
                                                                                    <td>R$ {{ number_format($cotacao->custo_operacional, 2, ',', '.') }}</td>
                                                                                    @can('precificar orcamento')
                                                                                        <td>R$ {{ number_format($cotacao->custo_instalacao_imp, 2, ',', '.') }}</td>
                                                                                    @endcan
                                                                                </tr>
                                                                        </tbody>
                                                                        @can('precificar orcamento')
                                                                            <thead>
                                                                                <tr><th colspan="2"><center>Lucros</center></th><th>Prazo total</th></tr>
                                                                                <tr>
                                                                                    <th>Adesão</th>
                                                                                    <th>Líquido</th>
                                                                                    <th>Instalação</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                    <tr>
                                                                                        <td><h2 class="@if($cotacao->lucro_adesao > 0) text-info @else text-danger @endif mb-0">R$ {{ number_format($cotacao->lucro_adesao, 2, ',', '.') }}</h2></td>
                                                                                        <td><h2 class="@if($cotacao->lucro_liquido > 0) text-info @else text-danger @endif mb-0">R$ {{ number_format($cotacao->lucro_liquido, 2, ',', '.') }}</h2></td>
                                                                                        <td>{{ $cotacao->prazo_instalacao }}</td>
                                                                                    </tr>
                                                                            </tbody>
                                                                        @endcan
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
                @endif
            </div>
            <div class="tab-pane" id="tab-import">
                @include('orcamento.import-sites-sheet')
            </div>
        </div>

    </div>
    <div class="card m-3 mapa">
        <div class="ratio ratio-21x9">
            <gmp-map center="-15.779444,-47.929444" zoom="5" map-id="DEMO_MAP_ID">
                @foreach($coordenadasDecimal as $coord)
                <gmp-advanced-marker 
                    position="{{ $coord['latitude'] }}, {{ $coord['longitude'] }}" 
                    data-target="{{ $coord['id'] }}" 
                    class="d-inline-block tooltip-container" 
                    onmouseover="document.getElementById('nome-{{ $coord['id'] }}').classList.add('show');" 
                    onmouseout="document.getElementById('nome-{{ $coord['id'] }}').classList.remove('show');">
                    
                    <img class="flag-icon" src="https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png"/>
                    <div id="nome-{{ $coord['id'] }}" class="p-0 m-0 bg-light position-absolute shadow-sm slider" style="top: 50%; left: 100%; transform: translateY(-50%); margin-left: 10px; white-space: nowrap;">
                        <div class="card-status-start bg-success"></div>
                        <h2 class="m-1 ms-2">{{ $coord['nome'] }}</h2>
                    </div>
                </gmp-advanced-marker>
                @endforeach
              </gmp-map>
        </div>
    </div>
@endsection

@section('js')
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiQk-FBvoeK8j7hKpVayMETHx4nuh4fcg&loading=async&libraries=marker&v=beta&solution_channel=GMP_CCS_complexmarkers_v3"
      defer
    >
</script>
<script>    
    $(document).ready(function () {
        var el;
        var el2;
        window.TomSelect && (new TomSelect(el = document.getElementById('servicos'), {
            options: [
                @foreach($servicos as $servico)
                    {value: '{{ $servico->id }}', text: '{{ $servico->nome }}'},
                @endforeach
            ],
        }));

        let tomSites;
        window.TomSelect && (tomSites = new TomSelect(el2 = document.getElementById('sites'), {
            options: [
                @foreach($orcamento->sitesOrcamento as $site)
                    {value: '{{ $site->id }}', text: '{{ $site->nome }}'},
                @endforeach
            ],
        }));

        $("#btn-todos").on('click', function() {
            let allValues = Object.keys(tomSites.options);
            tomSites.setValue(allValues);
        });
    });
    function confirmaDetachServico(e) {
        var site = $(e).attr('data-site');
        var servico = $(e).attr('data-servico');
        var nomeSite = $(e).attr('data-nome-site');
        var nomeServico = $(e).attr('data-nome-servico');

        Swal.fire({
            title: `Deseja remover o serviço ${nomeServico} de ${nomeSite}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, confirmar!',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/servico/detach-site',
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        site: site,
                        servico: servico
                    },
                    success: function (response) {
                        $(e).addClass('d-none');
                        Swal.fire({
                            toast: true,                   
                            position: 'top-end',           
                            icon: 'success',                
                            title: 'Operação realizada com sucesso!',
                            showConfirmButton: false,     
                            timer: 3000,                 
                            timerProgressBar: true,      
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um problema ao realizar a ação.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@yield('js-add')
@endsection

@section('style')
<style>
    .tooltip-container .flag-icon {
        display: inline-block;
    }

    .slider {
        opacity: 0;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        transform: translateX(-10px) translateY(-50%);
    }

    .slider.show {
        opacity: 1;
        transform: translateX(0) translateY(-50%);
    }
</style>
@endsection