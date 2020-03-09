<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>recibo de dietas</title>
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/informes.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap-theme.min.css') }}">
</head>
<body>
    <div class="content">
        <h1 class="text-center informe-titulo">Recibo de Dietas</h1>

        <p><b>D./Dña. </b> {{ $ponente->NOMBRE }} {{ $ponente->APELLIDO1 }} {{ $ponente->APELLIDO2 }}</p>

        <p><b>DNI: </b>{{ $ponente->DNI }}</p>

        <p>Ha recibido de la Dirección Provincial del MECD de Melilla la cantidad de: <span>{{ $gastosLetras }}</span>, ({{ $gastos }}€), correspondientes a los gastos de dietas por manutención de los días {{ $curso->INICIO }} al {{ $curso->FIN }}, por la realización de la actividad formativa: "{{ $curso->NOMBRE }}".</p>

        <div class="separador"></div>

        <table class="table-invisible">
            <tr>
                <td colspan="2">
                    <b>Melilla, {{ date('d') }} de {{ $meses[date('n')-1] }} de {{ date('Y') }}</b>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Recibí:</b>
                </td>
                <td></td>
            </tr>
            <tr class="firmas">
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <b>Fdo.: </b>{{ $ponente->NOMBRE }} {{ $ponente->APELLIDO1 }} {{ $ponente->APELLIDO2 }}
                </td>
                <td>
                    <b>IMPORTE EN EUROS:</b> {{ $gastos }}€
                </td>
            </tr>
        </table>
        
        <div class="separador"></div>
        
        <table class="table-invisible">
            <tr>
                <td colspan="5">
                    <b>C.C.:</b>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">IBAN</td>
                <td style="text-align: center;">ENTIDAD</td>
                <td style="text-align: center;">SUCURSAL</td>
                <td style="text-align: center;">CONTROL</td>
                <td style="text-align: center;">CUENTA</td>
            </tr>
            <tr>
                @if(strlen($ponente->ENTIDAD) > 4)
                    <td style="text-align: center;">{{ substr(str_replace(" " , "", $ponente->ENTIDAD), 0, 4) }}</td>
                    <td style="text-align: center;">{{ substr(str_replace(" " , "", $ponente->ENTIDAD), 4, 4) }}</td>
                @else
                    <td></td>
                    <td style="text-align: center;">{{ $ponente->ENTIDAD }}</td>
                @endif
                <td style="text-align: center;">{{ $ponente->SUCURSAL }}</td>
                <td style="text-align: center;">{{ $ponente->CONTROL }}</td>
                <td style="text-align: center;">{{ $ponente->CUENTA }}</td>
            </tr>
        </table>

    </div>
</body>
</html>