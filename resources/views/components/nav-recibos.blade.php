<nav class="navbar mt-2 text-center p-4 bg-light">
    <div>
        <a class="text-sm-center btn btn-outline-primary {{ $btnactive == 'recibo' ? 'active' : '' }}"
            aria-current="page" href="{{ route('recibos.novo') }}">Novo
            Recibo</a>
        <a class="text-sm-center btn btn-outline-primary {{ $btnactive == 'relatorio' ? 'active' : '' }}"
            href="{{ route('recibos.index') }}">Relatórios</a>
        <div style="display: none;" id="btn_imprimir" class="text-sm-center btn btn-outline-secondary"
            onclick="imprimirSelecionados()">Imprimir</div>
    </div>

    {!! Form::open([
        'url' => route('recibos.search.all'),
        'method' => 'POST',
        'class' => 'd-flex ',
        'id' => 'formFindDate',
    ]) !!}
    <div class="input-group {{ $btnactive == 'recibo' ? 'd-none' : '' }}">
        <input style="max-width: 130px;" onchange="findWithDate()" autocomplete="off" name="data_start"
            value="{{ isset($datastart) ? $datastart : '' }}" class="form-control me-2 data" type="search"
            placeholder="Data Inicial" aria-label="Search">
        <input style="max-width: 130px;" onchange="findWithDate()" autocomplete="off"
            value="{{ isset($dataend) ? $dataend : '' }}" name="data_end" class="form-control me-4 data"
            type="search" placeholder="Data Final" aria-label="Search">
    </div>
    <div class="input-group border-start border-secondary  {{ $btnactive == 'recibo' ? 'd-none' : '' }}">
        <select name="pesquisarpor" class="form-select form-control me-2 ms-4" aria-label="Default select example">
            <option value="">Pesquisar por:</option>
            <option {{ $pesquisarpor == 'colaborador' ? 'selected' : '' }} value="colaborador">Colaborador</option>
            <option {{ $pesquisarpor == 'servico' ? 'selected' : '' }} value="servico">Serviço Prestado</option>
            <option {{ $pesquisarpor == 'all' ? 'selected' : '' }} value="all">Ambos</option>
        </select>
        <input class="form-control me-2" value="{{ isset($texto) ? $texto : '' }}" type="search" name="texto"
            placeholder="Procurar" autocomplete="off" aria-label="Search">
        <button class="btn btn-outline-info" type="submit">Enviar</button>
    </div>
    {!! Form::close() !!}
</nav>
