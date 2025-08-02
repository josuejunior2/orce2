@extends('layouts.admin')

@section('content')
    <div class="col-12">
        <div class="card m-3">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Lista de serviços</h3>
                <div>
                    <a href="{{ route('servico.create') }}" class="btn btn-success w-100">
                        Adicionar novo serviço
                    </a>
                </div>
            </div>
            <div class="table-responsive m-4">
                <table class="display w-100" id="tabela-servicos"> {{-- table card-table table-vcenter text-nowrap datatable --}}
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servicos as $s)
                            @include('servico.modal.destroy')
                            <tr>
                                <td>{{ $s->nome }}</td>
                                <td>{{ $s->descricao }}</td>
                                <td class="w-25">
                                    <div class="row">
                                        <div class="col text-end">
                                            <a class="btn " href="{{ route('servico.edit', ['servico' => $s]) }}">
                                                <svg  xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                            </a>
                                        </div>
                                        <div class="col text-start">
                                            <form id="form_{{$s->id}}" method="post" action="{{ route('servico.destroy', ['servico' => $s->id]) }}">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-destroy-servico{{$s->id}}">
                                                    <svg  xmlns="http://www.w3.org/2000/svg" class="pr-0" width="18"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                </a>
                                            </form>                          
                                        </div>
                                    </div>  
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
        $(document).ready(function() {
            $('#tabela-servicos').DataTable({
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
