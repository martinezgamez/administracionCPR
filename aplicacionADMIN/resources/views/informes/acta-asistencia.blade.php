<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>acta</title>
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/informes.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap-theme.min.css') }}">
</head>
<body>
    <div class="content">
        <h1 class="text-center informe-titulo">Hoja de asistencia</h1>

        <p><b>Curso: </b>{{ $curso->NOMBRE }}</p>

        <table class="table-invisible">
            <tr>
                <td style="width: 33.3%">
                    <p><b>Del</b> {{ $curso->INICIO }} <b>al</b> {{ $curso->FIN }}</p>
                </td>
                <td style="width: 33.3%" class="text-center">
                    <p><b>Día: </b> {{ $sesion->FECHA }}</p>
                </td>
                <td style="width: 33.3%" class="text-right">
                    <p>{{ $sesion->SESION }}ª <b>Sesión</b></p>
                </td>
            </tr>
        <table>


        <table class="tabla-informe">
            <tr>
                <th style="width: 10%">Nº</th>
                <th style="width: 50%">APELLIDOS Y NOMBRE</th>
                <th style="width: 20%">NIF</th>
                <th style="width: 20%">FIRMA</th>
            </tr>
            <?php $cnt=1; ?>
            @foreach($alumnos as $alumno)
            <tr>
                <td class="text-center">{{ $cnt }}</td>
                <td>{{ $alumno->APELLIDO1 }} {{ $alumno->APELLIDO2 }}, {{ $alumno->NOMBRE }}</td>
                <td></td>
                <td></td>
            </tr>
            <?php $cnt++; ?>
            @endforeach
        </table>
    </div>
</body>
</html>