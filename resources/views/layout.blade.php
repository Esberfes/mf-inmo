<!doctype html>
<html lang="es" prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="index,follow" />

    @section('rrss')
    <!-- RRSS -->
    <meta property="og:locale" content="es_ES" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="1er Portal inmobiliario para franquicias. Con la garantía de mundoFranquicia." />
	<meta property="og:description" content="1er Portal inmobiliario para franquicias. Con la garantía de mundoFranquicia." />
	<meta property="og:url" content="{{ url(Request::url()) }}" />
	<meta property="og:site_name" content="mfinmobiliaria" />
	<meta property="og:image" content="{{asset('img/card-mf.jpg')}}" />
	<meta property="og:image:secure_url" content="{{asset('img/card-mf.jpg')}}" />
	<meta property="og:image:type" content="image/jpeg" />
	<meta property="og:image:width" content="900" />
	<meta property="og:image:height" content="324" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:description" content="1er Portal inmobiliario para franquicias. Con la garantía de mundoFranquicia." />
	<meta name="twitter:title" content="1er Portal inmobiliario para franquicias. Con la garantía de mundoFranquicia." />
	<meta name="twitter:image" content="{{asset('img/card-mf.jpg')}}" />
	<link rel="canonical" href="{{ url(Request::url()) }}" />
    <!-- RRSS -->
    @show

    @section('meta_description')
	<meta name="description" content="1er Portal inmobiliario para franquicias. Con la garantía de mundoFranquicia." />
	<title>1er Portal inmobiliario para franquicias</title>
	@show

    <meta name="author" content="mundoFranquicia">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{asset('/img/favicon/ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#ffffff">
    <meta name="application-name" content="ProveedoresFranquicias"/>
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('/img/favicon/icon-57x57.png')}}">

    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('/img/favicon/icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('/img/favicon/icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/img/favicon/icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('/img/favicon/icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('/img/favicon/icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('/img/favicon/icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('/img/favicon/icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/img/favicon/icon-180x180.png')}}">


    <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('/img/pwa/android-icon-192x192-dunplab-manifest-15105.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/img/pwa/favicon-32x32-dunplab-manifest-15105.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('/img/pwa/favicon-96x96-dunplab-manifest-15105.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/img/pwa/favicon-16x16-dunplab-manifest-15105.png')}}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{asset('/img/pwa/android-icon-512x512-dunplab-manifest-15105.png')}}">
    <link rel="manifest" href="/manifest.json?3">

	<meta name="csrf-token" content="{{ csrf_token() }}" >
    @section('pwa')
    @show

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

    <!-- Base css layout -->
    <link rel="stylesheet" href="{{asset('css/layout.css')}}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
</head>
<body>
    <?php
    $session = Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER);
    ?>
    <header>
        <div class="container">
            <div class="header-wrapper d-flex justify-content-lg-between justify-content-center align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ url('/') }}"><img width="200px" class="logo" src="{{asset('img/card-mf.jpg')}}" alt=""></a>
                </div>
                <div class=" align-items-center d-lg-flex d-none">
                    <a href="{{ url('/admin') }}"><i style="font-size:25px; color:#FFFFFF;margin-right: 1.4rem;" class="fas fa-user-lock"></i></a>
                </div>
            </div>
        </div>
    </header>


    <main>
        <div id="form-search-wrapper-mask"></div>
        <div class="container form-search-wrapper">
            <section class="section-form-search">
                <div id="section-form-search-toggle" class="d-lg-none d-flex justify-content-between align-items-center">
                    <i class="fas fa-bars"></i>
                    <a href="{{ url('/') }}"><i class="fas fa-home"></i></a>
                </div>
                <form id="form-search" data-is-collapsed="false" action="/buscar" method="GET">
                    <div class="form-search">
                        <div class="row no-gutters">
                            <div class="col-lg col-12 form-search-input-wrapper">
                                <input name="busqueda" value="{{ $session != null ? $session->busqueda : '' }}" type="search" class="form-control" placeholder="Busqueda">
                            </div>
                            <div class="col-lg col-12 form-search-input-wrapper">
                                <select name="sector" class="custom-select">
                                    <option value="none">Sector (sin filtro)</option>
                                    @if(!empty($sectores))
                                        @foreach($sectores as $sector)
                                            @if($session->sector == $sector->id)
                                            <option selected value="{{ $sector->id }}">{{ $sector->titulo }}</option>
                                            @else
                                            <option value="{{ $sector->id }}">{{ $sector->titulo }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg col-12 form-search-input-wrapper">
                                <select name="poblacion" class="custom-select">
                                    <option value="none">Población (sin filtro)</option>
                                    @if(!empty($poblaciones))
                                        @foreach($poblaciones as $poblacion)
                                        @if($session->poblacion == $poblacion->id)
                                            <option selected value="{{ $poblacion->id }}">{{ $poblacion->nombre }}</option>
                                            @else
                                            <option value="{{ $poblacion->id }}">{{ $poblacion->nombre }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg col-12 form-search-input-wrapper">
                                <select name="precio" class="custom-select">
                                    <?php
                                    $precios = [1000,10000,20000,40000,80000,160000, 500000,1000000];
                                    ?>
                                    <option value="none">Precio máximo (sin filtro)</option>
                                    @foreach($precios as $precio)
                                    @if($session->precio == $precio)
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
                            <input name="actionSearch" type="hidden">
                        </div>
                    </div>

                    <div class="form-order-by">
                        <div class="mr-3 form-order-by-text">
                            Ordenar:
                        </div>
                        <button class="btn btn-secondary {{ $session->order == 'relevancia' ? 'active-button' : ''}}" name="relevancia" value="{{ $session->order_direction == 'asc' && $session->order == 'relevancia'? 'desc' : 'asc' }}">
                            Relevancia
                            @if($session->order == 'relevancia')
                                @if($session->order_direction == 'asc')
                                <i class="fas fa-arrow-up"></i>
                                @else
                                <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </button>
                        <button class="btn btn-secondary {{ $session->order == 'precio' ? 'active-button' : ''}}"  name="precioOrder" value="{{ $session->order_direction == 'asc' && $session->order == 'precio' ? 'desc' : 'asc' }}">
                            Precio
                            @if($session->order == 'precio')
                                @if($session->order_direction == 'asc')
                                <i class="fas fa-arrow-up"></i>
                                @else
                                <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </button>
                        <button class="btn btn-secondary {{ $session->order == 'superficie' ? 'active-button' : ''}}"  name="superficie" value="{{ $session->order_direction == 'asc' && $session->order == 'superficie' ? 'desc' : 'asc' }}">
                            Superficie
                            @if($session->order == 'superficie')
                                @if($session->order_direction == 'asc')
                                <i class="fas fa-arrow-up"></i>
                                @else
                                <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </button>
                        <input name="actionOrder" type="hidden">

                    </div>


                </form>
            </section>
        </div>
        <div class="container">
            @section('nosidebar')
            @show

            <div class="row">

                <div class="col-12 col-lg-9">
                    @yield('content')
                </div>

                <div class="col-12 col-lg-3">
                    <aside>
                        @section('sidebar')
                        @if(!empty($banners))
                        <section>
                            @foreach($banners as $banner)
                                <article style="margin-top: 1.5rem">
                                    <a href="{{ url('/directorio/'.$banner->local->url_amigable) }}">
                                        <img width="100%" alt="{{ $banner->local->titulo }}" src="{{ url($banner->ruta) }}">
                                    </a>
                                </article>
                            @endforeach
                            </section>
                        @endif
                        @show
                    </aside>
                </div>

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
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script>
        var form = $("#form-search");
        var buttonToggle = $("#section-form-search-toggle");
        var main = $("main");
        var mask = $("#form-search-wrapper-mask");

        if(isMobile()) {
            form.slideUp();
            form.attr("data-is-collapsed", "true")
            mask.hide();
        }

        $(window).resize(function() {
            if(isMobile()) {
                form.slideUp();
                form.attr("data-is-collapsed", "true")
                mask.hide();
                unBindEvents();
                bindEvents();
            } else{
                form.slideDown();
                form.attr("data-is-collapsed", "false")
                mask.show();
                unBindEvents();
            }
        });

        if(isMobile()) {
           bindEvents();
        }

        function bindEvents() {
            main.click(function(event){
                event.stopPropagation();
                form.slideUp();
                form.attr("data-is-collapsed", "true")
                mask.hide();
            });

            buttonToggle.click(function(event){
                event.stopPropagation();
                var isCollapsed = form.attr("data-is-collapsed");

                if(isCollapsed == 'true') {
                    form.slideDown();
                    form.attr("data-is-collapsed", "false")
                    mask.show(500);
                } else {
                    form.slideUp();
                    form.attr("data-is-collapsed", "true")
                    mask.hide();
                }
            });
        }

        function unBindEvents() {
            main.unbind();
            buttonToggle.unbind();
        }

        function isMobile() {
            return window.matchMedia("only screen and (max-width: 991px)").matches;
        }

        $("#section-form-search-toggle a").click(function(event) {
            event.stopPropagation();
        });

    </script>

</body>

</html>
