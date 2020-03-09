<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>lista de admitidos y excluidos</title>
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/informes.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap-theme.min.css') }}">
</head>
<body>
    <div class="content">
        <h1 class="text-center informe-titulo">Lista de admitidos/as y excluidos/as</h1>

        <p><b>Curso: </b>{{ $curso->NOMBRE }}</p>

        <table class="table-invisible">
            <tr>
                <td>
                    <p><b>Del</b> {{ $curso->INICIO }} <b>al</b> {{ $curso->FIN }}</p>
                </td>
            </tr>
        <table>

        <h2>Admitidos</h2>

        <table class="tabla-informe">
            <tr>
                <th style="width: 10%">Nº</th>
                <th style="width: 50%">APELLIDOS Y NOMBRE</th>
                <th style="width: 20%">NIF</th>
                <th style="width: 15%">MÓVIL</th>
                <th style="width: 15%">EMAIL</th>
            </tr>
            @if(count($admitidos) > 0)
                <?php $cnt=1; ?>
                @foreach($admitidos as $alumno)
                <tr>
                    <td class="text-center">{{ $cnt }}</td>
                    <td>{{ $alumno->APELLIDO1 }} {{ $alumno->APELLIDO2 }}, {{ $alumno->NOMBRE }}</td>
                    <td>{{ $alumno->DNI }}</td>
                    <td>{{ $alumno->MOVIL }}</td>
                    <td>{{ $alumno->MAIL }}</td>
                </tr>
                <?php $cnt++; ?>
                @endforeach
            @else
                <tr>
                    <td colspan="5">Ningún alumno admitido.</td>
                </tr>
            @endif
        </table>

        <h2>Excluidos</h2>

        <table class="tabla-informe">
            <tr>
                <th style="width: 10%">Nº</th>
                <th style="width: 45%">APELLIDOS Y NOMBRE</th>
                <th style="width: 15%">NIF</th>
                <th style="width: 15%">MÓVIL</th>
                <th style="width: 15%">EMAIL</th>
            </tr>
            @if(count($excluidos) > 0)
                <?php $cnt=1; ?>
                @foreach($excluidos as $alumno)
                <tr>
                    <td class="text-center">{{ $cnt }}</td>
                    <td>{{ $alumno->APELLIDO1 }} {{ $alumno->APELLIDO2 }}, {{ $alumno->NOMBRE }}</td>
                    <td>{{ $alumno->DNI }}</td>
                    <td>{{ $alumno->MOVIL }}</td>
                    <td>{{ $alumno->MAIL }}</td>
                </tr>
                <?php $cnt++; ?>
                @endforeach
            @else
                <tr>
                    <td colspan="5">Ningún alumno excluido.</td>
                </tr>
            @endif
        </table>
    </div>
</body>
</html>