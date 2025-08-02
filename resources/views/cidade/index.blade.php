@extends('layouts.admin')

@section('content')

<div class="col-12">
    <div class="card m-3">
        <div class="card-header justify-content-between">
            <h3 class="card-title">Lista de cidades</h3>
        </div>
        @if(isset($cidades))
        <div class="table-responsive m-4">
          <table class="display w-100" id="tabela-cidades"> {{-- table card-table table-vcenter text-nowrap datatable --}}
            <thead>
              <tr>
                {{--<th class="w-1"></th>  <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"> --}}
                <th class="w-1">ID <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 15l6 -6l6 6" /></svg>
                </th>
                <th>Nome</th>
                <th>Estado</th>
                <th>UF</th>
                <th>Fornecedores</th>
                {{-- <th></th> --}}
              </tr>
            </thead>
            <tbody>
              @foreach ($cidades as $c)
              <tr>
                  <!--<td></td>  <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"> -->
                  <td><span class="text-muted">{{ $c->id }}</span></td>
                  <td>{{ $c->nome }}</td>
                  <td>{{ $c->Estado->nome }}</td>
                  <td>{{ $c->Estado->uf }}</td>
                  <td>
                      @foreach ($fornecedores as $f) {{-- Entre todos os fornecedores... --}}
                          @foreach ($f->cidades as $c_forn) {{-- Entre as cidades que os fornecedores atendem... --}}
                              @if ($c_forn->nome == $c->nome) {{-- Se a cidade for igual à da linha... --}}
                                  <a href="{{ route('fornecedor.show', ['fornecedor' => $f]) }}">{{ $f->nome }}</a><br>
                              @endif
                          @endforeach
                      @endforeach
                  </td>
                  {{-- <td class="text-end">
                    <span class="dropdown">
                      <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Ações</button>
                      <div class="dropdown-menu dropdown-menu-end">
                          <a class="dropdown-item" href="{{ route('cidade.show', ['cidade' => $c]) }}">
                              Visualizar
                          </a>
                      </div>
                    </span>
                  </td> --}}
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready( function () {
        $('#tabela-cidades').DataTable({
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
