@extends('layouts.admin')

@section('content')
<div class="card m-3">
    <div class="card-header">
      <h3 class="card-title">Editar cadastro de Cliente</h3>
    </div>
    <div class="card-body">
    <form method="POST" action="{{ route('cliente.update', ['cliente' => $cliente]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="mb-3">
                <label class="col-3 col-form-label required">Razão Social</label>
                <div class="col">
                    <input type="text" class="form-control" name="nome" id="nome" value="{{ $cliente->nome }}">
                    <span class="{{ $errors->has('nome') ? 'text-danger' : '' }}">
                        {{ $errors->has('nome') ? $errors->first('nome') : '' }}
                    </span>
                </div>
            </div>
        </div>
        </div>
        <div class="row g-3 mb-4">
        <div class="col-md">
        <div class="mb-3">
          <label class="col-3 col-form-label required">Telefone</label>
          <div class="col">
            <input type="text" class="form-control" name="telefone" id="telefone" value="{{ $cliente->telefone }}">
            <span class="{{ $errors->has('telefone') ? 'text-danger' : '' }}">
                    {{ $errors->has('telefone') ? $errors->first('telefone') : '' }}
                </span>
          </div>
        </div>
        </div>
        <div class="col-md">
        <div class="mb-3">
            <label class="col-3 col-form-label required">Email</label>
            <div class="col">
                <input type="text" class="form-control" name="email" id="email" value="{{ $cliente->email }}">
                <span class="{{ $errors->has('email') ? 'text-danger' : '' }}">
                    {{ $errors->has('email') ? $errors->first('email') : '' }}
                </span>
            </div>
        </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md">
            <div class="mb-3">
                <label class="col-3 col-form-label required">Endereço</label>
                <div class="col">
                  <input type="text" class="form-control" name="endereco" id="endereco" value="{{ $cliente->endereco }}">
                  <span class="{{ $errors->has('endereco') ? 'text-danger' : '' }}">
                          {{ $errors->has('endereco') ? $errors->first('endereco') : '' }}
                      </span>
                </div>
              </div>
              </div>
        </div>
      </div>
      <div class="card-footer text-end">
        <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
            Atualizar
        </button>
      </div>
    </form>
  </div>
</div>
</div>
@endsection

