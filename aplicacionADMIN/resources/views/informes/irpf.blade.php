<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>irpf</title>
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/informes.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/bootstrap-theme.min.css') }}">
</head>
<body>
    <div class="page">
        <div class="content">

            <h3>D./Dª. {{ $director }}, DIRECTOR/A PROVINCIAL DEL MECD.</h3>

            <div class="separador"></div>

            <p><b>Hace constar:</b></p>

            <p>Que D./Dña {{ $ponente->NOMBRE }} {{ $ponente->APELLIDO1 }} {{ $ponente->APELLIDO2 }}, con DNI {{ $ponente->DNI }} ha percibido por su participación en actividades de formación del profesorado en concepto por la ponencia "{{ $ponente->PONENCIA }}" del curso "{{ $curso->NOMBRE }}", celebrado en Melilla durante los días: {{ $curso->INICIO }} al {{ $curso->FIN }}, las siguientes cantidades tributarias:</p>

            <div class="separador"></div>

            <table class="table centered">
                <tr>
                    <td>
                        <b>TOTAL ÍNTEGRO</b>
                    </td>
                    <td class="text-right">
                        {{ $gastos }}€
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>RETENCIÓN IRPF: </b>{{ $irpf }}%
                    </td>
                    <td class="text-right">
                        {{ $irpf_cantidad }}€
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>TOTAL LÍQUIDO:</b>
                    </td>
                    <td class="text-right">
                        {{ $cantidad }}€
                    </td>
                </tr>
            </table>

            <p><b>Corresponde la cantidad líquida a:</b> {{ $gastosLetras }}</p>

            <p>Y para que conste, y a los efectos de la correspondiente declaración del impuesto sobre la Renta de las personas Físicas firmo la presente certificación en Melilla a {{ date('d') }} de {{ $meses[date('n')-1] }} de {{ date('Y') }}</p>

            <div class="separador"></div>

            <table class="table-invisible">
                <tr>
                    <td>
                        <b>Recibí:</b>
                    </td>
                    <td class="text-right">
                        <b>Fdo.: {{ $director }}</b></br>
                        <b>DIRECTOR/A PROVINCIAL DEL MECD</b>
                    </td>
                </tr>
            </table>

            <div class="separador"></div>

            <b>Ejemplar para la Dirección Provincial</b>

        </div>
    </div>
    <div class="page">
        <div class="content">

            <h3>D./Dª. {{ $director }}, DIRECTOR/A PROVINCIAL DEL MECD.</h3>

            <p><b>Hace constar:</b></p>

            <p>Que D./Dña {{ $ponente->NOMBRE }} {{ $ponente->APELLIDO1 }} {{ $ponente->APELLIDO2 }}, con DNI {{ $ponente->DNI }} ha percibido por su participación en actividades de formación del profesorado en concepto por la ponencia "{{ $ponente->PONENCIA }}" del curso "{{ $curso->NOMBRE }}", celebrado en Melilla durante los días: {{ $curso->INICIO }} al {{ $curso->FIN }}, las siguientes cantidades tributarias:</p>

            <div class="separador"></div>

            <table class="table centered">
                <tr>
                    <td>
                        <b>TOTAL ÍNTEGRO</b>
                    </td>
                    <td class="text-right">
                        {{ $gastos }}€
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>RETENCIÓN IRPF: </b>{{ $irpf }}%
                    </td>
                    <td class="text-right">
                        {{ $irpf_cantidad }}€
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>TOTAL LÍQUIDO:</b>
                    </td>
                    <td class="text-right">
                        {{ $cantidad }}€
                    </td>
                </tr>
            </table>

            <p><b>Corresponde la cantidad líquida a:</b> {{ $gastosLetras }}</p>

            <p>Y para que conste, y a los efectos de la correspondiente declaración del impuesto sobre la Renta de las personas Físicas firmo la presente certificación en Melilla a {{ date('d') }} de {{ $meses[date('n')-1] }} de {{ date('Y') }}</p>

            <div class="separador"></div>

            <table class="table-invisible">
                <tr>
                    <td class="text-right">
                        <b>Fdo.: {{ $director }}</b></br>
                        <b>DIRECTOR/A PROVINCIAL DEl MECD</b>
                    </td>
                </tr>
            </table>

            <div class="separador"></div>

            <b>Ejemplar para el interesado</b>

        </div>
    </div>
</body>
</html>