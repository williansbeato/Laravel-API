<html>
    <head>
        <title>VetClin @yield('titulo')</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <style>
            body{ padding:40px;}
            .navbar{margin-bottom:30px;}
            .card{margin:20px;}
            .card-header{color: #000;}
        </style>
    </head>

    <body>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <a class="navbar-brand" href="#"><b>VetClin System</b></a>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li @if($tag=="CLI") class="nav-item active" @else class="nav-item" @endif>
                        <a class="nav-link" href="{{ route('cliente.index') }}">
                            <b>Clientes</b>
                        </a>
                    </li>
                    <li @if($tag=="VET") class="nav-item active" @else class="nav-item" @endif>
                        <a class="nav-link" href="{{ route('veterinario.index')}}">
                            <b>Veterinários</b>
                        </a>
                    </li>
                    <li @if($tag=="ESP") class="nav-item active" @else class="nav-item" @endif>
                        <a class="nav-link" href="{{ route('especialidade.index') }}">
                            <b>Especialidades</b>
                        </a>
                    </li>
                </ul>
            </div>

        </nav>

        <div class="card">
            <div class="card-header bg-dark">
                <h3><b style="color: white";>{{ $titulo }}</b></h2>
            </div>
            <div class="card-body">
                @yield("conteudo")
            </div>
        </div>

    </body>
    <footer>
        <b>&copy;2021 - Willians Beato.</b>
    </footer>

    <script src="{{asset('js/app.js')}}" type="text/javascript"></script>

    @yield('script')


</html>