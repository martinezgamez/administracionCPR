@extends('cursos.cursos_form_layout')

@section('top-bar')

@stop

@section('form-tab')

    <!-- Sección de formulario -->
    <div class="form-tab active">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">

                <div class="row">

                    <div class="col-md-6">

                        <ul id="nav-form" class="nav nav-pills">
                            <li><a href="{!! URL::to('/cursos/'.$idCurso.'/ponentes') !!}">Ponencia</a></li>
                            <li><a href="{!! URL::to('/cursos/'.$idCurso.'/ponentes/presupuesto') !!}">Presupuestos</a></li>
                            <li><a href="{!! URL::to('/cursos/'.$idCurso.'/ponentes/gastos') !!}">Gastos</a></li>
                        </ul>

                    </div>

                    <div class="col-md-6">
                        <div class="pull-right">
                            <div class="form-inline">
                                {!! Form::text('buscar_dni', '', array('class' => 'form-control', 'placeholder' => 'DNI', 'id' => 'buscar_dni_ponente')) !!}
                                <a href="#" id="buscar-dni-ponente-btn" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></a>
                            </div>
                        </div>
                    </div>

                </div>

                <hr />

                <h4 class="heading">Ponentes: Presupuesto de docencia</h4>

                <div id="ponentes-presupuesto-docencia" class="row">

                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Horas</th>
                                <th>Precio (€)</th>
                                <th>Importe (€)</th>
                                <th>IRPF (%)</th>
                                <th>Importe IRPF (€)</th>
                                <th>Total (€)</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(isset($ponentes) && count($ponentes) > 0)
                                <?php $total = 0; ?>
                                @foreach($ponentes as $ponente)

                                    <?php
                                        $importe = $ponente->HORAS * $ponente->PRECIO;
                                        $importeIRPF = ($importe * $ponente->IRPF)/100;
                                    ?>

                                    <tr>
                                        <td class="col-md-1">
                                            {!! $ponente->CODIGO !!}
                                        </td>
                                        <td class="col-md-1">
                                            {!! $ponente->DNI !!}
                                        </td>
                                        <td class="col-md-4">
                                            {!! $ponente->NOMBRE !!} {!! $ponente->APELLIDO1 !!} {!! $ponente->APELLIDO2 !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("HORAS:".$ponente->PONENTE, $ponente->HORAS, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("PRECIO:".$ponente->PONENTE, $ponente->PRECIO, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="col-md-1">
                                            {!! Form::text("IMPORTE:".$ponente->PONENTE, $importe, array( 'id' => 'IMPORTE:'.$ponente->PONENTE, 'class' => 'form-control', 'readonly' => true )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("IRPF:".$ponente->PONENTE, $ponente->IRPF, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="col-md-1">
                                            {!! Form::text("IMPORTEIRPF:".$ponente->PONENTE, $importeIRPF, array( 'id' => 'IMPORTEIRPF:'.$ponente->PONENTE, 'class' => 'form-control', 'readonly' => true )) !!}
                                        </td>
                                        <td class="col-md-1">
                                            {!! Form::text("TOTAL:".$ponente->PONENTE, $importe - $importeIRPF, array( 'id' => 'TOTAL:'.$ponente->PONENTE, 'class' => 'form-control', 'readonly' => true )) !!}
                                        </td>
                                    </tr>
                                    <?php $total +=  ($importe - $importeIRPF); ?>
                                @endforeach
                                <tr>
                                    <td colspan="8"></td>
                                    <td>
                                        {!! Form::text("TOTAL", $total, array( 'id' => 'TOTAL', 'class' => 'form-control', 'readonly' => true )) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="9">
                                        Aún no hay ningún ponente para este curso.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>

                </div>

                <h4 class="heading">Ponentes: Presupuesto de transporte</h4>

                <div id="ponentes-presupuesto-desplazamiento" class="row">

                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Avión (€)</th>
                                <th>Tren (€)</th>
                                <th>Taxi (€)</th>
                                <th>Bus (€)</th>
                                <th>Coche (€)</th>
                                <th>Barco (€)</th>
                                <th>Otros (€)</th>
                                <th>Total (€)</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(isset($ponentes) && count($ponentes) > 0)
                            <?php $total = 0; ?>
                                @foreach($ponentes as $ponente)

                                    <?php
                                        $totalTransporte = $ponente->AVION + $ponente->TREN + $ponente->TAXI + $ponente->BUS + $ponente->COCHE + $ponente->BARCO + $ponente->OTROS;
                                    ?>

                                    <tr>
                                        <td class="col-md-1">
                                            {!! $ponente->CODIGO !!}
                                        </td>
                                        <td class="col-md-1">
                                            {!! $ponente->DNI !!}
                                        </td>
                                        <td class="col-md-2">
                                            {!! $ponente->NOMBRE !!} {!! $ponente->APELLIDO1 !!} {!! $ponente->APELLIDO2 !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("AVION:".$ponente->PONENTE, $ponente->AVION, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("TREN:".$ponente->PONENTE, $ponente->TREN, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("TAXI:".$ponente->PONENTE, $ponente->TAXI, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("BUS:".$ponente->PONENTE, $ponente->BUS, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("COCHE:".$ponente->PONENTE, $ponente->COCHE , array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("BARCO:".$ponente->PONENTE, $ponente->BARCO, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("OTROS:".$ponente->PONENTE, $ponente->OTROS, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td>
                                            {!! Form::text("TOTALTRANSPORTE:".$ponente->PONENTE, $totalTransporte, array( 'class' => 'form-control', 'readonly' => true )) !!}
                                        </td>
                                    </tr>
                                    <?php $total += $totalTransporte; ?>
                                @endforeach
                                <tr>
                                    <td colspan="10"></td>
                                    <td>
                                        {!! Form::text("TOTAL", $total, array( 'id' => 'TOTAL', 'class' => 'form-control', 'readonly' => true )) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="11">
                                        Aún no hay ningún ponente para este curso.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>

                </div>

                <h4 class="heading">Ponentes: Presupuesto de alojamiento y dietas</h4>

                <div id="ponentes-presupuesto-desplazamiento" class="row">

                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Dias alojamiento</th>
                                <th>Precio alojamiento (€)</th>
                                <th>Total alojamiento (€)</th>
                                <th>Dias dietas</th>
                                <th>Precio dietas (€)</th>
                                <th>Total dietas (€)</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(isset($ponentes) && count($ponentes) > 0)
                                <?php $total = 0; ?>
                                @foreach($ponentes as $ponente)

                                    <?php
                                        $totalAlojamiento = $ponente->ALOJDIAS * $ponente->ALOJPRECIO;
                                        $totalDietas = $ponente->DIETDIAS * $ponente->DIETPRECIO;
                                    ?>

                                    <tr>
                                        <td class="col-md-1">
                                            {!! $ponente->CODIGO !!}
                                        </td>
                                        <td class="col-md-1">
                                            {!! $ponente->DNI !!}
                                        </td>
                                        <td class="col-md-4">
                                            {!! $ponente->NOMBRE !!} {!! $ponente->APELLIDO1 !!} {!! $ponente->APELLIDO2 !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("ALOJDIAS:".$ponente->PONENTE, $ponente->ALOJDIAS, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("ALOJPRECIO:".$ponente->PONENTE, $ponente->ALOJPRECIO, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="col-md-1">
                                            {!! Form::text("TOTAL:".$ponente->PONENTE, $totalAlojamiento, array( 'class' => 'form-control', 'readonly' => true )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("DIETDIAS:".$ponente->PONENTE, $ponente->DIETDIAS, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::text("DIETPRECIO:".$ponente->PONENTE, $ponente->DIETPRECIO , array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="col-md-1">
                                            {!! Form::text("TOTAL:".$ponente->PONENTE, $totalDietas, array( 'class' => 'form-control', 'readonly' => true )) !!}
                                        </td>
                                    </tr>
                                    <?php $total += $totalDietas + $totalAlojamiento; ?>
                                @endforeach
                                <tr>
                                    <td colspan="8"></td>
                                    <td>
                                        {!! Form::text("TOTAL", $total, array( 'id' => 'TOTAL', 'class' => 'form-control', 'readonly' => true )) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="9">
                                        Aún no hay ningún ponente para este curso.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
        <!-- Fin Box -->
    </div>
    <!-- Fin Sección de formulario -->

@stop

@section('scripts')

<script>

    // Añadir ponente
    $('#buscar-dni-ponente-btn').click(function(e){

        e.preventDefault();

        var dniPonente = $('#buscar_dni_ponente').val();
        var route = "ponente/"+dniPonente;
        console.log(route);

        updateData(route);
        location.reload();

    });

    $(".editable-cell > input[type=text], .editable-cell > input[type=date]").blur(function(){

        var tmp = $(this).attr("name").split(":");
        var campo = $(this).attr('name');
        var valor = $(this).val();
        if (!$.isNumeric(valor)) {
            valor = 0;
            //alert("valor numerico requerido");
        }
        parametros = {
            fieldName: tmp[0],
            value: valor
        };

        var url = "edit/"+tmp[1];
        updateData(url, parametros);

        //console.log(fieldName + " " + idCurso + " " + sesionNum + " " + idProfesor);

    });

</script>

@stop