@extends('welcome')
@section('body')
    <div class="container p-4">
        <x-navRecibos :btnactive="$btnactive" />
        <table class="table table-hover mt-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Serviço Prestado</th>
                    <th scope="col">Valor R$</th>
                    <th scope="col">Data</th>
                </tr>
            </thead>
            <tbody>
                @isset($recibos)
                    @for ($i = 0; $i < count($recibos); $i++)
                        <x-RecibosList :recibo="$recibos[$i]" :i="$i + 1" />
                    @endfor
                @endisset
            </tbody>
        </table>
    </div>
@endsection
