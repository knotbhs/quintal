<nav class="navbar mt-2 text-center p-4 bg-light">
    <div>
        <a class="text-sm-center btn btn-outline-primary {{ $btnactive == 'recibo' ? 'active' : '' }}"
            aria-current="page" href="{{ route('recibos.novo') }}">Novo
            Recibo</a>
        <a class="text-sm-center btn btn-outline-primary {{ $btnactive == 'relatorio' ? 'active' : '' }}"
            href="{{ route('recibos.index') }}">Relatórios</a>
    </div>
    <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Procurar" aria-label="Search">
        <button class="btn btn-outline-info" type="submit">Enviar</button>
    </form>
</nav>
