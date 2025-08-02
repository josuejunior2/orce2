@extends('layouts.admin')

@section('content')

<div class="col-12">
    <div class="card m-3">
        <div class="card-header justify-content-between">
            <h3 class="card-title">Empresa</h3>
            <div>
                <a href="{{ route('empresa.show', ['empresa' => auth()->guard('admin')->user()->Empresa]) }}" class="btn btn-success w-100">
                    Dados
                </a>
            </div>
        </div>
  </div>
</div>
@endsection

