<!doctype html>
<html lang="es" prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex,nofollow" />

	<link rel="canonical" href="{{ url(Request::url()) }}" />

	<meta name="csrf-token" content="{{ csrf_token() }}" >

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- Base css layout -->
    <link rel="stylesheet" href="{{asset('css/layout.css')}}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-5">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="{{ url('/admin/locales') }}">Locales</a>
                <a class="nav-item nav-link" href="{{ url('/admin/sectores') }}">Sectores</a>
                <a class="nav-item nav-link" href="{{ url('/admin/poblaciones') }}">Poblaciones</a>
                <a class="nav-item nav-link" href="{{ url('/admin/solicitudes') }}">Solicitudes</a>
                <a class="nav-item nav-link" href="{{ url('/admin/usuarios') }}">Usuarios</a>
            </div>
        </div>
    </nav>

    <main class="container-fluid">
    @yield('content')
    </main>

    <!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>
