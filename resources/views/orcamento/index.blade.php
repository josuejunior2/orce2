@extends('layouts.admin')

@section('content')

<div class="col-12">
    <div class="card m-3">
        <div class="card-header justify-content-between">
            <h3 class="card-title">Lista de orçamentos</h3>
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
        <table class="display w-100" id="tabela-orcamentos"> {{-- table card-table table-vcenter text-nowrap datatable --}}
          <thead>
            <tr>
              {{--<th class="w-1"></th>  <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"> --}}
              {{-- <th class="w-1">ID <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 15l6 -6l6 6" /></svg>
              </th> --}}
              <th>Cliente</th>
              <th>Link</th>
              <th>Tempo do contrato</th>
              <th>Quantidade de sites</th>
              <th>Sites</th>
              <th>Cidade</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orcamentos as $o)
            @include('orcamento.modal.edit-status-orcamento', ['orcamento' => $o])
            <tr>
                <!--</td>  <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"> -->
                <td>{{ $o->Cliente->nome }}</td>
                <td>
                    @if ($o->tipo_link == 'ld') Link Dedicado @elseif ($o->tipo_link == 'l2l') Lan 2 Lan @endif
                </td>
                <td>{{ $o->tempo_contrato }} meses</td>
                <td>{{ $o->sites->count() }}</td>
                <td>
                    @if(isset($o->sites))
                        @foreach ($o->sites as $site)
                            {{ $site->nome }}<br>
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(isset($o->sites))
                        @foreach ($o->sites as $os)
                            {{ $site->Cidade->nome }} - {{ $site->Cidade->Estado->uf }}<br>
                        @endforeach
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="#" class="btn-icon" data-bs-toggle="modal" data-bs-target="#modal-edit-status-orcamento">
                            @if ($o->status == 'Sem viabilidade')
                            <span class="badge bg-red text-white align-items-center"><div>Sem viabilidade</div></span>
                            @elseif ($o->status == 'Em cotação')
                            <span class="badge bg-yellow text-white align-items-center"><div>Em cotação</div></span>
                            @elseif ($o->status == 'Enviado')
                            <span class="badge bg-blue text-white align-items-center"><div>Enviado</div></span>
                            @elseif ($o->status == 'Aprovado')
                            <span class="badge bg-green text-white align-items-center"><div>Aprovado</div></span>
                            @endif
                        </a>
                    </div>
                </td>
                <td class="text-end">
                    <a class="btn justify-content-center" href="{{ route('orcamento.show', ['orcamento' => $o]) }}">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                        Visualizar
                    </a>
                </td>
              </tr>
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
        $('#tabela-orcamentos').DataTable({
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
