@extends('layouts.admin')

@section('content')
    <div class="card m-3">
        <div class="card-header">
            <h3 class="card-title">Cadastro de empresa</h3>
        </div>

        <div class="card-body">
            <div class="row g-0">
                <div class="col d-flex flex-column">
                    <div class="card-body">
                        <form method="POST" action="{{ route('empresa.store') }}" autocomplete="off" novalidate>
                            @csrf
                            <div class="row g-3 mb-4">
                                <div class="col-md">
                                    <label for="nome" class="form-label required">Nome da Empresa</label>
                                    <input id="nome" name="nome" type="text" class="form-control" value="{{ old('nome', '') }}" />
                                    <span class="{{ $errors->has('name') ? 'text-danger' : '' }}">
                                        {{ $errors->has('nome') ? $errors->first('nome') : '' }}
                                    </span>
                                </div>
                                <div class="col-md">
                                    <label for="cnpj" class="form-label required">Cadastro Nacional de Pessoa Jurídica (CNPJ)</label>
                                    <input id="cnpj" name="cnpj" type="text" class="form-control"
                                        value="{{ old('cnpj', '') }}" data-mask="00.000.000/0000-00"
                                        data-mask-visible="true" placeholder="00.000.000/0000-00" autocomplete="off" />
                                    <span class="{{ $errors->has('cnpj') ? 'text-danger' : '' }}">
                                        {{ $errors->has('cnpj') ? $errors->first('cnpj') : '' }}
                                    </span>
                                </div>
                                <div class="col-md">
                                    <label for="email" class="form-label required">Email</label>
                                    <input id="email" name="email" type="text" class="form-control"
                                        value="{{ old('email', '') }}" />
                                    <span class="{{ $errors->has('email') ? 'text-danger' : '' }}">
                                        {{ $errors->has('email') ? $errors->first('email') : '' }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent mt-auto">
                                <div class="btn-list justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        Enviar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
