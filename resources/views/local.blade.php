@extends('layout')

@section('rrss')
<!-- RRSS -->
<meta property="og:locale" content="es_ES" />
<meta property="og:type" content="website" />
<meta property="og:title" content="1er Portal inmobiliario para franquicias. Con la garantía de mundoFranquicia." />
<meta property="og:description" content="1er Portal inmobiliario para franquicias. Con la garantía de mundoFranquicia." />
<meta property="og:url" content="{{ url(Request::url()) }}" />
<meta property="og:site_name" content="mfinmobiliaria" />

<meta property="og:image:type" content="image/jpeg" />
<meta property="og:image:width" content="900" />
<meta property="og:image:height" content="324" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:description" content="1er Portal inmobiliario para franquicias. Con la garantía de mundoFranquicia." />
<meta name="twitter:title" content="1er Portal inmobiliario para franquicias. Con la garantía de mundoFranquicia." />
<link rel="canonical" href="{{ url(Request::url()) }}" />
@if($local->imagen_principal != null)
<meta property="og:image" content="{{ url($local->imagen_principal->ruta) }}" />
<meta property="og:image:secure_url" content="{{ url($local->imagen_principal->ruta) }}" />
<meta name="twitter:image" content="{{ url($local->imagen_principal->ruta) }}" />
@endif
<!-- RRSS -->
@endsection

@section('content')
<section>

    <article class="local-article">

        @if($local->imagen_principal != null)
        <img class="local-article-main-image" src="{{ url($local->imagen_principal->ruta) }}">
        @endif

        <div class="local-article-body">
            <div class="local-article-sector">
                 <h2>{{ $local->metros}} m² - {{ $local->sector->titulo}}</h2>
            </div>
            <div class="local-article-titulo">
                <h1>{{ $local->titulo}}, {{ $local->poblacion->nombre}}</h1>
                <h2>{{ $local->precio}}€</h2>
            </div>

            <div class="local-article-descripcion mt-3">
                <h2>Comentario del anunciante</h2>
                <?= $local->descripcion ?>
            </div>

        </div>

        <div class="local-article-footer mt-5">
            <!-- https://sharingbuttons.io/ -->
            <!-- Sharingbutton Facebook -->
            <a class="resp-sharing-button__link" href="https://facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}" target="_blank" rel="noopener" aria-label="">
            <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                </div>
            </div>
            </a>

            <!-- Sharingbutton Twitter -->
            <a class="resp-sharing-button__link" href="https://twitter.com/intent/tweet/?text={{ urlencode($local->titulo) }}&amp;url={{ Request::url() }}" target="_blank" rel="noopener" aria-label="">
            <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>
                </div>
            </div>
            </a>

            <!-- Sharingbutton E-Mail -->
            <a class="resp-sharing-button__link" href="mailto:?subject={{ urlencode($local->titulo) }}&amp;body={{ Request::url() }}" target="_self" rel="noopener" aria-label="">
            <div class="resp-sharing-button resp-sharing-button--email resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 4H2C.9 4 0 4.9 0 6v12c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM7.25 14.43l-3.5 2c-.08.05-.17.07-.25.07-.17 0-.34-.1-.43-.25-.14-.24-.06-.55.18-.68l3.5-2c.24-.14.55-.06.68.18.14.24.06.55-.18.68zm4.75.07c-.1 0-.2-.03-.27-.08l-8.5-5.5c-.23-.15-.3-.46-.15-.7.15-.22.46-.3.7-.14L12 13.4l8.23-5.32c.23-.15.54-.08.7.15.14.23.07.54-.16.7l-8.5 5.5c-.08.04-.17.07-.27.07zm8.93 1.75c-.1.16-.26.25-.43.25-.08 0-.17-.02-.25-.07l-3.5-2c-.24-.13-.32-.44-.18-.68s.44-.32.68-.18l3.5 2c.24.13.32.44.18.68z"/></svg>
                </div>
            </div>
            </a>

            <!-- Sharingbutton LinkedIn -->
            <a class="resp-sharing-button__link" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ Request::url() }}&amp;title={{ urlencode($local->titulo) }}&amp;summary={{ urlencode($local->titulo) }}&amp;source={{ Request::url() }}" target="_blank" rel="noopener" aria-label="">
            <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg>
                </div>
            </div>
            </a>

            <!-- Sharingbutton WhatsApp -->
            <a class="resp-sharing-button__link" href="whatsapp://send?text={{ urlencode($local->titulo) }}%20{{ Request::url() }}" target="_blank" rel="noopener" aria-label="">
            <div class="resp-sharing-button resp-sharing-button--whatsapp resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.1 3.9C17.9 1.7 15 .5 12 .5 5.8.5.7 5.6.7 11.9c0 2 .5 3.9 1.5 5.6L.6 23.4l6-1.6c1.6.9 3.5 1.3 5.4 1.3 6.3 0 11.4-5.1 11.4-11.4-.1-2.8-1.2-5.7-3.3-7.8zM12 21.4c-1.7 0-3.3-.5-4.8-1.3l-.4-.2-3.5 1 1-3.4L4 17c-1-1.5-1.4-3.2-1.4-5.1 0-5.2 4.2-9.4 9.4-9.4 2.5 0 4.9 1 6.7 2.8 1.8 1.8 2.8 4.2 2.8 6.7-.1 5.2-4.3 9.4-9.5 9.4zm5.1-7.1c-.3-.1-1.7-.9-1.9-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.1-.2.2-.3.2-.6.1s-1.2-.5-2.3-1.4c-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.5.1-.6s.3-.3.4-.5c.2-.1.3-.3.4-.5.1-.2 0-.4 0-.5C10 9 9.3 7.6 9 7c-.1-.4-.4-.3-.5-.3h-.6s-.4.1-.7.3c-.3.3-1 1-1 2.4s1 2.8 1.1 3c.1.2 2 3.1 4.9 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.3-.3-.4-.6-.5z"/></svg>
                </div>
            </div>
            </a>
        </div>

    </article>
