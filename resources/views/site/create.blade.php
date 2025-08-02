@extends('layouts.admin')

@if (session('error'))
    <div class="alert alert-danger mt-1">
        {{ session('error') }}
    </div>
@endif

@include('site.modal.mapa')

@section('content')
<div class="card m-3">
    <div class="card-header">
        <h3 class="card-title">Cadastro de Site no orçamento {{ $orcamento->titulo }}</h3>
    </div>
    <div class="card-body mb-3">
        <div class="datagrid">
        <div class="datagrid-item">
            <div class="datagrid-title">Razão Social do Cliente</div>
            <div class="datagrid-content">{{ $orcamento->Cliente->nome }}</div>
        </div>
        <div class="datagrid-item">
            <div class="datagrid-title">Quantidade de sites</div>
            <div class="datagrid-content">{{ $orcamento->quantidade_sites }}</div>
        </div>
        <div class="datagrid-item">
            <div class="datagrid-title">Tempo do contrato</div>
            <div class="datagrid-content">{{ $orcamento->tempo_contrato }}</div>
        </div>
        <div class="datagrid-item">
            <div class="datagrid-title">Tipo de link</div>
            <div class="datagrid-content">
                @if($orcamento->tipo_link == 'l2l')
                    LAN to LAN (L2L)
                @elseif ($orcamento->tipo_link == 'ld')
                    Link Dedicado
                @endif
            </div>
        </div>
        <div class="datagrid-item">
            <div class="datagrid-title">Imposto</div>
            <div class="datagrid-content">{{ $orcamento->imposto }}%</div>
        </div>
    </div>
</div>
</div>

@if ($orcamento->sitesOrcamento->isNotEmpty())
<div class="card m-3" style="height: 14rem">
    <div class="card-body card-body-scrollable card-body-scrollable-shadow">
    @foreach ($orcamento->sitesOrcamento as $os)
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">{{ $os->nome }}</h3>
        </div>
        <div class="card-body">
            <div class="datagrid">
                <div class="datagrid-item">
                    <div class="datagrid-title">Latitude</div>
                    <div class="datagrid-content">{{ $os->latitude }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Longitude</div>
                    <div class="datagrid-content">{{ $os->longitude }}</div>
                </div>
                @if(!empty($os->Cidade))
                    <div class="datagrid-item">
                        <div class="datagrid-title">Cidade</div>
                        <div class="datagrid-content">{{ $os->Cidade->nome }}</div>
                    </div>
                @endif
                <div class="datagrid-item">
                    <div class="datagrid-title">Endereço detalhado</div>
                    <div class="datagrid-content">{{ $os->endereco }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    </div>
</div>
@endif
<div class="card m-3">
    <div class="card-body">
        <form method="POST" action="{{ route('site.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="orcamento_id" id="orcamento_id" value="{{ $orcamento->id }}">
            <input type="hidden" name="cadastrar_mais" id="cadastrar_mais" value="0">
            <div class="row g-3 mb-4">
                <div class="col-md">
                    <div class="mb-3">
                        <label class="col-form-label required">Nome do Site</label>
                        <div class="col">
                            <input type="text" class="form-control" name="nome" id="nome" value="{{ old('nome', '') }}">
                            <span class="{{ $errors->has('nome') ? 'text-danger' : '' }}">
                                {{ $errors->has('nome') ? $errors->first('nome') : '' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="mb-3">
                        <label class="col-form-label">Latitude e longitude</label>
                        <div class="row g-2">
                            <div class="col">
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <input type="text" class="form-control" id="coord" value="{{ old('latitude', '') }}, {{ old('longitude', '') }}" autocomplete="off">
                                <span class="{{ $errors->has('latitude') ? 'text-danger' : '' }}">
                                    {{ $errors->has('latitude') ? $errors->first('latitude') : '' }}
                                </span>
                                <span class="{{ $errors->has('longitude') ? 'text-danger' : '' }}">
                                    {{ $errors->has('longitude') ? $errors->first('longitude') : '' }}
                                </span>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#modal-full-width">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                </a>
                            </div>
                            <p id="result"></p>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md">
                    <div class="mb-3">
                        <label class="col-form-label required">Latitude</label>
                        <div class="col">
                            <input type="text" class="form-control" name="latitude" id="latitude" value="{{ old('latitude', '') }}" autocomplete="off">
                            <span class="{{ $errors->has('latitude') ? 'text-danger' : '' }}">
                                {{ $errors->has('latitude') ? $errors->first('latitude') : '' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="mb-3">
                        <label class="col-form-label required">Longitude</label>
                        <div class="col">
                            <input type="text" class="form-control" name="longitude" id="longitude" value="{{ old('longitude', '') }}" autocomplete="off">
                            <span class="{{ $errors->has('longitude') ? 'text-danger' : '' }}">
                                {{ $errors->has('longitude') ? $errors->first('longitude') : '' }}
                            </span>
                        </div>
                    </div>
                </div> --}}
                <div class="col-md">
                    <div class="mb-3">
                        <label class="col-3 col-form-label required">Cidade</label>
                        <div class="col">
                            <select class="form-select" type="text" name="cidade_id" id="cidade_id">
                                <option value="">-- Selecione uma cidade --</option>
                                @foreach($cidades as $c)
                                    <option value="{{ $c->id }}" {{ old('cidade_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->nome }} - {{ $c->Estado->uf }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="{{ $errors->has('cidade_id') ? 'text-danger' : '' }}">
                                {{ $errors->has('cidade_id') ? $errors->first('cidade_id') : '' }}
                            </span>
                            @if (session('error'))
                                <div class="alert alert-danger mt-1">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md">
                    <div class="mb-3">
                        <label class="col-3 col-form-label">Endereço detalhado</label>
                        <div class="col">
                            <input type="text" class="form-control" name="endereco" id="endereco" value="{{ old('endereco', '') }}">
                            <span class="{{ $errors->has('endereco') ? 'text-danger' : '' }}">
                                {{ $errors->has('endereco') ? $errors->first('endereco') : '' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="col-3 col-form-label" for="servicos">Serviços</label>
                    <div class="col">
                        <input type="text" name="servicos" id="servicos" multiple></input>
                    </div>
                </div> 
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label class="col-form-label">Velocidade solicitada</label>
                        <div class="col input-group mb-2">
                            <span class="input-group-text">
                                Down
                            </span>
                            <input type="number" min="0" name="vel_solicitada_down" id="vel_solicitada_down" placeholder="Mbps" class="form-control" autocomplete="off" value="{{ old('vel_solicitada_down', '') }}"/>
                            <span class="input-group-text">
                                Up
                            </span>
                            <input type="number" min="0" name="vel_solicitada_up" id="vel_solicitada_up" placeholder="Mbps" class="form-control" autocomplete="off" value="{{ old('vel_solicitada_up', '') }}"/>
                            <span class="input-group-text">/</span>
                            <input type="number" min="00" max="32" name="barra" id="barra" class="form-control" autocomplete="off" value="{{ old('barra', '') }}"/>
                            <span class="{{ $errors->has('vel_solicitada_down') ? 'text-danger' : '' }}">
                                {{ $errors->has('vel_solicitada_down') ? $errors->first('vel_solicitada_down') : '' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
    </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                Salvar
            </button>
            <button type="submit" class="btn btn-outline-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11l0 6" /><path d="M9 14l6 0" /></svg>
                Cadastrar outro Site
            </button>
        </div>
        </form>
</div>
@endsection

@section('js')
    @include('site.partials.script')
@endsection