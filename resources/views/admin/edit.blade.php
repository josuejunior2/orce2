@extends('layouts.admin')

@section('content')
<div class="card m-3">
    <div class="card-header">
      <h3 class="card-title">Cadastro de Colaborador</h3>
    </div>

    <div class="card-body">
    <form method="POST" action="{{ route('admin.update', ['admin' => $colaborador]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="empresa_id" id="empresa_id" value="{{ auth()->user()->empresa_id }}">
        <div class="row g-3 mb-4">
          <div class="col-md">
            <label class="col-3 col-form-label required">Nome</label>
            <div class="col">
              <input type="text" class="form-control" name="nome" id="nome" value="{{ old('nome', $colaborador->nome) }}">
              <span class="{{ $errors->has('nome') ? 'text-danger' : '' }}">
                      {{ $errors->has('nome') ? $errors->first('nome') : '' }}
              </span>
            </div>
          </div>
          <div class="col-md">
            <label class="col-3 col-form-label required">Email</label>
            <div class="col">
              <input type="text" class="form-control" name="email" id="email" value="{{ old('email', $colaborador->email) }}">
              <span class="{{ $errors->has('email') ? 'text-danger' : '' }}">
                      {{ $errors->has('email') ? $errors->first('email') : '' }}
              </span>
            </div>
          </div>
        </div>
        <div class="row g-3 mb-4">
          <div class="col-md">
            <label class="col-3 col-form-label">Senha</label>
            <div class="col">
              <input type="password" class="form-control" name="password" id="password" value="">
              <small class="form-hint">
                  A senha deve ter no mínimo 8 caracteres, incluir letras maiúsculas e minúsculas, conter pelo menos um número e um símbolo (como !, @, #, $).
              </small>
              <span class="{{ $errors->has('password') ? 'text-danger' : '' }}">
                      {{ $errors->has('password') ? $errors->first('password') : '' }}
              </span>
            </div>
          </div>
          <div class="col-md">
            <label class="col-3 col-form-label required">Telefone</label>
            <div class="col">
              <input type="text" name="telefone" id="telefone" class="form-control" value="{{ old('telefone', $colaborador->telefone) }}"  data-mask="(00) 00000-0000" data-mask-visible="true" placeholder="(00) 0000-0000"autocomplete="off" value="{{ $colaborador->telefone }}"/>
              <span class="{{ $errors->has('telefone') ? 'text-danger' : '' }}">
                      {{ $errors->has('telefone') ? $errors->first('telefone') : '' }}
              </span>
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

