<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe final</title>

    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/informes.css') }}">
</head>
<body>
    <div class="content">

        <h1 class="text-center informe-titulo">Informe final del curso</h1>

        <div class="informe-inicial">
            <div>
                <b>1.- TÍTULO: </b>{{ $datos->NOMBRE }}
            </div>
            <div>
                <b>2.- MODALIDAD FORMATIVA: </b>{{ $datos->MODALIDAD }}
            </div>
            <div>
                <b>3.- ÁREA: </b>{{ $datos->AREA }}
            </div>
            <div>
                <b>4.- NIVEL AL QUE SE DIRIGE LA ACTIVIDAD: </b>{{ $datos->NIVEL }}
            </div>
            <div>
                <b>5.- COORDINA: </b>{{ $datos->CNOMBRE }}
            </div>
            <div>
                <table class="table-invisible">
                    <tr>
                        <td style="width: 50%"><b>6.- FECHA INICIO: </b> {{ $datos->INICIO }}</td>
                        <td style="width: 50%"><b>FECHA FINALIZACIÓN: </b> {{ $datos->FIN }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <b>7.- Nº DE HORAS: </b>{{ $datos->DURACION }}
            </div>
            <div>
                <b>8.- PROFESORES/PONENTES:</b>
                <table class="tabla-normal">
                    <tr>
                        <th style="width: 60%;">APELLIDOS Y NOMBRE</th>
                        <th style="width: 25%;text-align: center;">DNI</th>
                        <th style="width: 15%;text-align: center;">HORAS</th>
                    </tr>
                @foreach($datos->PONENTES as $ponente)
                    <tr>
                        <td>
                            {{ $ponente->NOMBRE }} {{ $ponente->APELLIDO1 }} {{ $ponente->APELLIDO2 }}
                        </td>
                        <td style="text-align: center;">
                            {{ $ponente->DNI }}
                        </td>
                        <td style="text-align: center;">
                            {{ $ponente->HORAS }}
                        </td>
                    </tr>
                @endforeach
                </table>
                <table class="table-invisible">
                    <tr>
                        
                        <td style="width: 50%">
                            <b>HORAS TOTALES DE DOCENCIA DIRECTA: </b>{{ $datos->PRESENCIAL }}
                        </td>
                        <td style="width: 50%" align="right">
                            <b>HORAS DE FASE PRÁCTICA: </b>{{ $datos->DURACION-$datos->PRESENCIAL }}
                        </td>
                        
                    </tr>
                </table>
            </div>
            <div>
                <b>9.- DATOS CUANTITATIVOS:</b>
                    <ul style="list-style-type:none;">
                        <li><b>- PLAZAS OFERTADAS: </b>{{ $datos->PLAZAS }}</li>
                        <li><b>- ALUMNOS ADMITIDOS: </b>{{ $datos->ADMITIDOS }}</li>
                        <li><b>- ASISTENTES CON DERECHO A CERTIFICACIÓN: </b>{{ $datos->CERTIFICADOS }}</li>
                    </ul>
            </div>
            <div>
                <b>10.- LUGAR DE CELEBRACIÓN: </b>{{ $datos->LUGAR }}
            </div>
            <div>
                <b>11.- OBJETIVOS: </b><br/>
                @if($detalles != null)
                    @php
                        $order=["\r\n", "\n", "\r"];
                        $replace = '<br>';
                        $detalles->OBJETIVOS=str_replace($order, $replace, $detalles->OBJETIVOS);
                    @endphp
                    <p> {!! $detalles->OBJETIVOS !!} </p>
                @endif
            </div>
            <div>
                <b>12.- CONTENIDOS: </b><br/>
                @if($detalles != null)
                    @php
                        $order=["\r\n", "\n", "\r"];
                        $replace = '<br>';
                        $detalles->CONTENIDOS=str_replace($order, $replace, $detalles->CONTENIDOS);
                    @endphp
                    <p> {!! $detalles->CONTENIDOS !!} </p>
                @endif
            </div>
            <div>
                <b>13.- RECURSOS UTILIZADOS: </b>
                @if($detalles != null)
                    @php
                        $order=["\r\n", "\n", "\r"];
                        $replace = '<br>';
                        $detalles->RECURSOS=str_replace($order, $replace, $detalles->RECURSOS);
                    @endphp
                    <p> {!! $detalles->RECURSOS !!} </p>
                @endif
            </div>
            <div>
                <b>14.- MATERIALES NECESARIOS:</b>
                @if($detalles != null)
                    @php
                        $order=["\r\n", "\n", "\r"];
                        $replace = '<br>';
                        $detalles->MATERIALES=str_replace($order, $replace, $detalles->MATERIALES);
                    @endphp
                    <p> {!! $detalles->MATERIALES !!} </p>
                @endif
            </div>
            <div>
                <b>15.- GASTOS DE LA ACTIVIDAD: </b>{{ $datos->GASTOS }}€
            </div>
            <div>
                <b>16.- VALORACIÓN Y EVALUACIÓN: </b>
                @if($detalles != null)
                    @php
                        $order=["\r\n", "\n", "\r"];
                        $replace = '<br>';
                        $detalles->VALORACION=str_replace($order, $replace, $detalles->VALORACION);
                    @endphp
                    <p> {!! $detalles->VALORACION !!} </p>
                @endif
            </div>
            <div>
                <b>17.- OBSERVACIONES Y PROPUESTAS: </b>
                @if($detalles != null)
                    @php
                        $order=["\r\n", "\n", "\r"];
                        $replace = '<br>';
                        $detalles->OBSERVACIONES=str_replace($order, $replace, $detalles->OBSERVACIONES);
                    @endphp
                    <p> {!! $detalles->OBSERVACIONES !!} </p>
                @endif
            </div>
        </div>

        <br/>

        <table class="table-invisible">
            <tr>
                <td colspan="2">
                    <b>Melilla, {{ date('d') }} de {{ $meses[date('n')-1] }} de {{ date('Y') }}</b>
                </td>
            </tr>
            <tr>
                <td>
                    <b>VºBº DIRECTOR/A</b>
                </td>
                <td>
                    <b>COORDINADOR/A</b>
                </td>
            </tr>
            <tr class="firmas">
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <b>Fdo.: </b>{{ $datos->DNOMBRE }}
                </td>
                <td>
                    <b>Fdo.: </b>{{ $datos->CNOMBRE }}
                </td>
            </tr>
        </table>

    </div>
</body>
</html>