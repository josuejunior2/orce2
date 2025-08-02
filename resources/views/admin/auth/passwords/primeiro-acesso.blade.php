@extends('layouts.app')

@section('page_title')
    Primeiro Acesso
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-status-top bg-dark"></div>
        <div class="card-body">
            <div class="card-title">Redefinir Senha</div>
            <form action="{{ route('admin.redefinir.senha') }}" method="POST">
                @csrf

                <div class="col-md-6">
                    <label for="password" class="col-md-4 col-form-label">{{ __('Senha') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required>

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="password-confirm" class="col-md-4 col-form-label ">Confirmar senha</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Redefinir</button>
            </form>
        </div>
    </div>
@endsection