</section>
@endsection

@section('sidebar')
<section>
    <form class="form-solicitud" method="POST" action="{{ url('/solicitud') }}">
        <div class="form-solicitud-header"><i class="far fa-id-card"></i><h2>Pregunta al anunciante</h2></div>
        <div class="form-solicitud-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <input placeholder="Nombre" type="text" class="form-control" name="nombre" value="{{ old('nombre') }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <input placeholder="Email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <input placeholder="Teléfono" type="tel" class="form-control" name="telefono" value="{{ old('telefono') }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <textarea placeholder="Comentario" class="form-control" rows="3" name="comentario">{{ old('comentario') }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                    <button class="btn btn-secondary">Conectar</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id_local" value="{{ $local->id }}">

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

    @if(!empty($local->caracteristicas))
    <article class="mt-3">
        <ul class="list-group">
            <li class="list-group-item active"><i class="fas fa-list"></i> <h2>Caracteristicas</h2></li>
            @foreach($local->caracteristicas as $caracteristica)
            <li class="list-group-item">{{ $caracteristica->valor }}</li>
            @endforeach
        </ul>
    </article>
    @endif

    @if(!empty($local->edificios))
    <article class="mt-3">
        <ul class="list-group">
            <li class="list-group-item active"><i class="far fa-building"></i> <h2>Edificio</h2></li>
            @foreach($local->edificios as $edificio)
            <li class="list-group-item">{{ $edificio->valor }}</li>
            @endforeach
        </ul>
    </article>
    @endif

    @if(!empty($local->equipamientos))
    <article class="mt-3">
        <ul class="list-group">
            <li class="list-group-item active"><i class="fas fa-boxes"></i><h2>Equipamiento</h2></li>
            @foreach($local->equipamientos as $equipamiento)
            <li class="list-group-item">{{ $equipamiento->valor }}</li>
            @endforeach
        </ul>
    </article>
    @endif

</section>
@endsection
