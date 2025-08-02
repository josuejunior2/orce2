@extends('layouts.admin')

@section('content')
    <div class="card m-3">
        <div class="card-header">
            <h3 class="card-title">Editar Serviço</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('servico.update', ['servico' => $servico]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3 mb-4">
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-3 col-form-label required">Nome</label>
                            <div class="col">
                                <input type="text" class="form-control" name="nome" id="nome"
                                    value="{{ old('nome', $servico->nome) }}"">
                                <span class="{{ $errors->has('nome') ? 'text-danger' : '' }}">
                                    {{ $errors->has('nome') ? $errors->first('nome') : '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-3 col-form-label">Descrição</label>
                            <div class="col">
                                <input type="text" class="form-control" name="descricao" id="descricao"
                                    value="{{ old('descricao', $servico->descricao) }}"">
                                <span class="{{ $errors->has('descricao') ? 'text-danger' : '' }}">
                                    {{ $errors->has('descricao') ? $errors->first('descricao') : '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        Salvar
                    </button>
                </div>
            </form>
    </div>
    </div>
@endsection
