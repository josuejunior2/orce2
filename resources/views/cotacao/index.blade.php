@extends('layouts.admin')

@section('content')

<div class="col-12">
    <div class="card m-3">
        <div class="card-header justify-content-between">
            <h3 class="card-title">Lista de cotações</h3>
            <div>
                <a href="{{ route('orcamento.create') }}" class="btn btn-success w-100">
                    Adicionar novo orçamento
                </a>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
      <div class="table-responsive m-4">
        <table class="display w-100" id="tabela-cotacoes"> {{-- table card-table table-vcenter text-nowrap datatable --}}
          <thead>
            <tr>
              {{--<th class="w-1"></th>  <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"> --}}
              {{-- <th class="w-1">ID <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 15l6 -6l6 6" /></svg>
              </th> --}}
              <th>Cliente</th>
              <th>Site</th>
              <th>Endereço</th>
              {{-- <th>Cidade</th> --}}
              <th>Velocidade</th>
              <th>Contrato</th>
              @can('precificar orcamento')
                <th>Mensal c/ Imp</th>
                <th>Custo de Instalação c/ Imp</th>
              @endcan
              <th>Prazo de Instalação</th>
              <th>Tecnologia</th>
              <th>Fornecedor</th>
              <th>Adesão Fornecedor</th>
              <th>Mensalidade Fornecedor</th>
              @can('precificar orcamento')
                <th>Imposto Adesão</th>
                <th>Lucro Adesão</th>
                <th>Imposto Mensal</th>
                <th>Lucro Líquido</th>
              @endcan
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($sites as $site)
                    @foreach ($site->cotacoes as $cotacao)
                        @include('orcamento.modal.edit-status-orcamento', ['orcamento' => $site->Orcamento])
                        <tr>
                            <td>{{ $site->Orcamento->Cliente->nome }}</td>
                            <td>{{ $site->nome }}</td>
                            <td>{{ $site->endereco }}</td>
                            <td>{{ number_format($site->vel_solicitada_down, 0, ',', '.') }} Mbps</td>
                            <td>{{ $cotacao->Site->Orcamento->tempo_contrato }} meses</td>
                            @can('precificar orcamento')
                                <td>R$ {{ number_format($cotacao->mensal_imp, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($cotacao->custo_instalacao_imp, 2, ',', '.') }}</td>
                            @endcan
                            <td>{{ $cotacao->prazo_instalacao }}</td>
                            <td>{{ $cotacao->tecnologia }}</td>
                            <td>{{ $cotacao->Fornecedor->nome }}</td>
                            <td>R$ {{ number_format($cotacao->adesao_fornecedor, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($cotacao->mensal_fornecedor, 2, ',', '.') }}</td>
                            @can('precificar orcamento')
                                <td>R$ {{ number_format($cotacao->imposto_mensal, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($cotacao->imposto_adesao, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($cotacao->lucro_adesao, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($cotacao->lucro_liquido, 2, ',', '.') }}</td>
                            @endcan
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="#" class="btn-icon" data-bs-toggle="modal" data-bs-target="#modal-edit-status-orcamento">
                                        @if ($site->Orcamento->status == 'Sem viabilidade')
                                        <span class="badge bg-red text-white align-items-center">Sem viabilidade<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil ms-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                        @elseif ($site->Orcamento->status == 'Em cotação')
                                        <span class="badge bg-yellow text-white align-items-center">Em cotação<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil ms-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                        @elseif ($site->Orcamento->status == 'Enviado')
                                        <span class="badge bg-blue text-white align-items-center">Enviado<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil ms-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                        @elseif ($site->Orcamento->status == 'Aprovado')
                                        <span class="badge bg-green text-white align-items-center">Aprovado<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil ms-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                        @endif
                                    </a>
                                </div>
                            </td>
                            <td class="text-end">
                                <a class="btn justify-content-center" href="{{ route('orcamento.show', ['orcamento' => $site->Orcamento]) }}">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                    Visualizar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @if($site->cotacoes->isEmpty())
                    @include('orcamento.modal.edit-status-orcamento', ['orcamento' => $site->Orcamento])
                        <tr>
                            <td>{{ $site->Orcamento->Cliente->nome }}</td>
                            <td>{{ $site->nome }}</td>
                            <td>{{ $site->endereco }}</td>
                            <td> </td>
                            <td> </td>
                            @can('precificar orcamento')
                            <td> </td>
                            <td> </td>
                            @endcan
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            @can('precificar orcamento')
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            @endcan
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="#" class="btn-icon" data-bs-toggle="modal" data-bs-target="#modal-edit-status-orcamento">
                                        @if ($site->Orcamento->status == 'Sem viabilidade')
                                        <span class="badge bg-red text-white align-items-center">Sem viabilidade<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil ms-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                        @elseif ($site->Orcamento->status == 'Em cotação')
                                        <span class="badge bg-yellow text-white align-items-center">Em cotação<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil ms-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                        @elseif ($site->Orcamento->status == 'Enviado')
                                        <span class="badge bg-blue text-white align-items-center">Enviado<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil ms-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                        @elseif ($site->Orcamento->status == 'Aprovado')
                                        <span class="badge bg-green text-white align-items-center">Aprovado<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil ms-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                        @endif
                                    </a>
                                </div>
                            </td>
                            <td class="text-end">
                                <a class="btn justify-content-center" href="{{ route('orcamento.show', ['orcamento' => $site->Orcamento]) }}">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                    Visualizar
                                </a>
                            </td>
                        </tr>
                    @endif
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready( function () {
        $('#tabela-cotacoes').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
            "pageLength": 10,
            "language": {
                url: '/pt-br-datatables.json',
            },
        });
    });
</script>
@endsection
