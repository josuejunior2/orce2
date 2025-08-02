@extends('layouts.admin')

@section('content')

<div class="card m-3">
    <div class="card-header justify-content-between">
        <h3 class="card-title">Empresa</h3>
        <div class="d-flex justify-content-between col-auto">
            <a href=" {{ route('empresa.edit', ['empresa' => $empresa ]) }}" class="btn me-2 btn-secondary w-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                Editar
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="datagrid">
            <div class="datagrid-item">
                <div class="datagrid-title">Nome</div>
                <div class="datagrid-content">{{ $empresa->nome }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">CNPJ</div>
                <div class="datagrid-content">{{ $empresa->cnpj }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Email</div>
                <div class="datagrid-content">{{ $empresa->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('empresa.updateParams', ['empresa' => $empresa]) }}" autocomplete="off" novalidate>
            @csrf
            <div class="row g-3 mb-4 justify-content-center">
                <div class="col-md-2">
                    <label for="custo_fixo_percent" class="col-form-label form-label">Custo fixo</label>
                    <div class="input-group">
                        <input id="custo_fixo_percent" name="custo_fixo_percent" type="number" class="form-control" value="{{ old('custo_fixo_percent', $empresa->custo_fixo_percent) }}" />
                        <span class="input-group-text">
                            %
                        </span>
                        <span class="{{ $errors->has('custo_fixo_percent') ? 'text-danger' : '' }}">
                            {{ $errors->has('custo_fixo_percent') ? $errors->first('custo_fixo_percent') : '' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="gear_noc" class="col-form-label form-label">GearNoc</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            R$
                        </span>
                        <input id="gear_noc" name="gear_noc" type="number" class="form-control" value="{{ old('gear_noc', $empresa->gear_noc) }}"/>
                        <span class="{{ $errors->has('gear_noc') ? 'text-danger' : '' }}">
                            {{ $errors->has('gear_noc') ? $errors->first('gear_noc') : '' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent mt-auto">
                <div class="btn-list justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
        </form>
        
</div>

<div class="card m-3">
    <div class="card-header justify-content-between">
        <h3 class="card-title">Lista de Colaboradores</h3>
        <div>
            @can('CRUD usuarios')
                <a href="{{ route('admin.create') }}" class="btn btn-success w-100">
                    Adicionar novo colaborador
                </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive m-4">
            <table class="display w-100" id="tabela-colaboradores">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($colaboradores as $c)
                <tr>
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

