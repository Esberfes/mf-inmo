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
        <div class="container">
            <div class="header-wrapper d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ url('/') }}"><img width="200px" class="logo" src="{{asset('img/card-mf.jpg')}}" alt=""></a>
                </div>
                <div class="d-flex align-items-center">
                    <a href="{{ url('/admin') }}"><i style="font-size:25px; color:#FFFFFF;margin-right: 1.4rem;" class="fas fa-user-lock"></i></a>
                </div>
            </div>
        </div>
    </header>


    <main class="container">
        <section class="section-form-search">
            <form class="form-search" action="/" method="POST">
                <div class="row no-gutters">
                    <div class="col form-search-input-wrapper">
                        <input name="busqueda" value="{{ Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->busqueda }}" type="search" class="form-control" placeholder="Busqueda">
                    </div>
                    <div class="col form-search-input-wrapper">
                        <select name="sector" class="custom-select">
                            <option value="none">Sector (sin filtro)</option>
                            @if(!empty($sectores))
                                @foreach($sectores as $sector)
                                    @if(Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->sector == $sector->id)
                                    <option selected value="{{ $sector->id }}">{{ $sector->titulo }}</option>
                                    @else
                                    <option value="{{ $sector->id }}">{{ $sector->titulo }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col form-search-input-wrapper">
                        <select name="poblacion" class="custom-select">
                            <option value="none">Población (sin filtro)</option>
                            @if(!empty($poblaciones))
                                @foreach($poblaciones as $poblacion)
                                @if(Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->poblacion == $poblacion->id)
                                    <option selected value="{{ $poblacion->id }}">{{ $poblacion->nombre }}</option>
                                    @else
                                    <option value="{{ $poblacion->id }}">{{ $poblacion->nombre }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col form-search-input-wrapper">
                        <select name="precio" class="custom-select">
                            <?php
                            $precios = [1000,10000,20000,40000,80000,160000, 500000,1000000];
                            ?>
                            <option value="none">Precio máximo (sin filtro)</option>
                            @foreach($precios as $precio)
                            @if(Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->precio == $precio)
                                    <option selected value="{{ $precio }}">{{ $precio }} €</option>
                                    @else
                                    <option value="{{ $precio }}">{{ $precio }} €</option>
                             @endif
                            @endforeach
                        </select>
                    </div>
                <div class="col form-search-button-wrapper">
                    <button class="btn btn-secondary" >Encontrar</button>
                </div>
                <input name="action" value="search" type="hidden">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
            </form>
            <form class="form-order-by" action="/" method="POST">
                <div class="mr-3">
                    Ordenar:
                </div>
                <button class="btn btn-secondary {{ Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order == 'relevancia' ? 'active-button' : ''}}" name="relevancia" value="{{ Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order_direction == 'asc' && Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order == 'relevancia'? 'desc' : 'asc' }}">
                    Relevancia
                    @if(Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order == 'relevancia')
                        @if(Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order_direction == 'asc')
                        <i class="fas fa-arrow-up"></i>
                        @else
                        <i class="fas fa-arrow-down"></i>
                        @endif
                    @endif
                </button>
                <button class="btn btn-secondary {{ Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order == 'precio' ? 'active-button' : ''}}"  name="precio" value="{{ Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order_direction == 'asc' && Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order == 'precio' ? 'desc' : 'asc' }}">
                    Precio
                    @if(Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order == 'precio')
                        @if(Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order_direction == 'asc')
                        <i class="fas fa-arrow-up"></i>
                        @else
                        <i class="fas fa-arrow-down"></i>
                        @endif
                    @endif
                </button>
                <button class="btn btn-secondary {{ Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order == 'superficie' ? 'active-button' : ''}}"  name="superficie" value="{{ Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order_direction == 'asc' && Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order == 'superficie' ? 'desc' : 'asc' }}">
                    Superficie
                    @if(Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order == 'superficie')
                        @if(Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER)->order_direction == 'asc')
                        <i class="fas fa-arrow-up"></i>
                        @else
                        <i class="fas fa-arrow-down"></i>
                        @endif
                    @endif
                </button>
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
        <div class="container">
            <div class="footer-links">
                <div>© 2020</div>
                <span>|</span>
                <div> <a target="_blank" href="https://www.mundofranquicia.com/aviso-legal/">Aviso legal</a></div>
                <span>|</span>
                <div> <a target="_blank" href="https://www.mundofranquicia.com/aviso-legal/">Política de privacidad</a></div>
                <span>|</span>
                <div> <a target="_blank" href="https://www.mundofranquicia.com/aviso-legal/">Política de Cookies</a></div>
            </div>

            <div class="footer-logos row">
                <div class="col-lg-2 footer-logo">
                    <img src="{{asset('img/footer/logo20anyos.png')}}" alt="">
                </div>
                <div class="col-lg-4 footer-logo">
                    <img src="{{asset('img/footer/logo-mundofranquicia-negativo.png')}}" alt="">
                </div>
                <div class="col-lg-6 footer-logo">
                    <img src="{{asset('img/footer/logos-footer-membersof-mf.png')}}" alt="">
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap -->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>
