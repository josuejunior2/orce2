@extends('layouts.admin')

@include('cliente.modal.alert-destroy')
@section('content')

<div class="card m-3">
    <div class="card-header justify-content-between">
        <h3 class="card-title">{{ $cliente->nome }}</h3>
        <div class="d-flex justify-content-between col-auto">
            <div>
                <a href=" {{ route('cliente.edit', ['cliente' => $cliente->id ]) }}" class="btn me-2 btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                </a>
            </div>
            @can('excluir cliente')
            <form id="form_{{$cliente->id}}" method="post" action="{{ route('cliente.destroy', ['cliente' => $cliente->id]) }}">
                @method('DELETE')
                @csrf
                <a href="#" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modal-destroy-cliente">
                    <svg xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                </a>
            </form>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="datagrid">
        <div class="datagrid-item">
            <div class="datagrid-title">Telefone</div>
            <div class="datagrid-content">{{ $cliente->telefone }}</div>
        </div>
        <div class="datagrid-item">
            <div class="datagrid-title">Email</div>
            <div class="datagrid-content">{{ $cliente->email }}</div>
        </div>
        <div class="datagrid-item">
            <div class="datagrid-title">Endereco</div>
            <div class="datagrid-content">{{ $cliente->endereco }}</div>
        </div>
    </div>
</div>
<div class="card m-3">
    <div class="card-header justify-content-between">
        <h3 class="card-title">Lista de Orçamentos</h3>
        <div>
            <a href="{{ route('orcamento.create') }}" class="btn btn-success w-100">
                Adicionar novo orçamento
            </a>
        </div>
    </div>
    @if ($cliente->orcamentos->isEmpty())
        <div class="form-control-plaintext m-3">Esse Cliente não solicitou nenhum orçamento. <a href="{{ route('orcamento.create') }}">Crie um orçamento.</a></div></div>
    @else
    <div class="datagrid">
        @foreach ($cliente->orcamentos as $orcamento)
        <div class="datagrid-item">
            <a href="{{ route('orcamento.show', ['orcamento' => $orcamento]) }}" class="card card-link card-link-pop m-2">
                <div class="card-body">({{ $orcamento->created_at->format('m/Y') }}) {{ $orcamento->tempo_contrato }} meses - {{ $orcamento->tipo_link }}
                @if ($orcamento->sitesOrcamento->isNotEmpty())
                     - {{ $orcamento->quantidade_sites }} sites
                    @php
                        $estadosExibidos = [];
                    @endphp
                    @foreach ($orcamento->sitesOrcamento as $site)
                        @if ($site->cotacoes->isNotEmpty())
                             em
                            @foreach ($site->cotacoes as $cotacao)
                                @foreach ($cotacao->Fornecedor->cidades as $c)
                                    @if (!in_array($c->Estado->uf, $estadosExibidos))
                                        {{ $c->Estado->uf }}
                                        @php
                                            $estadosExibidos[] = $c->Estado->uf;
                                        @endphp
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    @endforeach
                @endif
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
            </a>
        </div>
    @endforeach
    @endif
</div>
@endsection
