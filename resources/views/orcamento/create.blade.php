@extends('layouts.admin')

@if (session('error'))
    <div class="alert alert-danger mt-1">
        {{ session('error') }}
    </div>
@endif

@section('content')
<div class="card m-3">
    <div class="card-header">
      <h3 class="card-title">Cadastro de Orçamento</h3>
    </div>
    <div class="card-body">
    <form method="POST" action="{{ route('orcamento.store') }}" id="formOrcamento">
        @csrf
        <input type="hidden" name="cadastrar_sites" id="cadastrar_sites" value="0">
        <input type="hidden" name="gear_noc" id="gear_noc" value="{{ auth()->user()->Empresa->gear_noc }}">
        <input type="hidden" name="custo_fixo_percent" id="custo_fixo_percent" value="{{ auth()->user()->Empresa->custo_fixo_percent }}">
        <div class="row g-3 mb-4">
            <div class="col-md">
                <div class="mb-3">
                    <label class="col-3 col-form-label required" for="titulo">Título</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" value="{{ old('titulo', '') }}">
                    <span class="{{ $errors->has('titulo') ? 'text-danger' : '' }}">
                        {{ $errors->has('titulo') ? $errors->first('titulo') : '' }}
                    </span>
                </div>
            </div>
            <div class="col-md">
                <div class="mb-3">
                    <label class="col-3 col-form-label required">Cliente</label>
                    <select class="form-select" name="cliente_id" id="cliente_id" value="{{ old('cliente_id', '') }}">
                        <option value=""> -- Selecione o cliente -- </option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->nome }}
                        </option>
                        @endforeach
                    </select>
                    <small class="form-hint">
                        Caso o fornecedor não esteja cadastrado ainda, <a href="{{ route('cliente.create') }}">clique aqui</a>
                    </small>
                    <span class="{{ $errors->has('cliente_id') ? 'text-danger' : '' }}">
                        {{ $errors->has('cliente_id') ? $errors->first('cliente_id') : '' }}
                    </span>
                </div>
            </div>
            <div class="col-md">
                <div class="mb-3">
                    <label class="col-form-label required">Tempo do contrato (em meses)</label>
                    <div class="col">
                        <input type="number" class="form-control" name="tempo_contrato" id="tempo_contrato" value="{{ old('tempo_contrato', '') }}">
                        <span class="{{ $errors->has('tempo_contrato') ? 'text-danger' : '' }}">
                            {{ $errors->has('tempo_contrato') ? $errors->first('tempo_contrato') : '' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="mb-3">
                <label class="col-form-label required">Status do Orçamento</label>
                <select class="form-select" name="status" id="status" value="{{ old('status', '') }}">
                    <option value=""></option>
                    @foreach(\App\Models\Orcamento::getStatus() as $status)
                        <option value="{{$status}}" class="badge bg-{{\App\Models\Orcamento::getStatusTextoECor($status)['cor']}} text-white">{{ \App\Models\Orcamento::getStatusTextoECor($status)['texto'] }}</option>
                    @endforeach
                </select>
                <span class="{{ $errors->has('status') ? 'text-danger' : '' }}">
                        {{ $errors->has('status') ? $errors->first('status') : '' }}
                </span>
            </div>
        </div>
        <div class="col-md">
            <div class="mb-3">
                <label class="col-form-label required">Imposto</label>
                <div class="input-group mb-2">
                    <input type="number" class="form-control" name="imposto" id="imposto" value="{{ old('imposto', 15) }}" autocomplete="off">
                    <span class="input-group-text">
                        %
                    </span>
                  </div>
            </div>
        </div>
    </div>

      <div class="card-footer text-end">
        <button type="submit" class="btn btn-primary">
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
            Salvar
        </button>
        <button type="submit" class="btn btn-outline-primary" onclick="$('#cadastrar_sites').val(1)">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M15 16l4 -4" /><path d="M15 8l4 4" /></svg>
            Salvar e cadastrar Sites
        </button>
      </div>
    </form>
  </div>
</div>
</div>
@endsection

@section('js')
<script>
    // $("#formOrcamento").on('submit', function(e) {
    //     $(e).preventDefault();
    //     var formData = new formData($(e))
    // });
</script>
@endsection
