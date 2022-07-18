@extends('welcome')
@section('body')
    @csrf
    <div class="container p-4">
        <x-navRecibos :btnactive="$btnactive" :datastart="isset($datastart) ? $datastart : null" :dataend="isset($dataend) ? $dataend : null" :texto="isset($texto) ? $texto : null" :pesquisarpor="isset($pesquisarpor) ? $pesquisarpor : null" />
        <div class="mt-3 p-4 bg-light">
            {!! Form::open([
                'url' => route('recibos.salvar'),
                'method' => 'POST',
                'class' => 'm-0 p-0',
                'id' => 'formSave',
            ]) !!}
            <div class="mb-3 row">
                @include('form.input', [
                    'value' => isset($recibos['user']['name']) ? $recibos['user']['name'] : '',
                    'size' => 'col-6 col-lg-6 col-md-6 col-sm-6',
                    'id' => 'name',
                    'label' => 'Nome do Colaborador',
                    'class' => 'col-form-label form-control',
                    'attributes' => [
                        'placeholder' => '',
                        'oninput' => 'searchUser(this)',
                        'onfocus' => 'this.select()',
                        'autocomplete' => 'off',
                    ],
                ])
                @include('form.input', [
                    'value' => isset($recibos['user']['cpf']) ? $recibos['user']['cpf'] : '',
                    'size' => 'col-3 col-lg-3 col-md-3 col-sm-3',
                    'id' => 'cpf',
                    'label' => 'CPF do Colaborador',
                    'class' => 'col-form-label form-control',
                    'attributes' => ['placeholder' => '', 'onfocus' => 'this.select()', 'autocomplete' => 'off'],
                ])
                @include('form.input', [
                    'value' => isset($recibos['data']) ? $recibos['data'] : '',
                    'size' => 'col-3 col-lg-3 col-md-3 col-sm-3',
                    'id' => 'data',
                    'label' => 'Data da Execução',
                    'class' => 'col-form-label form-control',
                    'attributes' => ['placeholder' => '', 'onfocus' => 'this.select()', 'autocomplete' => 'off'],
                ])
                <div class="sugestion-row-up col-6 position-relative" style="display: none;">
                    <div class="list-group border border-light"
                        style=" max-height: 200px; overflow-y:auto; overflow-x: hidden; box-shadow: 0px 0px 1px 5px rgb(193,212,251);">
                    </div>
                    <span onclick="fecharSugestion1(this)"
                        class="cursor-pointer position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info">
                        X
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </div>
            </div>
            <div class="mb-3 row">
                @include('form.input', [
                    'value' => isset($recibos['servico']) ? $recibos['servico'] : '',
                    'size' => 'col-6 col-lg-6 col-md-6 col-sm-6',
                    'id' => 'servico',
                    'label' => 'Serviço Prestado',
                    'class' => 'col-form-label form-control',
                    'attributes' => [
                        'placeholder' => '',
                        'onfocus' => 'this.select()',
                        'oninput' => 'searchService(this)',
                        'autocomplete' => 'off',
                    ],
                ])
                @include('form.input', [
                    'value' => isset($recibos['valor']) ? $recibos['valor'] : '',
                    'size' => 'col-3 col-lg-3 col-md-3 col-sm-3',
                    'id' => 'valor',
                    'label' => 'Valor do Serviço R$',
                    'class' => 'col-form-label form-control',
                    'attributes' => ['placeholder' => '', 'onfocus' => 'this.select()', 'autocomplete' => 'off'],
                ])

                <div class="sugestion-row-middle col-6 position-relative" style="display: none;">
                    <div class="list-group border border-light"
                        style=" max-height: 200px; overflow-y:auto; overflow-x: hidden; box-shadow: 0px 0px 1px 5px rgb(193,212,251);">
                    </div>
                    <span onclick="fecharSugestion1(this)"
                        class="cursor-pointer position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info">
                        X
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </div>
            </div>
            <div class="mb-3">
                @include('form.textarea', [
                    'value' => isset($recibos['descricao']) ? $recibos['descricao'] : '',
                    'size' => 'col-12 col-lg-12 col-md-12 col-sm-12',
                    'id' => 'descricao',
                    'label' => 'Descrição:',
                    'class' => 'col-form-label form-control',
                    'attributes' => ['rows' => 2, 'cols' => 45, 'placeholder' => ''],
                ])

                <input type="hidden" name="id" value="{{ isset($recibos['id']) ? $recibos['id'] : '' }}">
                <input type="hidden" name="formato" value="save">
            </div>
            @if (Session::has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::has('message') ? Session::get('message') : '' }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {!! Form::close() !!}
            <nav class="navbar mt-2 text-center bg-light">
                <a class="text-sm-center btn btn-outline-secondary" aria-current="page"
                    href="{{ route('recibos.index') }}">Voltar</a>
                <div class="botaoApagar {{ !isset($recibos['id']) ? 'd-none' : '' }}">
                    <div class="text-sm-center btn btn-outline-danger btnApagar" aria-current="page"
                        onclick="msgApagarRecibo(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-trash-fill" viewBox="0 0 16 16">
                            <path
                                d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                        </svg> Deletar Recibo
                    </div>

                    {!! Form::open([
                        'url' => route('recibos.deletar'),
                        'method' => 'POST',
                        'class' => 'm-0 p-0',
                        'id' => 'formDelete',
                    ]) !!}
                    <div class="botoesConfirmacao d-none">

                        <button type="submit" class="text-sm-center btn btn-outline-danger" aria-current="page"
                            onclick="confirmacaoSimDeletar(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-trash-fill" viewBox="0 0 16 16">
                                <path
                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                            </svg> SIM, DELETAR!
                        </button>
                        <div class="text-sm-center btn btn-outline-secondary" aria-current="page"
                            onclick="confirmacaoNaoDeletar(this)">NÃO DELETAR
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{ isset($recibos['id']) ? $recibos['id'] : '' }}">
                    {!! Form::close() !!}

                </div>
                <div>
                    <div class="{{ Session::has('message') ? '' : 'd-none' }} text-sm-center btn btn-outline-success"
                        onclick="imprimirThis({{ isset(Session::get('recibos')['id']) ? Session::get('recibos')['id'] : '' }})">
                        Imprimir</div>
                    <button type="button" class="text-sm-center btn btn-outline-primary"
                        onclick="saveThis(this)">Salvar</button>
                </div>
            </nav>
        </div>
    </div>

    <form target="_blank" action="{{ route('recibos.imprimir.selecionados') }}" method="post" id="formImprimir">
        @csrf
    </form>
