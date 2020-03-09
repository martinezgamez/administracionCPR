<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Propuesta de certificación</title>

    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/informes.css') }}">
</head>
<body>
    <div class="content">

        <h1 class="text-center informe-titulo">PROPUESTA DE CERTIFICACIÓN DEL GRUPO DE TRABAJO</h1>

        <div class="informe-inicial">
            <div>
                <b>1.- CENTRO: </b>{{ $datos['CENTRO'] }}
            </div>
            <div>
                <b>2.- TÍTULO: </b>{{ $datos['TITULO'] }}
            </div>
            <div>
                <b>3.- MODALIDAD FORMATIVA: </b>{{ $datos['MODALIDAD'] }}
            </div>
            <div>
                <b>4.- FECHAS DE REALIZACIÓN:</b>
                <table class="table-invisible">
                    <tr>
                        <td><b>Del</b> {{ $datos['INICIO'] }} <b>al</b> {{ $datos['FIN'] }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <b>5.- SESIONES REALIZADAS:</b>
                <table class="table-invisible">
                    <tr>
                        <td style="text-align: left;"><b>NÚMERO DE SESIONES:</b> {{ $datos['SESIONES'] }}</td>
                        <td style="text-align: right;"><b> NÚMERO DE HORAS A CERTIFICAR:</b> {{ $datos['HORASFINAL'] }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <b>6.- PROPUESTA DE CERTIFICACIÓN:</b>
                @if(count($certifican) > 0)
                <table class="table-invisible">
                    <tr>
                        <td><b>PARTICIPANTES CON DERECHO A CERTIFICACIÓN:</b></td>
                    </tr>
                </table>
                <table class="tabla-normal">
                    <tr>
                        <th style="width: 70%;">APELLIDOS Y NOMBRE</th>
                        <th style="width: 30%;text-align: center;">DNI</th>
                    </tr>
                    @foreach($certifican as $certifica)
                        <tr>
                            <td>
                                {{ $certifica->APELLIDO1 }} {{ $certifica->APELLIDO2 }}, {{ $certifica->NOMBRE }}
                            </td>
                            <td style="text-align: center;">
                                {{ $certifica->DNI }}
                            </td>
                        </tr>
                    @endforeach
                </table>
                @endif
                @if(count($nocertifican) > 0)
                <table class="table-invisible">
                    <tr>
                        <td><b>PARTICIPANTES SIN DERECHO A CERTIFICACIÓN:</b></td>
                    </tr>
                </table>
                <table class="tabla-normal">
                    <tr>
                        <th style="width: 70%;">APELLIDOS Y NOMBRE</th>
                        <th style="width: 30%;text-align: center;">DNI</th>
                    </tr>
                    @foreach($nocertifican as $nocertifica)
                        <tr>
                            <td>
                                {{ $nocertifica->APELLIDO1 }} {{ $nocertifica->APELLIDO2 }}, {{ $nocertifica->NOMBRE }}
                            </td>
                            <td style="text-align: center;">
                                {{ $nocertifica->DNI }}
                            </td>
                        </tr>
                    @endforeach
                </table>
                @endif
            </div>
            <div>
                @if(count($coordinadores) > 0)
                <table class="tabla-invisible">
                    <b>7.- COORDINADOR/A RESPONSABLE:</b>
                    @foreach($coordinadores as $coordinador)
                    <tr>
                        <td style="text-align: left;">
                            <b>NOMBRE, DNI:</b> {{ $coordinador->NOMBRE }} {{ $coordinador->APELLIDO1 }} {{ $coordinador->APELLIDO2 }}, {{ $coordinador->DNI }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>HORAS QUE CERTIFICA: </b>{{ number_format($datos['HORASFINAL']+$datos['HORASFINAL']*$datos['PORCENTAJE']/100, 2, ",", ".") }}
                        </td>
                    </tr>
                    @endforeach
                </table>
                @endif
            </div>
            <div>
                <b>8.- ASESOR/A: </b>{{ $datos['ASESOR']}}
            </div>
            <div>
                <b>9.- APROBADO POR EL CONSEJO DEL CENTRO EN SESIÓN DE FECHA:: </b>{{ $datos['APROBADO'] }}
            </div>
        </div>

        <br/>

        <table class="table-invisible" style="page-break-inside: avoid;">
            <tr>
                <td colspan="2" style="text-align: center;">
                    <b>Melilla, {{ date('d') }} de {{ $meses[date('n')-1] }} de {{ date('Y') }}</b>
                </td>
            </tr>
            <tr>

            </tr>
            <tr class="firmas">
                <td></td>
            </tr>
            <tr>
                <td>
                    <b>Fdo.: </b>{{ $datos['ASESOR'] }}
                </td>
                <td>
                    <b>Fdo.: </b>{{ $director }}
                </td>
            </tr>
            <tr>
                <td>
                    <b>ASESOR/A DEL GRUPO DE TRABAJO</b>
                </td>
                <td>
                    <b>DIRECTOR</b>
                </td>
            </tr>
        </table>

    </div>
</body>
</html>