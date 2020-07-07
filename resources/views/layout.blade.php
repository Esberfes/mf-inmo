<!doctype html>
<html lang="es" prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="index,follow" />

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
    <header>

    </header>

    <main class="container">
        <section>
            <form class="form-search" action="">
                <div class="row">
                    <div class="col form-search-input-wrapper">
                    <input type="text" class="form-control" placeholder="Busqueda">
                    </div>
                    <div class="col form-search-input-wrapper">
                        <select class="custom-select" id="inlineFormCustomSelectPref">
                            <option selected>Sector</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                <div class="col form-search-button-wrapper">
                    <button>Encontrar</button>
                </div>
            </div>
            </form>
            <form class="form-order-by" action="/" method="POST">
                <button name="relevancia">Relevancia</button>
                <button name="barato">Barato</button>
                <button name="reciente">Recientes</button>
                <input name="action" value="order" type="hidden">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </section>
        @section('nosidebar')
		@show

        <div class="row">

            <div class="col-12 col-lg-9">
				@yield('content')
			</div>

			<div class="col-12 col-lg-3">
				<aside>
                    @section('sidebar')
                    <section>
                        <article style="margin-top: 1.5rem">
                            <img width="100%" src="http://lorempixel.com/300/700?1" alt="">
                        </article>
                        <article style="margin-top: 1.5rem">
                            <img width="100%" src="http://lorempixel.com/300/700?2" alt="">
                        </article>
                    </section>
                    @show
				</aside>
			</div>

        </div>

    </main>

    <footer>
        footer
    </footer>

    <!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>
