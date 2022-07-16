@extends('welcome')
@section('body')
    @csrf
    <div class="container p-4">
        <x-navRecibos :btnactive="$btnactive" />
        <div class="mt-3 p-4 bg-light">
            {!! Form::open([
                'url' => route('recibos.salvar'),
                'method' => 'POST',
                'class' => 'm-0 p-0',
                'target' => '_blank',
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
                    ],
                ])
                @include('form.input', [
                    'value' => isset($recibos['user']['cpf']) ? $recibos['user']['cpf'] : '',
                    'size' => 'col-3 col-lg-3 col-md-3 col-sm-3',
                    'id' => 'cpf',
                    'label' => 'CPF do Colaborador',
                    'class' => 'col-form-label form-control',
                    'attributes' => ['placeholder' => '', 'onfocus' => 'this.select()'],
                ])
                @include('form.input', [
                    'value' => isset($recibos['data']) ? $recibos['data'] : '',
                    'size' => 'col-3 col-lg-3 col-md-3 col-sm-3',
                    'id' => 'data',
                    'label' => 'Data da Execução',
                    'class' => 'col-form-label form-control',
                    'attributes' => ['placeholder' => '', 'onfocus' => 'this.select()'],
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
                    'attributes' => ['placeholder' => '', 'onfocus' => 'this.select()'],
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
            <nav class="navbar mt-2 text-center bg-light">
                <a class="text-sm-center btn btn-outline-secondary" aria-current="page"
                    href="{{ route('recibos.novo') }}">Voltar</a>
                <div>
                    <a class="text-sm-center btn btn-outline-primary" href="#">Imprimir</a>
                    <button type="submit" class="text-sm-center btn btn-outline-primary" aria-current="page"
                        href="{{ route('recibos.novo') }}">Salvar</button>
                </div>
            </nav>
            {!! Form::close() !!}
        </div>
    </div>
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
    </script>
@endsection
