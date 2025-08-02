@extends('layouts.admin')

@section('content')
    <div class="card m-3">
        <div class="card-header">
            <h3 class="card-title">Orcamento de {{ $cotacao->Site->Orcamento->Cliente->nome }}</h3>
        </div>
        <div class="card-body">
            <div class="datagrid">
                {{-- <div class="datagrid-item">
            <div class="datagrid-title">Razão Social do Cliente</div>
            <div class="datagrid-content">{{ $site->Orcamento->Cliente->nome }}</div>
        </div> --}}
                <div class="datagrid-item">
                    <div class="datagrid-title">Velocidade</div>
                    <div class="datagrid-content">{{ $cotacao->vel_down }} -@if ($cotacao->tecnologia == 'fibra')
                            Fibra óptica
                        @elseif($cotacao->tecnologia == 'radio')
                            Rádio
                        @elseif($cotacao->tecnologia == 'satelital')
                            Satelital
                        @endif
                    </div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Tempo do contrato</div>
                    <div class="datagrid-content">{{ $cotacao->Site->Orcamento->tempo_contrato }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Tipo de link</div>
                    <div class="datagrid-content">
                        @if ($cotacao->Site->Orcamento->tipo_link == 'l2l')
                            LAN to LAN (L2L)
                        @elseif ($cotacao->Site->Orcamento->tipo_link == 'ld')
                            Link Dedicado
                        @endif
                    </div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Imposto</div>
                    <div class="datagrid-content">{{ $cotacao->Site->Orcamento->imposto }}%</div>
                </div>
            </div>
        </div>
    </div>
    
    @include('site.partials.card-site', ['site' => $cotacao->Site])

    <div class="card m-3">
        <div class="card-header">
            <h3 class="card-title">Atualizar cotação do fornecedor</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('cotacao.update', ['cotacao' => $cotacao]) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="site_id" id="site_id" value="{{ $cotacao->Site->id }}">
                <div class="row g-3 mb-4">
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-form-label required">Fornecedor</label>
                            <select class="form-select" name="fornecedor_id" id="fornecedor_id">
                                <option value=""> -- Selecione o Fornecedor -- </option>
                                @foreach ($fornecedores as $f)
                                    <option value="{{ $f->id }}"
                                        {{ $cotacao->Fornecedor->id == $f->id ? 'selected' : '' }}>
                                        {{ $f->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-hint">
                                Caso o fornecedor não esteja cadastrado ainda, <a
                                    href="{{ route('fornecedor.create') }}">clique aqui</a>
                            </small>
                            <span class="text-danger" id="fornecedor_id-error"></span>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-form-label">Velocidade</label>
                            <div class="col input-group mb-2">
                                <span class="input-group-text">
                                    Down
                                </span>
                                <input type="number" min="0" name="vel_down" id="vel_down" placeholder="Mbps" class="form-control" autocomplete="off" value="{{ intval($cotacao->vel_down) }}" />
                                <span class="text-danger" id="vel_down-error"></span>
                                <span class="input-group-text">
                                    Up
                                </span>
                                <input type="number" min="0" name="vel_up" id="vel_up" placeholder="Mbps" class="form-control" autocomplete="off" value="{{ intval($cotacao->vel_up) }}"  />
                                <span class="input-group-text">/</span>
                                <input type="number" min="00" max="32" name="barra" id="barra" class="form-control" autocomplete="off" value="{{ $cotacao->barra }}"/>
                                <span class="text-danger" id="vel_up-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-form-label required">Tecnologia</label>
                            <div>
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tecnologia" id="tecnologia"
                                        value="fibra" {{ $cotacao->tecnologia == 'fibra' ? 'checked' : '' }}>
                                    <span class="form-check-label">Fibra óptica</span>
                                </label>
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tecnologia" id="tecnologia"
                                        value="radio" {{ $cotacao->tecnologia == 'radio' ? 'checked' : '' }}>
                                    <span class="form-check-label">Rádio</span>
                                </label>
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tecnologia" id="tecnologia"
                                        value="satelital" {{ $cotacao->tecnologia == 'satelital' ? 'checked' : '' }}>
                                    <span class="form-check-label">Satelital</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-form-label required">Prazo de instalação do Fornecedor</label>
                            <div class="col">
                                <input type="text" class="form-control" name="prazo_instalacao_fornecedor"
                                    id="prazo_instalacao_fornecedor" value="{{ $cotacao->prazo_instalacao_fornecedor }}">
                                <span class="text-danger" id="prazo_instalacao_fornecedor-error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="card servicos mb-3">
                        <div class="card-header justify-content-between p-1">
                            <h3 class="card-title">Serviços adicionais</h3>
                            <button type="button" class="btn btn-info" id="add-servico">
                                <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                            </button>
                        </div>
                        @foreach ($cotacao->servicos as $key => $servico)
                            <div class="card-body row servico-item">
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label class="col-3 col-form-label required" for="servico[{{$key}}][servico_id]">Serviço</label>
                                        <div class="col">
                                            <select class="form-select" type="text" name="servicos[{{$key}}][servico_id]" id="servico{{$key}}servico_id">
                                                <option value=""></option>
                                                @foreach($servicos as $s)
                                                    <option value="{{ $s->id }}" @if($servico->id == $s->id) selected @endif>
                                                        {{ $s->nome }}  @if(in_array($s->id, $cotacao->Site->servicosSolicitados->pluck('id')->toArray())) (Solicitado) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label class="col-form-label" for="servicos[{{$key}}][vel_down]">Velocidade do serviço</label>
                                        <div class="col input-group mb-2">
                                            <span class="input-group-text">Down</span>
                                            <input type="number" min="0" name="servicos[{{$key}}][vel_down]" placeholder="Mbps" id="servico{{$key}}vel_down" class="form-control" autocomplete="off" value="{{ intval($servico->pivot->vel_down) }}" />
                                            <span class="input-group-text">Up</span>
                                            <input type="number" min="0" name="servicos[{{$key}}][vel_up]" placeholder="Mbps" id="servico{{$key}}vel_up" class="form-control" autocomplete="off" value="{{ intval($servico->pivot->vel_up) }}" />
                                            <span class="input-group-text">/</span>
                                            <input type="number" min="00" max="32" name="servicos[{{$key}}][barra]" placeholder="Mbps" id="servico{{$key}}barra" class="form-control" autocomplete="off" value="{{ intval($servico->pivot->barra) }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label class="col-form-label" for="servicos[{{$key}}][adesao]">Adesão do serviço</label>
                                        <div class="col input-group mb-2">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" step="any" class="form-control servico_adesao" name="servicos[{{$key}}][adesao]" id="servico{{$key}}adesao" value="{{ $servico->pivot->adesao }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label class="col-form-label" for="servicos[{{$key}}][mensalidade]">Mensalidade do serviço</label>
                                        <div class="col input-group mb-2">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" step="any" class="form-control servico_mensalidade" name="servicos[{{$key}}][mensalidade]" id="servico{{$key}}mensalidade" value="{{ $servico->pivot->mensalidade }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label class="col-form-label" for="servicos[{{$key}}][obs]">Observação</label>
                                        <div class="col">
                                            <input type="text" class="form-control" name="servicos[{{$key}}][obs]" id="servico{{$key}}obs" value="{{ $servico->pivot->obs }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col text-center" style="display: contents">
                                    <button type="button" class="btn btn-danger h-50 remove-servico align-self-center"><svg  xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-minus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /></svg></button>
                                </div>
                            </div>                            
                        @endforeach
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-form-label required">Adesão do Fornecedor</label>
                            <div class="col input-group mb-2">
                                <span class="input-group-text">
                                    R$
                                </span>
                                <input type="number" step="any" class="form-control" name="adesao_fornecedor"
                                    id="adesao_fornecedor" value="{{ $cotacao->adesao_fornecedor }}">
                                <button type="button" class="btn somar" onclick="somarAdesaoServicos()" style="display: none">Somar serviços</button>
                                <span class="text-danger" id="adesao_fornecedor-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-form-label required">Mensalidade do fornecedor</label>
                            <div class="col input-group mb-2">
                                <span class="input-group-text">
                                    R$
                                </span>
                                <input type="number" step="any" class="form-control" name="mensal_fornecedor"
                                    id="mensal_fornecedor" value="{{ $cotacao->mensal_fornecedor }}">
                                <button type="button" class="btn somar" onclick="somarMensalServicos()" style="display: none">Somar serviços</button>
                                <span class="text-danger" id="mensal_fornecedor-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-form-label required">Custo de ativação</label>
                            <div class="col input-group mb-2">
                                <span class="input-group-text">
                                    R$
                                </span>
                                <input type="number" step="any" class="form-control" name="custo_ativacao"
                                    id="custo_ativacao" value="{{ $cotacao->custo_ativacao }}">
                                <span class="text-danger" id="custo_ativacao-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-form-label required">Status da cotação</label>
                            <select class="form-select" name="status" id="status" value="{{ $cotacao->status }}">
                                <option value=""></option>
                                <option class="badge bg-yellow text-white" value="Em aberto"
                                    {{ $cotacao->status == 'Em aberto' ? 'selected' : '' }}>Em aberto</option>
                                <option class="badge bg-green text-white" value="Fechado"
                                    {{ $cotacao->status == 'Fechado' ? 'selected' : '' }}>Fechado</option>
                            </select>
                            <span class="text-danger" id="status-error"></span>
                        </div>
                    </div>
                </div>
                @can('precificar orcamento')
                    <div class="d-flex justify-content-around align-items-center">
                        <div class="row g-3 d-flex justify-content-around">
                            <div class="col-md">
                                <div class="mb-3">
                                    <label class="col-form-label">Cobrança mensal com imposto</label>
                                    <div class="col input-group mb-2">
                                        <span class="input-group-text">
                                            R$
                                        </span>
                                        <input type="number" step="any" class="form-control" name="mensal_imp"
                                            id="mensal_imp" value="{{ $cotacao->mensal_imp }}">
                                        <span class="text-danger" id="mensal_imp-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-3">
                                    <label class="col-form-label">Custo instalação c/ imposto</label>
                                    <div class="col input-group mb-2">
                                        <span class="input-group-text">
                                            R$
                                        </span>
                                        <input type="number" step="any" class="form-control"
                                            name="custo_instalacao_imp" id="custo_instalacao_imp"
                                            value="{{ $cotacao->custo_instalacao_imp }}">
                                        <span class="text-danger" id="custo_instalacao_imp-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-3">
                                    <label class="col-form-label">Prazo de instalação</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="prazo_instalacao"
                                            id="prazo_instalacao" value="{{ $cotacao->prazo_instalacao }}">
                                        <span class="text-danger" id="prazo_instalacao-error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="mb-3">
                    <label class="form-label">Imposto Mensal</label>
                    <div class="col input-group mb-2">
                        <span class="input-group-text">
                            R$
                        </span>
                        <input id="imposto_mensal" type="number" step="any" class="form-control" name="imposto_mensal"
                            value="{{ $cotacao->imposto_mensal }}" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Imposto Adesão</label>
                    <div class="col input-group mb-2">
                        <span class="input-group-text">
                            R$
                        </span>
                        <input id="imposto_adesao" type="number" step="any" class="form-control" name="imposto_adesao"
                            value="{{ $cotacao->imposto_adesao }}" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Custo Operacional</label>
                    <div class="col input-group mb-2">
                        <span class="input-group-text">
                            R$
                        </span>
                        <input id="custo_operacional" type="number" step="any" class="form-control" name="custo_operacional"
                            value="{{ $cotacao->custo_operacional }}" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Custo Fixo</label>
                    <div class="col input-group mb-2">
                        <span class="input-group-text">
                            R$
                        </span>
                        <input id="custo_fixo" type="number" step="any" class="form-control" name="custo_fixo"
                            value="{{ $cotacao->custo_fixo }}" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lucro Adesão</label>
                    <div class="col input-group mb-2">
                        <span class="input-group-text">
                            R$
                        </span>
                        <input id="lucro_adesao" type="number" step="any" class="form-control" name="lucro_adesao"
                            value="{{ $cotacao->lucro_adesao }}" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lucro Líquido</label>
                    <div class="col input-group mb-2">
                        <span class="input-group-text">
                            R$
                        </span>
                        <input id="lucro_liquido" type="number" step="any" class="form-control" name="lucro_liquido"
                            value="{{ $cotacao->lucro_liquido }}" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-plus" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        <path d="M12 11l0 6" />
                        <path d="M9 14l6 0" />
                    </svg>
                    Atualizar
                </button>
            </div>
        @else
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-plus" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <path d="M12 11l0 6" />
                    <path d="M9 14l6 0" />
                </svg>
                Atualizar
            </button>
        </div>
    @endcan
    </form>
    </div>
    </div>
    </div>
@endsection


@section('js')
    <script type="text/template" id="servico-template">
        <div class="card-body row servico-item">
            <div class="col-md">
                <div class="mb-3">
                    <label class="col-3 col-form-label required" for="servico[#][servico_id]">Serviço</label>
                    <div class="col">
                        <select class="form-select" type="text" name="servicos[#][servico_id]" id="servico#servico_id">
                            <option value=""></option>
                            @foreach($servicos as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->nome }}  @if(in_array($s->id, $cotacao->Site->servicosSolicitados->pluck('id')->toArray())) (Solicitado) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> 
            </div>
            <div class="col-md">
                <div class="mb-3">
                    <label class="col-form-label" for="servicos[#][vel_down]">Velocidade do serviço</label>
                    <div class="col input-group mb-2">
                        <span class="input-group-text">Down</span>
                        <input type="number" min="0" name="servicos[#][vel_down]" placeholder="Mbps" id="servico#vel_down" class="form-control" autocomplete="off" value="" />
                        <span class="input-group-text">Up</span>
                        <input type="number" min="0" name="servicos[#][vel_up]" placeholder="Mbps" id="servico#vel_up" class="form-control" autocomplete="off" value="" />
                        <span class="input-group-text">/</span>
                        <input type="number" min="00" max="32" name="servicos[#][barra]" id="servico#barra" class="form-control" autocomplete="off" />
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="mb-3">
                    <label class="col-form-label" for="servicos[#][adesao]">Adesão do serviço</label>
                    <div class="col input-group mb-2">
                        <span class="input-group-text">R$</span>
                        <input type="number" step="any" class="form-control servico_adesao" name="servicos[#][adesao]" id="servico#adesao" value="" />
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="mb-3">
                    <label class="col-form-label" for="servicos[#][mensalidade]">Mensalidade do serviço</label>
                    <div class="col input-group mb-2">
                        <span class="input-group-text">R$</span>
                        <input type="number" step="any" class="form-control servico_mensalidade" name="servicos[#][mensalidade]" id="servico#mensalidade" value="" />
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="mb-3">
                    <label class="col-form-label" for="servicos[#][obs]">Observação</label>
                    <div class="col">
                        <input type="text" class="form-control" name="servicos[#][obs]" id="servico#obs" value="" />
                    </div>
                </div>
            </div>
            <div class="col text-center" style="display: contents">
                <button type="button" class="btn btn-danger h-50 remove-servico align-self-center"><svg  xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-minus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /></svg></button>
            </div>
        </div>
    </script>
    <script>
        $(document).ready(function() {
            var gear_noc = "{{ $site->Orcamento->gear_noc ?? auth()->user()->Empresa->gear_noc }}";
            var custo_fixo_percent = "{{ $site->Orcamento->custo_fixo_percent ?? auth()->user()->Empresa->custo_fixo_percent }}";
            $('#mensal_fornecedor, #custo_operacional, #mensal_imp, #adesao_fornecedor, #custo_ativacao, #custo_instalacao_imp')
                .on('input', function() {
                    // Obtenha os valores dos campos
                    var mensal_forn = $('#mensal_fornecedor').val();
                    var adesao_forn = $('#adesao_fornecedor').val();
                    var custo_operacional = $('#custo_operacional').val();
                    var mensal_imp = $('#mensal_imp').val();
                    var porcent = "{{ $cotacao->Site->Orcamento->imposto }}";
                    var imposto_mensal = mensal_imp * (porcent / 100);
                    var custo_inst_imp = $('#custo_instalacao_imp').val();
                    var custo_ativacao = $('#custo_ativacao').val();

                    var imposto_adesao = custo_inst_imp * (porcent / 100);

                    var custo_operacional = Number(mensal_forn) + Number(gear_noc);

                    var custo_fixo = mensal_imp * (custo_fixo_percent / 100);

                    var lucro_liquido = mensal_imp - custo_fixo - custo_operacional - imposto_mensal;

                    $('#lucro_liquido').val(lucro_liquido.toFixed(2));
                    $('#imposto_mensal').val(imposto_mensal.toFixed(2));
                    $('#imposto_adesao').val(imposto_adesao.toFixed(2));
                    var lucro_adesao = $('#custo_instalacao_imp').val() - $('#adesao_fornecedor').val() -
                        imposto_adesao;
                    $('#lucro_adesao').val(lucro_adesao.toFixed(2));
                    $('#custo_operacional').val(Number(custo_operacional).toFixed(2));
                    $('#custo_fixo').val(Number(custo_fixo).toFixed(2));
                });

            

            var qtd_servico = "{{ $cotacao->servicos->count() }}";
            if(qtd_servico > 0) $(".somar").show();
            $('#add-servico').on('click', function() {
                var template = $($('#servico-template').html().replaceAll('#', qtd_servico));
                $('.servicos').append(template);
                qtd_servico++;
                $(".somar").show();
            });

            $(document).on('click', '.remove-servico', function() {
                $(this).closest('.servico-item').remove();
                if ($('.servicos').find('.servico-item').length === 0) $(".somar").hide();
            });

            $('#formCotacao').on('submit', function (e) {
                e.preventDefault();

                $('input').removeClass('is-invalid');
                $('.text-danger').text('');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        window.location.href = response.redirect_url;
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                $(`[name="${field}"]`).addClass('is-invalid');
                                $(`#${field}-error`).text(errors[field][0]);
                            }
                        } else {
                            alert('Ocorreu um erro inesperado. Por favor, tente novamente.');
                            console.error(xhr);
                        }
                    }
                });
            });
        });

        function somarAdesaoServicos() {
            $("#adesao_fornecedor").val('');
            let soma = 0;
            $(".servico_adesao").each(function () {
                const valor = parseFloat($(this).val()) || 0;
                soma += valor;
            });
            $("#adesao_fornecedor").val(soma.toFixed(2));
        }

        function somarMensalServicos() {
            $("#mensal_fornecedor").val('');
            let soma = 0;
            $(".servico_mensalidade").each(function () {
                const valor = parseFloat($(this).val()) || 0;
                soma += valor;
            });
            $("#mensal_fornecedor").val(soma.toFixed(2));
        }
    </script>
@endsection
