<!doctype html>
<html lang="es" prefix="og: http://ogp.me/ns#">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
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
    <meta name="application-name" content="ProveedoresFranquicias" />
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('/img/favicon/icon-57x57.png')}}">

    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('/img/favicon/icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('/img/favicon/icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/img/favicon/icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('/img/favicon/icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('/img/favicon/icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('/img/favicon/icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('/img/favicon/icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/img/favicon/icon-180x180.png')}}">


    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('/img/pwa/android-icon-192x192-dunplab-manifest-15105.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/img/pwa/favicon-32x32-dunplab-manifest-15105.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('/img/pwa/favicon-96x96-dunplab-manifest-15105.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/img/pwa/favicon-16x16-dunplab-manifest-15105.png')}}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{asset('/img/pwa/android-icon-512x512-dunplab-manifest-15105.png')}}">
    <link rel="manifest" href="/manifest.json?3">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @section('pwa')
    <script type="module" src="{{ asset('/pwabuilder-sw-register.js') }}"></script>
    @show

    <!-- Base css layout -->
    <link rel="stylesheet" href="{{asset('css/layout.css')}}">

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

    <!-- Font -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Titillium+Web" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web"></noscript>

    <link rel="preload" href="{{asset('css/font-awesome.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}"></noscript>
</head>

<body>
    <?php
    $session = Session::get(\App\Constants\SessionConstants::USER_LOCALES_FILTER);
    ?>
    <header>
        <div class="container">
            <div class="header-wrapper d-flex justify-content-lg-between justify-content-center align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ url('/') }}"><img width="200px" class="logo" src="{{asset('img/logo-mundofranquicia-2020.png')}}" alt="logo-inmobiliaria"></a>
                </div>
                <div class=" align-items-center d-lg-flex d-none">
                    <a class="d-none" href="{{ url('/admin') }}"><i class="fas fa-user-lock"></i></a>
                </div>
            </div>
        </div>
        <div class="header-footer"></div>
    </header>

    <nav class="container form-search-wrapper">
        <section class="section-form-search">
            <div id="section-form-search-toggle" class="d-lg-none d-flex justify-content-between align-items-center">
                <i class="fas fa-bars"></i>
                <a href="{{ url('/') }}"><i class="fas fa-home"></i></a>
            </div>
            <form id="form-search" data-is-collapsed="false" action="/buscar" method="GET">
                <div class="form-search">
                    <div class="row no-gutters">
                        <div class="col-lg col-12 form-search-input-wrapper">
                            <label for="busqueda">Busqueda</label>
                            <input name="busqueda" value="{{ $session != null ? $session->busqueda : '' }}" type="search" class="form-control" placeholder="Busqueda">
                        </div>
                        <div class="col-lg col-12 form-search-input-wrapper">
                            <label for="poblacion">Población</label>
                            <div class="select-wrapper">
                                <select name="poblacion" class="custom-select">
                                    <option value="none"></option>
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
                        </div>
                        <div class="col-lg col-12 form-search-input-wrapper">
                            <label for="sector">Sector</label>
                            <div class="select-wrapper">
                                <select name="sector" class="custom-select">
                                    <option value="none"></option>
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
                        </div>

                        <div id="precio" class="col-lg col-12 form-search-input-wrapper">
                            <label for="precio">Precio máximo compra</label>
                            <div class="select-wrapper">
                                <select name="precio" class="custom-select">
                                    <option value="none"></option>
                                    @foreach(\App\Constants\SessionConstants::FILTRO_PRECIOS_COMPRA as $precio => $v)
                                    @if($session->precio == $v)
                                    <option selected value="{{ $v}}">{{ $precio }} €</option>
                                    @else
                                    <option value="{{ $v }}">{{ $precio }} €</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="precio_alquiler" class="col-lg col-12 form-search-input-wrapper">
                            <label for="precio_alquiler">Precio máximo alquiler</label>
                            <div class="select-wrapper">
                                <select name="precio_alquiler" class="custom-select">
                                    <option value="none"></option>
                                    @foreach(\App\Constants\SessionConstants::FILTRO_PRECIOS_ALQUILER as $precio => $v)
                                    @if($session->precio_alquiler == $v)
                                    <option selected value="{{ $v}}">{{ $precio }} €</option>
                                    @else
                                    <option value="{{ $v }}">{{ $precio }} €</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg col-12 form-search-input-wrapper">
                            <label for="precio_alquiler">Filtro compra/alquiler</label>
                            <select name="mostrar_compra_alquiler" class="custom-select">
                                <option {{ $session->mostrar_compra_alquiler == -1 ? 'selected' : '' }} value="-1">Compra y alquiler</option>
                                <option {{ $session->mostrar_compra_alquiler == 0 ? 'selected' : '' }} value="0">Compra</option>
                                <option {{ $session->mostrar_compra_alquiler == 1 ? 'selected' : '' }} value="1">Alquiler</option>
                            </select>
                        </div>
                        <input name="actionSearch" type="hidden">
                    </div>
                </div>

                <div class="form-order-by row no-gutters">
                    <div class="mr-3 form-order-by-text">
                        Ordenar:
                    </div>
                    <div class="col-12 col-lg d-flex">
                        <button class="btn btn-secondary flex-basis-0 flex-grow-1 flex-shrink-1 {{ $session->order == 'relevancia' ? 'active-button' : ''}}" name="relevancia" value="{{ $session->order_direction == 'asc' && $session->order == 'relevancia'? 'desc' : 'asc' }}">
                            Relevancia
                            @if($session->order == 'relevancia')
                            @if($session->order_direction == 'asc')
                            <i class="fas fa-arrow-up"></i>
                            @else
                            <i class="fas fa-arrow-down"></i>
                            @endif
                            @endif
                        </button>
                        <button class="btn btn-secondary flex-basis-0  flex-grow-1 flex-shrink-1 {{ $session->order == 'precio' ? 'active-button' : ''}}" name="precioOrder" value="{{ $session->order_direction == 'asc' && $session->order == 'precio' ? 'desc' : 'asc' }}">
                            Precio
                            @if($session->order == 'precio')
                            @if($session->order_direction == 'asc')
                            <i class="fas fa-arrow-up"></i>
                            @else
                            <i class="fas fa-arrow-down"></i>
                            @endif
                            @endif
                        </button>
                        <button class="btn btn-secondary flex-basis-0  flex-grow-1 flex-shrink-1 {{ $session->order == 'superficie' ? 'active-button' : ''}}" name="superficie" value="{{ $session->order_direction == 'asc' && $session->order == 'superficie' ? 'desc' : 'asc' }}">
                            Superficie
                            @if($session->order == 'superficie')
                            @if($session->order_direction == 'asc')
                            <i class="fas fa-arrow-up"></i>
                            @else
                            <i class="fas fa-arrow-down"></i>
                            @endif
                            @endif
                        </button>
                    </div>

                    <input name="actionOrder" type="hidden">
                    <div class="col-12 col-lg form-search-button-wrapper pr-0 mt-lg-0 mt-3">
                        <button class="btn btn-secondary  justify-content-center">Encontrar <i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </section>
    </nav>

    <main>
        <div id="form-search-wrapper-mask"></div>
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
                        <section>
                            <form class="form-solicitud" method="POST" action="{{ url('/solicitud') }}">
                                <div class="form-solicitud-header position-static"><i class="far fa-id-card"></i><h2>Trabaja con nostros</h2></div>
                                <div class="form-solicitud-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input required placeholder="Nombre" type="text" class="form-control" name="nombre" value="{{ old('nombre') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input required placeholder="Email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input required placeholder="Teléfono" type="tel" class="form-control" name="telefono" value="{{ old('telefono') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <textarea placeholder="Comentario" class="form-control" rows="3" name="comentario">{{ old('comentario') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group d-flex align-items-center">
                                                <input required type="checkbox" aria-label="Política de privacidad"> <small class="ml-2">He leido y acepto la <a href="https://www.mundofranquicia.com/aviso-legal/">política de privacidad</a> </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                            <button class="btn btn-secondary">Conectar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    @if ($errors->any())
                                    <div class="blanco mt-3">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    @if(session()->has('success'))
                                    <div class="alert alert-success mt-3">
                                        {{ session()->get('success') }}
                                    </div>
                                    @endif
                                </div>

                            </form>
                        </section>
                        @if(!empty($banners))
                        <section>
                            @foreach($banners as $banner)
                            <article style="margin-top: 1.5rem">
                                <a href="{{ url('/directorio/'.$banner->local->url_amigable) }}">
                                    <img class="lozad" width="100%" alt="{{ $banner->local->titulo }}" data-src="{{ url($banner->ruta) }}">
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
                <div> <a target="_blank" href="https://www.mundofranquicia.com/aviso-legal/">Política de privacidad</a>
                </div>
                <span>|</span>
                <div> <a target="_blank" href="https://www.mundofranquicia.com/aviso-legal/">Política de Cookies</a>
                </div>
            </div>
            <div class="footer-logos row">
                <div class="col-lg-2 footer-logo">
                    <img alt="logo-20-anos-mundofranquicia" class="lozad" data-src="{{asset('img/footer/logo20anyos.png')}}" alt="">
                </div>
                <div class="col-lg-4 footer-logo">
                    <img alr="logo-mundofranquicia" class="lozad" data-src="{{asset('img/footer/logo-mundofranquicia-negativo.png')}}" alt="">
                </div>
                <div class="col-lg-6 footer-logo">
                    <img alt="logo-miembro-de-aef-aemme-gnf" class="lozad" data-src="{{asset('img/footer/logos-footer-membersof-mf.png')}}" alt="">
                </div>
            </div>
        </div>
    </footer>
    <!-- Bootstrap -->
    <script async src="{{ mix('js/app.js') }}"></script>
</body>

</html>
