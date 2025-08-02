@extends('layouts.admin')

@section('content')
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">
                            Home
                        </div>
                        <h2 class="page-title">
                            ORCE v. alpha - Norte Conexão
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl m-3">
                <div class="row row-deck row-cards">
                    <div class="col-12">
                        <div class="row row-cards">
                            <div class="col-md-6 col-xl-3">
                                <a class="card card-link" href="{{ route('cliente.index') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-heart-handshake" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                                    <path
                                                        d="M12 6l-3.293 3.293a1 1 0 0 0 0 1.414l.543 .543c.69 .69 1.81 .69 2.5 0l1 -1a3.182 3.182 0 0 1 4.5 0l2.25 2.25" />
                                                    <path d="M12.5 15.5l2 2" />
                                                    <path d="M15 13l2 2" />
                                                </svg>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">Clientes</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <a class="card card-link" href="{{ route('fornecedor.index') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-network" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M6 9a6 6 0 1 0 12 0a6 6 0 0 0 -12 0" />
                                                    <path d="M12 3c1.333 .333 2 2.333 2 6s-.667 5.667 -2 6" />
                                                    <path d="M12 3c-1.333 .333 -2 2.333 -2 6s.667 5.667 2 6" />
                                                    <path d="M6 9h12" />
                                                    <path d="M3 20h7" />
                                                    <path d="M14 20h7" />
                                                    <path d="M10 20a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path d="M12 15v3" />
                                                </svg>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">Fornecedores</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <a class="card card-link" href="{{ route('cotacao.index') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-clipboard-list" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                    <path
                                                        d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                    <path d="M9 12l.01 0" />
                                                    <path d="M13 12l2 0" />
                                                    <path d="M9 16l.01 0" />
                                                    <path d="M13 16l2 0" />
                                                </svg>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">Cotações</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <a class="card card-link" href="{{ route('empresa.index') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-auto">
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-briefcase"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">Minha empresa</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-xl m-3">
                <div class="row row-deck row-cards">
                    <div class="col-sm-6 col-lg-3">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card" style="height: 51rem">
                                    <div class="card-header justify-content-between">
                                        <h3 class="card-title">Orçamentos <span class="badge bg-yellow text-white">Em cotação</span></h3>
                                        <div class="text-muted">Quantidade: {{ $orcamentos->where('status', 1)->count() }}</div>
                                    </div>
                                    <div class="card-body card-body-scrollable card-body-scrollable-shadow" id="column1" ondrop="drop(event, $(this))" ondragover="allowDrop(event)" data-status="1">
                                        @foreach($orcamentos->where('status', 1) as $orcamento)
                                            <div id="drag{{$orcamento->id}}" class="row mt-2" draggable="true" ondragstart="drag(event)">
                                                <div class="row">
                                                    <div class="card card-link card-link-pop p-3">
                                                        @if(isset($orcamento->titulo)) <div class="card-title"><b>{{ $orcamento->titulo }}</b></div> @endif
                                                        {{ $orcamento->Cliente->nome }} | {{ $orcamento->tempo_contrato }} meses | {{ $orcamento->tipoLinkTexto() }} | {{ $orcamento->sitesOrcamento->count() }} sites
                                                        <div class="text-muted">{{ $orcamento->created_at->format('d/m/Y') }}
                                                            <a href="{{ route('orcamento.show', ['orcamento' => $orcamento]) }}">
                                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card" style="height: 51rem">
                                    <div class="card-header justify-content-between">
                                        <h3 class="card-title">Orçamentos <span class="badge bg-blue text-white">Enviados</span></h3>
                                        <div class="text-muted">Quantidade: {{ $orcamentos->where('status', 2)->count() }}</div>
                                    </div>
                                    <div class="card-body card-body-scrollable card-body-scrollable-shadow" id="column2" ondrop="drop(event, $(this))" ondragover="allowDrop(event)" data-status="2">
                                        @foreach($orcamentos->where('status', 2) as $orcamento)
                                            <div id="drag{{$orcamento->id}}" class="row mt-2" draggable="true" ondragstart="drag(event)">
                                                <div class="row">
                                                    <div class="card card-link card-link-pop p-3">
                                                        @if(isset($orcamento->titulo)) <div class="card-title"><b>{{ $orcamento->titulo }}</b></div> @endif
                                                        {{ $orcamento->Cliente->nome }} | {{ $orcamento->tempo_contrato }} meses | {{ $orcamento->tipoLinkTexto() }} | {{ $orcamento->sitesOrcamento->count() }} sites
                                                        <div class="text-muted">
                                                            {{ $orcamento->created_at->format('d/m/Y') }}
                                                            <a href="{{ route('orcamento.show', ['orcamento' => $orcamento]) }}">
                                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card" style="height: 51rem">
                                    <div class="card-header justify-content-between">
                                        <h3 class="card-title">Orçamentos <span class="badge bg-green text-white">Aprovados</span></h3>
                                        <div class="text-muted">Quantidade: {{ $orcamentos->where('status', 3)->count() }}</div>
                                    </div>
                                    <div class="card-body card-body-scrollable card-body-scrollable-shadow" id="column3" ondrop="drop(event, $(this))" ondragover="allowDrop(event)" data-status="3">
                                        @foreach($orcamentos->where('status', 3) as $orcamento)
                                            <div id="drag{{$orcamento->id}}" class="row mt-2" draggable="true" ondragstart="drag(event)">
                                                <div class="row">
                                                    <div class="card card-link card-link-pop p-3">
                                                        @if(isset($orcamento->titulo)) <div class="card-title"><b>{{ $orcamento->titulo }}</b></div> @endif
                                                        {{ $orcamento->Cliente->nome }} | {{ $orcamento->tempo_contrato }} meses | {{ $orcamento->tipoLinkTexto() }} | {{ $orcamento->sitesOrcamento->count() }} sites
                                                        <div class="text-muted">
                                                            {{ $orcamento->created_at->format('d/m/Y') }}
                                                            <a href="{{ route('orcamento.show', ['orcamento' => $orcamento]) }}">
                                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card" style="height: 51rem">
                                    <div class="card-header justify-content-between">
                                        <h3 class="card-title">Orçamentos <span class="badge bg-red text-white">Sem viabilidade</span></h3>
                                        <div class="text-muted">Quantidade: {{ $orcamentos->where('status', 4)->count() }}</div>
                                    </div>
                                    <div class="card-body card-body-scrollable card-body-scrollable-shadow" id="column4" ondrop="drop(event, $(this))" ondragover="allowDrop(event)" data-status="4">
                                        @foreach($orcamentos->where('status', 4) as $orcamento)
                                            <div id="drag{{$orcamento->id}}" class="row mt-2" draggable="true" ondragstart="drag(event)">
                                                <div class="row">
                                                    <div class="card card-link card-link-pop p-3">
                                                        @if(isset($orcamento->titulo)) <div class="card-title"><b>{{ $orcamento->titulo }}</b></div> @endif
                                                        {{ $orcamento->Cliente->nome }} | {{ $orcamento->tempo_contrato }} meses | {{ $orcamento->tipoLinkTexto() }} | {{ $orcamento->sitesOrcamento->count() }} sites
                                                        <div class="text-muted">
                                                            {{ $orcamento->created_at->format('d/m/Y') }}
                                                            <a href="{{ route('orcamento.show', ['orcamento' => $orcamento]) }}">
                                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('js')
<script>
    function allowDrop(event) {
        event.preventDefault(); // Permite o drop
    }

    function drag(event) {
        // Define o ID do elemento que está sendo arrastado
        event.dataTransfer.setData("text", event.target.id);
    }

    function drop(event, e) {
        event.preventDefault();
        event.target.classList.remove("drag-over");

        // Obtém o ID do elemento arrastado
        var cardId = event.dataTransfer.getData("text");
        var card = document.getElementById(cardId);

        // Verifica se o card existe
        if (!card) {
            console.error(`Elemento com ID ${cardId} não encontrado.`);
            return;
        }

        // Verifica se o destino é válido
        if (event.target.classList.contains("card-body-scrollable")) {
            event.target.prepend(card);
            $.ajax({
                    url: '/altera-status-home',
                    type: 'POST', 
                    data: {
                        status: e.attr('data-status'),
                        orcamentoId: $(card).attr('id'),
                        _token: '{{ csrf_token() }}'
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na requisição: ', error);
                    }
                });
        } else if (event.target.closest(".card-body-scrollable")) {
            event.target.closest(".card-body-scrollable").prepend(card);
        } else {
            console.error("Destino inválido para o drop.");
        }
    }
</script>

@endsection