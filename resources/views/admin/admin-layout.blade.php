<!doctype html>
<html lang="es" prefix="og: http://ogp.me/ns#">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex,nofollow" />

    <link rel="canonical" href="{{ url(Request::url()) }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- Base css layout -->
    <link rel="stylesheet" href="{{asset('css/layout.css')}}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
</head>

<body>
    @section('nonav')
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-5 sticky-top">

        <form style="position: absolute;right: 15px;" action="{{ url('/admin/logout') }}" method="post">
            <button style="background:transparent;border:none;"><i style="color:#FFFFFF;"
                    class="fas fa-power-off"></i></button>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>

        <a class="navbar-brand" href="{{ url('/') }}"><i class="fas fa-home"></i></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNavbar"
            aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <div class="navbar-nav">
                <x-dropdown name="Test" link-list="/" link-create="/" />
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownLocales"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Locales
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownLocales">
                        <a class="dropdown-item" href="{{ url('/admin/locales') }}">Listar</a>
                        <a class="dropdown-item" href="{{ url('/admin/locales/crear') }}">Crear</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownSectores"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sectores
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownSectores">
                        <a class="dropdown-item" href="{{ url('/admin/sectores') }}">Listar</a>
                        <a class="dropdown-item" href="{{ url('/admin/sectores/crear') }}">Crear</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownPoblaciones"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Poblaciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownPoblaciones">
                        <a class="dropdown-item" href="{{ url('/admin/poblaciones') }}">Listar</a>
                        <a class="dropdown-item" href="{{ url('/admin/poblaciones/crear') }}">Crear</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownSolicitudes"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Solicitudes
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownSolicitudes">
                        <a class="dropdown-item" href="{{ url('/admin/solicitudes') }}">Listar</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownUsuarios"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Usuarios
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownUsuarios">
                        <a class="dropdown-item" href="{{ url('/admin/usuarios') }}">Listar</a>
                        <a class="dropdown-item" href="{{ url('/admin/usuarios/crear') }}">Crear</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @show
    <main class="container-fluid" id="app">
        @yield('content')

        <div data-gridId="locales" class="react-data-grid"></div>
    </main>

    <link rel="stylesheet" href="{{ asset('css/file-uploader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/multi-select.dist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui-timepicker-addon.css') }}">

    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/file-uploader.js') }}"></script>
    <script src="{{ asset('js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-timepicker-addon.js') }}"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="{{ mix('/js/app.js') }}"></script>
    <script>
    $(".btn-danger,.btn-outline-danger").click(function() {
        if (confirm("Deseas eliminar el registro?")) return true;
        return false;
    });
    $(".alert.alert-success").delay(8000).fadeOut(500);
    $(".alert.alert-danger").delay(8000).fadeOut(500);
    </script>
    @section('scripts')
    @show
</body>

</html>
