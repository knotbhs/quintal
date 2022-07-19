@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Painel Administrativo') }}</div>

                    <div class="card-body">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Recibos</h5>
                                    <p class="card-text">Gerar e Imprimir Recibos</p>
                                    <a href="{{ route('recibos.index') }}" class="btn btn-primary">Visitar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
