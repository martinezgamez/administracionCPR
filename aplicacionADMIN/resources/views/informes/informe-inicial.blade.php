<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe inicial</title>

    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/informes.css') }}">
</head>
<body>
    <div class="content">

        <h1 class="text-center informe-titulo">Informe inicial</h1>

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
                <b>5.- COORDINADOR/A: </b>{{ $datos->CNOMBRE }}
            </div>
            <div>
                <b>6. FECHAS DE LA CELEBRACIÓN DEL CURSO: </b> Del {{ $datos->INICIO }} al {{ $datos->FIN }}
            </div>
            <div>
                <b>7.- Nº DE HORAS: </b>{{ $datos->DURACION }}
            </div>
            <div>
                <b>8.- PROFESORES/PONENTES:</b>
                <table class="table-invisible">
                @foreach($datos->PONENTES as $ponente)
                    <tr>
                        <td colspan="3">
                            {{ $ponente->NOMBRE }} {{ $ponente->APELLIDO1 }} {{ $ponente->APELLIDO2 }}
                        </td>
                    </tr>
                @endforeach
                    <tr>
                        <td style="width: 20%">
                            <b>HORAS: </b>{{ $datos->DURACION }}</td>
                        <td style="width: 40%">
                            <b>Horas docencia directa: </b>{{ $datos->PRESENCIAL }}
                        </td>
                        <td style="width: 40%">
                            <b>Horas fase no presencial: </b>{{ $datos->DURACION-$datos->PRESENCIAL }}
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <b>9.- PLAZAS OFERTADAS: </b>{{ $datos->PLAZAS }}
            </div>
            <div>
                <b>10.- LUGAR DE CELEBRACIÓN: </b>{{ $datos->LUGAR }}
            </div>
            <div>
                <b>11.- JUSTIFICACIÓN: </b><br/>
                @if($detalles != null)
                    @php
                        $order=["\r\n", "\n", "\r"];
                        $replace = '<br>';
                        $detalles->JUSTIFICACION=str_replace($order, $replace, $detalles->JUSTIFICACION);
                    @endphp
                    <p> {!! $detalles->JUSTIFICACION !!} </p>
                @endif
            </div>
            <div>
                <b>12.- OBJETIVOS: </b><br/>
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
                <b>13.- CONTENIDOS Y TEMPORALIZACIÓN: </b><br/>
                @if($detalles != null)
                    @php
                        $order=["\r\n", "\n", "\r"];
                        $replace = '<br>';
                        $detalles->CONTENIDOS=str_replace($order, $replace, $detalles->CONTENIDOS);
                    @endphp
                    <p> {!! $detalles->CONTENIDOS !!} </p>
                @endif
                <table class="table-invisible">
                    <tr>
                        <td>
                            <b>FECHAS: </b> Del {{ $datos->INICIO }} al {{ $datos->FIN }}
                        </td>
                        <td>
                            <b>HORARIO: </b> {{ $datos->HORARIO }}
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <b>14.- METODOLOGÍA: </b><br/>
                @if($detalles != null)
                    @php
                        $order=["\r\n", "\n", "\r"];
                        $replace = '<br>';
                        $detalles->METODOLOGIA=str_replace($order, $replace, $detalles->METODOLOGIA);
                    @endphp
                    <p> {!! $detalles->METODOLOGIA !!} </p>
                @endif
            </div>
            <div>
                <b>15.- RECURSOS NECESARIOS:</b>
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
                <b>16.- MATERIALES PARA LOS ASISTENTES Y BIBLIOGRAFÍA: </b>
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
                <b>17.- PRESUPUESTO: </b>{{ $datos->PRESUPUESTO }}€
            </div>
            <div>
                <b>18.- CRITERIOS DE EVALUACIÓN: </b>
                @if($detalles != null)
                    @php
                        $order=["\r\n", "\n", "\r"];
                        $replace = '<br>';
                        $detalles->EVALUACION=str_replace($order, $replace, $detalles->EVALUACION);
                    @endphp
                    <p> {!! $detalles->EVALUACION !!} </p>
                @endif
            </div>
            <div>
                <b>19.- OBSEVACIONES: </b>
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