@extends('layouts.admin')

@section('content')
<div class="card m-3">
    <div class="card-header">
      <h3 class="card-title">Cadastro de Fornecedor</h3>
    </div>

    <div class="card-body">
    <form method="POST" action="{{ route('fornecedor.store', ['site' => $site]) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="col-3 col-form-label required">Nome</label>
            <div class="col">
                <input type="text" class="form-control" name="nome" id="nome" value="{{ old('nome', '') }}">
                <span class="{{ $errors->has('nome') ? 'text-danger' : '' }}">
                    {{ $errors->has('nome') ? $errors->first('nome') : '' }}
                </span>
            </div>
        </div>
        <div class="mb-3">
            <label class="col-form-label required">Cadastro Nacional de Pessoa Jurídica (CNPJ)</label>
            <input id="cnpj" name="cnpj" type="text" class="form-control" value="{{ old('cnpj', '') }}" data-mask="00.000.000/0000-00" data-mask-visible="true" placeholder="00.000.000/0000-00"autocomplete="off" />
            <span class="{{ $errors->has('cnpj') ? 'text-danger' : '' }}">
                {{ $errors->has('cnpj') ? $errors->first('cnpj') : '' }}
            </span>
        </div>
        <div class="mb-3">
          <label class="col-3 col-form-label required">Email</label>
          <div class="col">
            <input type="text" class="form-control" name="email" id="email" value="{{ old('email', '') }}">
            <span class="{{ $errors->has('email') ? 'text-danger' : '' }}">
                    {{ $errors->has('email') ? $errors->first('email') : '' }}
                </span>
          </div>
        </div>
        <div class="mb-3">
          <label class="col-3 col-form-label required">Telefone</label>
          <div class="col">
            <input type="text" name="telefone" id="telefone" class="form-control" value="{{ old('telefone', '') }}" data-mask="(00) 00000-0000" data-mask-visible="true" placeholder="(00) 0000-0000"autocomplete="off"/>
            <span class="{{ $errors->has('telefone') ? 'text-danger' : '' }}">
                    {{ $errors->has('telefone') ? $errors->first('telefone') : '' }}
                </span>
          </div>
        </div>
        <div class="mb-3">
            <label class="col-3 col-form-label">Cidade</label>
            <div class="col">
                <input type="text" name="cidades" id="cidades" multiple></input>
                <small class="form-hint">
                    Caso o fornecedor tenha muitas cidades adicione cidades via planilha na página de visualização do fornecedor.</a>
                </small>
                <span class="{{ $errors->has('cidades') ? 'text-danger' : '' }}">
                    {{ $errors->has('cidades') ? $errors->first('cidades') : '' }}
                </span>
            </div>
        </div>
        <div class="mb-3">
            <label class="col-3 col-form-label required">Nome do representante</label>
            <div class="col">
                <input type="text" class="form-control" name="representante" id="representante" value="{{ old('representante', '') }}">
                <span class="{{ $errors->has('representante') ? 'text-danger' : '' }}">
                    {{ $errors->has('representante') ? $errors->first('representante') : '' }}
                </span>
            </div>
        </div>
      </div>
      <div class="card-footer text-end">
        <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Cadastrar
        </button>
      </div>
    </form>
  </div>
</div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var el;
        window.TomSelect && (new TomSelect(el = document.getElementById('cidades'), {
            options: [
                @foreach($cidades as $cidade)
                    {
                        value: '{{ $cidade->id }}',
                        text: '{{ str_replace("'", " ", $cidade->nome) }} - {{ $cidade->Estado->uf }}'
                    },
                @endforeach
            ],
            @php
                $oldCidades = old('cidades') ?? (isset($site) && isset($site->Cidade) ? [$site->Cidade->id] : []);
                if (is_string($oldCidades)) {
                    $oldCidades = explode(',', $oldCidades);
                }
            @endphp

            items: [
                @foreach($oldCidades as $id)
                    '{{ $id }}',
                @endforeach
            ],
        }));
    });
    </script>
@endsection
