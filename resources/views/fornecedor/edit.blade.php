@extends('layouts.admin')

@section('content')
<div class="card m-3">
    <div class="card-header">
      <h3 class="card-title">Cadastro de Fornecedor</h3>
    </div>

    <div class="card-body">
    <form method="POST" action="{{ route('fornecedor.update', ['fornecedor' => $fornecedor] ) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="col-3 col-form-label required">Nome</label>
            <div class="col">
                <input type="text" class="form-control" name="nome" id="nome" value="{{ old('nome', $fornecedor->nome) }}">
                <span class="{{ $errors->has('nome') ? 'text-danger' : '' }}">
                    {{ $errors->has('nome') ? $errors->first('nome') : '' }}
                </span>
            </div>
        </div>
        <div class="mb-3">
            <label class="col-form-label required">Cadastro Nacional de Pessoa Jurídica (CNPJ)</label>
            <input id="cnpj" name="cnpj" type="text" class="form-control" value="{{ old('cnpj', $fornecedor->cnpj) }}" data-mask="00.000.000/0000-00" data-mask-visible="true" placeholder="00.000.000/0000-00"autocomplete="off" />
            <span class="{{ $errors->has('cnpj') ? 'text-danger' : '' }}">
                {{ $errors->has('cnpj') ? $errors->first('cnpj') : '' }}
            </span>
        </div>
        <div class="mb-3">
          <label class="col-3 col-form-label required">Email</label>
          <div class="col">
            <input type="text" class="form-control" name="email" id="email" value="{{ old('email', $fornecedor->email) }}">
            <span class="{{ $errors->has('email') ? 'text-danger' : '' }}">
                {{ $errors->has('email') ? $errors->first('email') : '' }}
            </span>
          </div>
        </div>
        <div class="mb-3">
          <label class="col-3 col-form-label required">Telefone</label>
          <div class="col">
            <input type="text" name="telefone" id="telefone" class="form-control" autocomplete="off" value="{{ old('telefone', $fornecedor->telefone) }}"/>
            <span class="{{ $errors->has('telefone') ? 'text-danger' : '' }}">
                {{ $errors->has('telefone') ? $errors->first('telefone') : '' }}
            </span>
          </div>
        </div>
        {{-- <div class="mb-3">
            <label class="col-3 col-form-label required">Cidade</label>
            <div class="col">
                <select class="form-select" type="text" name="cidades[]" id="cidades" multiple>
                    @foreach($cidades as $c)
                        @foreach($fornecedor->cidades as $fc)
                            <option value="{{ $c->id }}" {{ (is_array(old('cidades')) && in_array($c->id, old('cidades'))) || ($fornecedor->cidades->contains('id', $c->id)) ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
                <span class="{{ $errors->has('cidades') ? 'text-danger' : '' }}">
                    {{ $errors->has('cidades') ? $errors->first('cidades') : '' }}
                </span>
            </div>
        </div> --}}
        <div class="mb-3">
            <label class="col-3 col-form-label required">Nome do representante</label>
            <div class="col">
                <input type="text" class="form-control" name="representante" id="representante" value="{{ old('representante', $fornecedor->representante) }}">
                <span class="{{ $errors->has('representante') ? 'text-danger' : '' }}">
                    {{ $errors->has('representante') ? $errors->first('representante') : '' }}
                </span>
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

{{-- @section('js')
<script src="https://cdn.jsdelivr.net/npm/tom-select@latest/dist/js/tom-select.base.min.js" defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var el;
        window.TomSelect && (new TomSelect(el = document.getElementById('cidades'), {
            options: [
                @foreach($cidades as $cidade)
                    {value: '{{ $cidade->id }}', text: '{{ str_replace("'", " ", $cidade->nome) }}'},
                @endforeach
            ], // quando digita um nome de cidade e clica, o que foi digitado ainda continua lá, preciso resolver isso depois
        }));
    });
</script>
@endsection --}}
