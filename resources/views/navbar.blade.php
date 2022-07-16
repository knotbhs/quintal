<nav class="navbar navbar-light bg-light shadow">
    <div class="container-fluid">
        <a href="{{ isset($link_header) ? $link_header : url('/') }}"
            class="navbar-brand mb-0 h1">{{ isset($title) ? $title : env('APP_NAME') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">MENU</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a class="text-decoration-none"
                            href="{{ route('recibos.index') }}">Recibos</a></li>
                    <li class="list-group-item"><a class="text-decoration-none"
                            href="{{ route('recibos.index') }}">Relatórios</a></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
