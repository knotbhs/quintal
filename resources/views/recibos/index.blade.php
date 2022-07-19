@extends('layouts.app')
@section('content')
    <div class="container">
        <x-navRecibos :btnactive="$btnactive" :datastart="isset($datastart) ? $datastart : null" :dataend="isset($dataend) ? $dataend : null" :texto="isset($texto) ? $texto : null" :pesquisarpor="isset($pesquisarpor) ? $pesquisarpor : null" />
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
                <form target="_blank" action="{{ route('recibos.imprimir.selecionados') }}" method="post" id="formImprimir">
                    @csrf
                    @isset($recibos)
                        @for ($i = 0; $i < count($recibos); $i++)
                            <x-RecibosList :recibo="$recibos[$i]" :i="$i + 1" />
                        @endfor
                    @endisset
                </form>
            </tbody>
        </table>
        <div class="alert alert-dismissible fade show" role="alert">
            Total de <strong>{{ count($recibos) }}</strong> Resultados Encontrados.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <ul class="nav">
            <li class="nav-item">
                <div class="nav-link cursor-pointer" onclick="SelectAll()">Todos</div>
            </li>
            <li class="nav-item">
                <div class="nav-link cursor-pointer" onclick="DesselectAll()">Nenhum</div>
            </li>
        </ul>
    </div>
@endsection
@section('js')
    <script>
        function DesselectAll() {
            $("input[type='checkbox']").each(function() {
                if ($(this).is(":checked")) {
                    if ($(this).parent().parent().hasClass("bg-select-recibo")) {
                        $(this).parent().parent().removeClass("bg-select-recibo");
                    }
                    $(this).prop("checked", false);
                }
            });
            $("#btn_imprimir").hide();
        }

        function SelectAll() {
            $("input[type='checkbox']").each(function() {
                if (!$(this).is(":checked")) {
                    if (!$(this).parent().parent().hasClass("bg-select-recibo")) {
                        $(this).parent().parent().addClass("bg-select-recibo");
                    }
                    $(this).prop("checked", true);
                }
            });
            $("#btn_imprimir").show();
        }
        $(".data").datepicker({
            format: "dd/mm/yyyy",
            weekStart: 0,
            autoclose: true,
            todayHighlight: true,
            orientation: "auto",
            language: "pt-BR",

        }).change(function() {});

        function selectRecibos(este) {
            let check = false;
            $("input[type='checkbox']").each(function() {
                if ($(this).is(":checked")) {
                    check = true;
                    $(this).parent().parent().addClass("bg-select-recibo");
                } else {
                    if ($(this).parent().parent().hasClass("bg-select-recibo")) {
                        $(this).parent().parent().removeClass("bg-select-recibo");
                    }
                }
            });
            if (check) {
                if (!$("#btn_imprimir").is(":visible")) {
                    $("#btn_imprimir").show();
                }
            } else {
                $("#btn_imprimir").hide();
            }
        }

        function imprimirSelecionados() {
            $("#formImprimir").submit();
        }

        function findWithDate() {
            if ($("input[name='data_start']").val() !== "" && $("input[name='data_end']").val() !== "") {
                $("#formFindDate").submit();
            }
        }
    </script>
@endsection
