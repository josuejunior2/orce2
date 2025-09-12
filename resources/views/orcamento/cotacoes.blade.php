@foreach ($cotacoes as $cotacao)
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
                    <div class="btn-group">
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
                                    <div class="datagrid-content">{{ App\Models\Cotacao::getTecnologiaTexto($cotacao->tecnologia) }}</div>
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