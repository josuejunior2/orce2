@extends('layouts.admin')

@if (session('error'))
    <div class="alert alert-danger mt-1">
        {{ session('error') }}
    </div>
@endif

@include('site.modal.mapa')

@section('content')
<div class="card m-3">
    <div class="card-header justify-content-between">
        <h3 class="card-title">Orcamento de {{$site->Orcamento->Cliente->nome}}</h3>
    </div>
    {{--'cliente_id', 'site_id', 'velocidade', 'tempo_contrato', 'tecnologia', 'tipo_link', 'imposto', 'status'];--}}
    <div class="card-body">
        <div class="datagrid">
        <div class="datagrid-item">
            <div class="datagrid-title">Razão Social do Cliente</div>
            <div class="datagrid-content">{{ $site->Orcamento->Cliente->nome }}</div>
        </div>
        <div class="datagrid-item">
            <div class="datagrid-title">Tempo do contrato</div>
            <div class="datagrid-content">{{ $site->Orcamento->tempo_contrato }}</div>
        </div>
        <div class="datagrid-item">
            <div class="datagrid-title">Tipo do link</div>
            <div class="datagrid-content">
                @if($site->Orcamento->tipo_link == 'l2l')
                    LAN to LAN (L2L)
                @elseif ($site->Orcamento->tipo_link == 'ld')
                    Link Dedicado
                @endif
            </div>
        </div>
        <div class="datagrid-item">
            <div class="datagrid-title">Imposto</div>
            <div class="datagrid-content">{{ $site->Orcamento->imposto }}%</div>
        </div>
        <div class="datagrid-item">
            <div class="datagrid-title">Status</div>
            <div class="datagrid-content">
                @if ($site->Orcamento->status == 'Sem viabilidade')
                    <span class="badge bg-red text-white">Sem viabilidade</span>
                @elseif ($site->Orcamento->status == 'Em cotação')
                    <span class="badge bg-yellow text-white">Em cotação</span>
                @elseif ($site->Orcamento->status == 'Enviado')
                    <span class="badge bg-blue text-white">Enviado</span>
                @elseif ($site->Orcamento->status == 'Aprovado')
                    <span class="badge bg-green text-white">Aprovado</span>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
<div class="card m-3">
    <div class="card-header justify-content-between">
        <h3 class="card-title">Editar Site {{$site->nome}}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('siteOrcamento.update', ['site' => $site]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="orcamento_id" id="orcamento_id" value="{{ $site->Orcamento->id }}">
            <div class="row g-3 mb-4">
                <div class="col-md">
                    <div class="mb-3">
                        <label class="col-form-label required">Nome do Site</label>
                        <div class="col">
                            <input type="text" class="form-control" name="nome" id="nome" value="{{ $site->nome }}">
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
                                <input type="text" class="form-control" id="coord" value="{{ old('coord', $site->latitude . ', ' . $site->longitude) }}" autocomplete="off">
                                <span class="{{ $errors->has('coord') ? 'text-danger' : '' }}">
                                    {{ $errors->has('coord') ? $errors->first('coord') : '' }}
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
                <div class="col-md">
                    <div class="mb-3">
                        <label class="col-3 col-form-label required">Cidade</label>
                        <div class="col">
                            <select class="form-select" type="text" name="cidade_id" id="cidade_id">
                                <option value="">-- Selecione uma cidade --</option>
                                @foreach($cidades as $c)
                                    <option value="{{ $c->id }}" {{ !empty($site->cidade_id) && $site->Cidade->id == $c->id ? 'selected' : '' }}>
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
                        <label class="col-3 col-form-label required">Endereço detalhado</label>
                        <div class="col">
                            <input type="text" class="form-control" name="endereco" id="endereco" value="{{ $site->endereco }}">
                            <span class="{{ $errors->has('endereco') ? 'text-danger' : '' }}">
                                {{ $errors->has('endereco') ? $errors->first('endereco') : '' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="col-3 col-form-label" for="servicos">Serviços</label>
                    <div class="col">
                        <input type="text" name="servicos" id="servicos" multiple>
                        </input>
                    </div>
                </div> 
                <div class="col-sm-3">
                    <div class="mb-3">
                        <label class="col-form-label">Velocidade solicitada</label>
                        <div class="col input-group mb-2">
                            <span class="input-group-text">
                                Down
                            </span>
                            <input type="number" min="0" name="vel_solicitada_down" id="vel_solicitada_down" placeholder="Mbps" class="form-control" autocomplete="off" value="{{ old('vel_solicitada_down', intval($site->vel_solicitada_down)) }}"/>
                            <span class="input-group-text">
                                Up
                            </span>
                            <input type="number" min="0" name="vel_solicitada_up" id="vel_solicitada_up" placeholder="Mbps" class="form-control" autocomplete="off" value="{{ old('vel_solicitada_up', intval($site->vel_solicitada_up)) }}"/>
                            <span class="input-group-text">/</span>
                            <input type="number" min="00" max="32" name="barra" id="barra" class="form-control" autocomplete="off" value="{{ old('barra', $site->barra) }}"/>
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
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11l0 6" /><path d="M9 14l6 0" /></svg>
                Salvar
            </button>
        </form>
    </div>
</div>
@endsection


@section('js')
    @include('site.partials.script')
@endsection