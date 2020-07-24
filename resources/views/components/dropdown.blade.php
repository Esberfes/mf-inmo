<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdown{{ $name }}" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        {{ $name }}
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownLocales">
        <a class="dropdown-item" href="{{ url( $linkList ) }}">Listar</a>
        <a class="dropdown-item" href="{{ url( $linkCreate ) }}">Crear</a>
    </div>
</div>
