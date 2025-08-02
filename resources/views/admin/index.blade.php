@extends('layouts.admin')

@section('content')

<div class="col-12">
    <div class="card m-3">
        <div class="card-header justify-content-between">
            <h3 class="card-title">Lista de Colaboradores</h3>
            <div>
                @can('precificar orcamento')
                    <a href="{{ route('admin.create') }}" class="btn btn-success w-100">
                        Adicionar novo colaborador
                    </a>
                @endcan
            </div>
        </div>
      <div class="table-responsive m-4">
        <table class="display w-100" id="tabela-colaboradores"> {{-- table card-table table-vcenter text-nowrap datatable --}}
          <thead>
            <tr>
              {{--<th class="w-1"></th>  <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"> --}}
              {{-- <th class="w-1">ID <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 15l6 -6l6 6" /></svg>
              </th> --}}
              <th>Nome</th>
              <th>Email</th>
              <th>Telefone</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($colaboradores as $c)
            <tr>
                <!--<td></td>  <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"> -->
                {{-- <td><span class="text-muted">{{ $c->id }}</span></td> --}}
                <td>{{ $c->nome }}</td>
                <td>{{ $c->email }}</td>
                <td>{{ $c->telefone }}</td>
                <td class="text-end">
                    <a class="btn justify-content-center" href="{{ route('admin.show', ['admin' => $c->id]) }}">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                        Visualizar
                    </a>
                  {{-- <span class="dropdown">
                    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Ações</button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ route('admin.show', ['admin' => $c->id]) }}">
                            Visualizar
                        </a>
                        @can('CRUD usuarios')
                            <a class="dropdown-item" href="{{ route('admin.edit', ['admin' => $c->id]) }}">
                                Editar
                            </a>
                            <form id="form_{{$c->id}}" method="post" action="{{ route('admin.destroy', ['admin' => $c->id]) }}">
                                @method('DELETE')
                                @csrf
                                <!-- <button type="submit">Excluir</button>  -->
                                <a href="#" onclick="document.getElementById('form_{{$c->id}}').submit()" class="dropdown-item">
                                    Excluir cadastro
                                </a>
                            </form>
                        @endcan
                    </div>
                  </span> --}}
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
        $('#tabela-colaboradores').DataTable({
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

