<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <h1>Solicitud de contacto: {{ $solicitud->local->titulo }}</h1>
    <p>Hola {{ $admin->nombre }} se ha realizado una nueva solicitud, puede atenderla desde el Area Privada</p>
    <small>Id: $solicitud->id</small>
</body>
</html>
