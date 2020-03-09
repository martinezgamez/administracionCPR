<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Propuesta de certificación</title>

    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/informes.css') }}">
</head>
<style type="text/css">
    table { page-break-inside:avoid; }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
</style>
<body>
    <div class="content">

        <h1 class="text-center informe-titulo">Propuesta de certificación</h1>

        <div class="propuesta-certificacion">

            <table class="tabla-certificacion">
                <tr>
                    <td class="azul">
                        <b>Nombre:</b>
                    </td>
                    <td colspan="3">
                        {{ $datos->NOMBRE }}
                    </td>
                </tr>
                <tr>
                    <td class="azul">
                        <b>Fecha de inicio:</b>
                    </td>
                    <td>
                        {{ $datos->INICIO }}
                    </td>
                    <td class="azul">
                        <b>Fecha de finalización:</b>
                    </td>
                    <td>
                        {{ $datos->FIN }}
                    </td>
                </tr>
                <tr>
                    <td class="azul">
                        <b>Ubicación:</b>
                    </td>
                    <td colspan="3">
                        {{ $datos->LUGAR }}
                    </td>
                </tr>
                <tr>
                    <td class="azul">
                        <b>Horas:</b>
                    </td>
                    <td colspan="3">
                        {{ $datos->DURACION }}
                    </td>
                </tr>
            </table>

            <table class="tabla-certificacion" style="margin: 0px 0px 0px 0px;">
                <tr>
                    <td class="azul" style="text-align: center;">
                        <b>Alumnos admitidos</b>
                    </td>
                    <td class="azul" style="text-align: center;">
                        <b>No admitidos</b>
                    </td>
                    <td class="azul" style="text-align: center;">
                        <b>Certificados</b>
                    </td>
                    <td class="azul" style="text-align: center;">
                        <b>Sin certificación</b>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        {{ count($admitidos) }}
                    </td>
                    <td style="text-align: center">
                        {{ count($noAdmitidos) }}
                    </td>
                    <td style="text-align: center">
                        {{ count($certificados) }}
                    </td>
                    <td style="text-align: center">
                        {{ count($noCertificados) }}
                    </td>
                </tr>
            </table>
            <table class="tabla-certificacion" style="margin: -1px 0px 0px 0px;">
                <tr>
                    <td class="azul" colspan="6" style="text-align: center;">
                        <b>Profesorado admitido al curso</b>
                    </td>
                </tr>
                <tr>
                    <td class="azul" style="text-align: center;"><b>Situación</b></td>
                    <td class="azul" style="text-align: center;"><b>Infantil</b></td>
                    <td class="azul" style="text-align: center;"><b>Primaria</b></td>
                    <td class="azul" style="text-align: center;"><b>Secundaria</b></td>
                    <td class="azul" style="text-align: center;"><b>F.P.</b></td>
                    <td class="azul" style="text-align: center;"><b>Otros</b></td>
                </tr>
                <tr>
                    <td>Funcionario Definitivo</td>
                    <td style="text-align: center;">{{ $resumen['fdefinitivo']['infantil'] }}</td>
                    <td style="text-align: center;">{{ $resumen['fdefinitivo']['primaria'] }}</td>
                    <td style="text-align: center;">{{ $resumen['fdefinitivo']['secundaria'] }}</td>
                    <td style="text-align: center;">{{ $resumen['fdefinitivo']['fp'] }}</td>
                    <td style="text-align: center;">{{ $resumen['fdefinitivo']['otros'] }}</td>
                </tr>
                <tr>
                    <td>Funcionario Interino</td>
                    <td style="text-align: center;">{{ $resumen['finterino']['infantil'] }}</td>
                    <td style="text-align: center;">{{ $resumen['finterino']['primaria'] }}</td>
                    <td style="text-align: center;">{{ $resumen['finterino']['secundaria'] }}</td>
                    <td style="text-align: center;">{{ $resumen['finterino']['fp'] }}</td>
                    <td style="text-align: center;">{{ $resumen['finterino']['otros'] }}</td>
                </tr>
                <tr>
                    <td>Contrato Laboral</td>
                    <td style="text-align: center;">{{ $resumen['claboral']['infantil'] }}</td>
                    <td style="text-align: center;">{{ $resumen['claboral']['primaria'] }}</td>
                    <td style="text-align: center;">{{ $resumen['claboral']['secundaria'] }}</td>
                    <td style="text-align: center;">{{ $resumen['claboral']['fp'] }}</td>
                    <td style="text-align: center;">{{ $resumen['claboral']['otros'] }}</td>
                </tr>
                <tr>
                    <td>Desempleado</td>
                    <td style="text-align: center;">{{ $resumen['desempleado']['infantil'] }}</td>
                    <td style="text-align: center;">{{ $resumen['desempleado']['primaria'] }}</td>
                    <td style="text-align: center;">{{ $resumen['desempleado']['secundaria'] }}</td>
                    <td style="text-align: center;">{{ $resumen['desempleado']['fp'] }}</td>
                    <td style="text-align: center;">{{ $resumen['desempleado']['otros'] }}</td>
                </tr>
                <tr>
                    <td>Otros</td>
                    <td style="text-align: center;">{{ $resumen['otros']['infantil'] }}</td>
                    <td style="text-align: center;">{{ $resumen['otros']['primaria'] }}</td>
                    <td style="text-align: center;">{{ $resumen['otros']['secundaria'] }}</td>
                    <td style="text-align: center;">{{ $resumen['otros']['fp'] }}</td>
                    <td style="text-align: center;">{{ $resumen['otros']['otros'] }}</td>
                </tr>
                <tr>
                    <td><b>Totales</b></td>
                    <td style="text-align: center;"><b>{{ $resumen['total']['infantil'] }}</b></td>
                    <td style="text-align: center;"><b>{{ $resumen['total']['primaria'] }}</b></td>
                    <td style="text-align: center;"><b>{{ $resumen['total']['secundaria'] }}</b></td>
                    <td style="text-align: center;"><b>{{ $resumen['total']['fp'] }}</b></td>
                    <td style="text-align: center;"><b>{{ $resumen['total']['otros'] }}</b></td>
                </tr>
            </table>

            <table class="tabla-certificacion">
                <tr>
                    <th class="verde" colspan="4">
                        Responsables de la actividad
                    </th>
                </tr>
                <tr>
                    <th class="gris">
                        Apellidos y Nombre
                    </th>
                    <th class="gris"">
                        Cargo
                    </th>
                    <th class="gris"">
                        N.I.F
                    </th>
                    <th class="gris">
                        Horas
                    </th>
                </tr>
                @foreach($ponentes as $ponente)
                <tr>
                    <td>
                        {{ $ponente->APELLIDO1 }} {{ $ponente->APELLIDO2 }}, {{ $ponente->NOMBRE }}
                    </td>
                    <td>
                        PONENTE: {{ $ponente->PONENCIA }}
                    </td>
                    <td style="text-align: center">
                        {{ $ponente->DNI }}
                    </td>
                    <td style="text-align: center">
                        {{ $ponente->HORAS }}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td>
                        {{ $datos->CNOMBRE }}
                    </td>
                    <td>
                        {{ $datos->CCARGO }}
                    </td>
                    <td style="text-align: center">
                        {{ $datos->CNIF }}
                    </td>
                    <td style="text-align: center">
                        {{ $datos->DURACION }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $datos->DNOMBRE }}
                    </td>
                    <td>
                        {{ $datos->DCARGO }}
                    </td>
                    <td style="text-align: center">
                        {{ $datos->DNIF }}
                    </td>
                    <td style="text-align: center">
                        {{ $datos->DURACION }}
                    </td>
                </tr>
            </table>

            <table class="tabla-certificacion">
                <tr>
                    <th class="verde" colspan="3">
                        Relación de participantes con derecho a certificación
                    </th>
                </tr>
                <tr>
                    <th class="gris">
                        Nº
                    </th>
                    <th class="gris">
                        Apellidos y Nombre
                    </th>
                    <th class="gris">
                        N.I.F
                    </th>
                </tr>
                @php $i = 1 @endphp
                @foreach($certificados as $certificado)
                <tr>
                    <td style="text-align: right">
                        {{ $i++ }}
                    </td>
                    <td>
                        {{ $certificado->APELLIDO1 }} {{ $certificado->APELLIDO2 }}, {{ $certificado->NOMBRE }}
                    </td>
                    <td style="text-align: center">
                        {{ $certificado->DNI }}
                    </td>
                </tr>
                @endforeach
            </table>

            <table class="tabla-certificacion">
                <tr>
                    <th class="naranja" colspan="4">
                        Relación de participantes sin derecho a certificación
                    </th>
                </tr>
                <tr>
                    <th class="gris">
                        Apellidos y Nombre
                    </th>
                    <th class="gris">
                        N.I.F
                    </th>
                    <th class="gris">
                        Asistencia
                    </th>
                    <th class="gris">
                        Trabajo
                    </th>
                </tr>
                @foreach($noCertificados as $noCertificado)
                <tr>
                    <td>
                        {{ $noCertificado->APELLIDO1 }} {{ $noCertificado->APELLIDO2 }}, {{ $noCertificado->NOMBRE }}
                    </td>
                    <td>
                        {{ $noCertificado->DNI }}
                    </td>
                    <td>
                        {{ $noCertificado->ASISTENCIA }}
                    </td>
                    <td>
                        {{ $noCertificado->ASISTENCIANOPRESENCIAL }}
                    </td>
                </tr>
                @endforeach
            </table>

        </div>

        <div class="separador"></div>

        <br/>

        <table class="table-invisible">
            <tr>
                <td colspan="2" style="text-align: center;">
                    Melilla, {{ date('d') }} de {{ $meses[date('n')-1] }} de {{ date('Y') }}
                </td>
            </tr>
            <tr>
                <td>
                    <b>VºBº Director/a</b>
                </td>
                <td class="text-right">
                    <b>VºBº Coordinador/a</b>
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
                <td class="text-right">
                    <b>Fdo.: </b>{{ $datos->CNOMBRE }}
                </td>
            </tr>
        </table>

    </div>
</body>
</html>