@endsection
@section('js')
    <script>
        var request;
        var time;

        $("input[name='data']").datepicker({
            format: "dd/mm/yyyy",
            weekStart: 0,
            autoclose: true,
            todayHighlight: true,
            orientation: "auto",
            language: "pt-BR",

        }).change(function() {
            $("#servico").focus();
        });

        function fecharSugestion1(este) {
            $(este).parent().hide();
        }

        function searchUser(este) {
            clearTimeout(time);
            time = setTimeout(() => {
                let val = $(este).val();
                let link = "{{ route('recibos.search') }}";
                let data = {
                    'name': val,
                    '_token': $('input[name="_token"]').val()
                };
                request = $.post({
                    url: link,
                    data: data,
                    success: function(content) {
                        $(".sugestion-row-up").find("div.list-group").html("");
                        if (content.length < 1) {
                            $(".sugestion-row-up").hide();
                        } else {
                            for (let index = 0; index < content.length; index++) {
                                const element = content[index];
                                let first = element['name'].toUpperCase().indexOf(val.toUpperCase());
                                let last = first + val.length;
                                let replaced = element['name'].toUpperCase().replace(val
                                    .toUpperCase(), "<strong>" + val.toUpperCase() +
                                    "</strong>");

                                let ahref =
                                    "<a href='javascript:selectUser(" + JSON.stringify(element) +
                                    ")' class='list-group-item list-group-item-action'>" +
                                    replaced + "<input type='hidden' name='dados' value='" +
                                    JSON.stringify(element) + "' /></a>";
                                $(".sugestion-row-up").find("div.list-group").append(ahref);
                            }
                            $(".sugestion-row-up").show();
                        }
                    }
                });
            }, 150);

        }

        function searchService(este) {
            clearTimeout(time);
            time = setTimeout(() => {
                let val = $(este).val();
                let link = "{{ route('recibos.search.servico') }}";
                let data = {
                    'servico': val,
                    '_token': $('input[name="_token"]').val()
                };
                request = $.post({
                    url: link,
                    data: data,
                    success: function(content) {
                        $(".sugestion-row-middle").find("div.list-group").html("");
                        if (content.length < 1) {
                            $(".sugestion-row-middle").hide();
                        } else {
                            for (let index = 0; index < content.length; index++) {
                                const element = content[index];
                                let first = element['name'].toUpperCase().indexOf(val.toUpperCase());
                                let last = first + val.length;
                                let replaced = element['name'].toUpperCase().replace(val
                                    .toUpperCase(), "<strong>" + val.toUpperCase() +
                                    "</strong>");
                                let ahref =
                                    "<a href='javascript:selectService(" + JSON.stringify(element) +
                                    ")' class='list-group-item list-group-item-action'>" +
                                    replaced + "<input type='hidden' name='dados' value='" +
                                    JSON.stringify(element) + "' /></a>";
                                $(".sugestion-row-middle").find("div.list-group").append(ahref);
                            }
                            $(".sugestion-row-middle").show();
                        }
                    }
                });
            }, 150);
        }

        function selectUser(este) {
            $("input[name='name']").val(este.name);
            $("input[name='cpf']").val(este.cpf);
            $(".sugestion-row-up").hide();
            $("input[name='data']").focus();
        }

        function selectService(este) {
            $("input[name='servico']").val(este.name);
            $("input[name='valor']").val(este.valor);
            $("textarea[name='descricao']").val(este.descricao);
            $(".sugestion-row-middle").hide();
            $("input[name='valor']").focus();
        }

        function imprimirThis(id) {
            $("#formImprimir").append("<input type='hidden' name='select[]' value='" + id + "' />");
            $("#formImprimir").submit();
        }

        function saveThis(este) {
            $("#formSave").submit();
            $(este).attr("disabled", true);
        }
        var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
            keyboard: false
        })

        function msgApagarRecibo(este) {
            $(".botoesConfirmacao").removeClass("d-none");
            $(".btnApagar").addClass("d-none");
        }

        function confirmacaoNaoDeletar(este) {
            $(".botoesConfirmacao").addClass("d-none");
            $(".btnApagar").removeClass("d-none");

        }

        function confirmacaoSimDeletar(este) {
            $(".botoesConfirmacao").addClass("d-none");
            $(".btnApagar").removeClass("d-none");
        }
    </script>
@endsection
