@extends('layouts.admin')

@section('content')
    <div class="card m-3">
        <div class="card-header">
            <h3 class="card-title">Cadastro de Serviço</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('servico.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md">
                        <div class="mb-3">
                            <label class="col-3 col-form-label required">Nome</label>
                            <div class="col">
                                <input type="text" class="form-control" name="nome" id="nome"
                                    value="{{ old('nome', '') }}">
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
                                    value="{{ old('descricao', '') }}">
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Cadastrar
                    </button>
                </div>
            </form>
    </div>
    </div>
@endsection
