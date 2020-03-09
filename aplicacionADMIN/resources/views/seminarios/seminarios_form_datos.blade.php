@extends('seminarios.seminarios_form_layout')

@section('top-bar')
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}
@stop

@section('form-tab')

    {!! Form::open(array('form' => 'seminarioDatosForm', 'action' => ['SeminariosController@guardarDatos', $datos->CODIGO], 'method' => 'post')) !!}
    @if(count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {!! $error !!} </br>
            @endforeach
        </div>
    @endif

    @if(isset($datos))
        {!! Form::hidden('CODIGO', $datos->CODIGO) !!}
    @endif

    <!-- Sección de formulario -->
    <div class="form-tab active">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">

                <h4 class="heading">Datos del seminario/grupo de trabajo</h4>

                <div class="row">

                    <!-- Fila -->
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('NOMBRE', 'Denominación:') !!}
                            {!! Form::text('NOMBRE', Helper::issetor($datos->NOMBRE), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('NI', 'NI:') !!}
                            {!! Form::text('NI', Helper::issetor($datos->NI), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('CENTRO', 'Centro:') !!}
                            {!! Form::select('CENTRO', $centros, Helper::issetor($datos->CENTRO), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('TIPO', 'Modalidad formativa:') !!}
                            {!! Form::select('TIPO', $modalidades, Helper::issetor($datos->TIPO), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('INICIO', 'Inicio:') !!}
                            {!! Form::input("date", "INICIO", Helper::issetor($datos->INICIO), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('FIN', 'Fin:') !!}
                            {!! Form::input("date", "FIN", Helper::issetor($datos->FIN), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('HORAS', 'Horas:') !!}
                            {!! Form::text('HORAS', Helper::issetor($datos->HORAS), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('TEMATICA', 'Temática:') !!}
                            {!! Form::text('TEMATICA', Helper::issetor($datos->TEMATICA), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('ASESOR', 'Asesor:') !!}
                            {!! Form::text('ASESOR', Helper::issetor($datos->ASESOR), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('HORARIO', 'Horario') !!}
                            {!! Form::text('HORARIO', Helper::issetor($datos->HORARIO), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            {!! Form::label('LUGAR', 'Lugar:') !!}
                            {!! Form::text('LUGAR', Helper::issetor($datos->LUGAR), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('ESTADO', 'Estado:') !!}
                            {!! Form::select('ESTADO', $estados, Helper::issetor($datos->ESTADO), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('REUNION', 'Fecha de reunión:') !!}
                            {!! Form::text('REUNION', Helper::issetor($datos->REUNION), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('APROBADO', 'Fecha de aprobación de la certificación:') !!}
                            {!! Form::input("date", "APROBADO", Helper::issetor($datos->aprobado), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        {!! Form::label('ADD', 'Añadir/cambiar solicitante:') !!}
                        <div class="form-inline">
                            {!! Form::text('dniSolicitante', $solicitante['dni'], array('class' => 'form-control', 'placeholder' => 'DNI')) !!}
                            <a href="#" id="buscar-dni-profesor-btn" class="btn btn-success"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            {!! Form::label('SOLICITANTE', 'Solicitante:') !!}
                            {!! Form::text('SOLICITANTE', Helper::issetor($solicitante['nombre']), array('class' => 'form-control', 'id' => 'soltext')) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Fin Box -->
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">

                <h4 class="heading">Visita informada</h4>

                <div class="row">

                    <!-- Fila -->
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('DIA1', 'Primera visita:') !!}
                            {!! Form::input("date", "DIA1", Helper::issetor($datos->DIA1), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('DIA2', 'Segunda visita:') !!}
                            {!! Form::input("date", "DIA2", Helper::issetor($datos->DIA2), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('TEMA1', 'Temas tratados:') !!}
                            {!! Form::textarea('TEMA1', Helper::issetor($datos_adicionales->tema1), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('TEMA2', 'Temas tratados:') !!}
                            {!! Form::textarea('TEMA2', Helper::issetor($datos_adicionales->tema2), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('CUESTIONES', 'Cuestiones demandadas - soluciones propuestas:') !!}
                            {!! Form::textarea('CUESTIONES', Helper::issetor($datos_adicionales->cuestiones), array('class' => 'form-control', 'rows' => '5', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('OBSERVACIONES', 'Observaciones:') !!}
                            {!! Form::textarea('OBSERVACIONES', Helper::issetor($datos_adicionales->observaciones), array('class' => 'form-control', 'rows' => '5', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Box -->
    </div>
    <!-- Fin sección de formulario -->
    {!! Form::submit('Guardar', array('id' => 'submitForm')) !!}
    {!! Form::close() !!}

@stop

@section('scripts')

<script>
    
    // añadir solicitante
    $('#buscar-dni-profesor-btn').click(function(e) {
        var dniSolicitante = document.getElementsByName("dniSolicitante")[0].value;
        var idSeminario = document.getElementsByName("CODIGO")[0].value;

        if (dniSolicitante == "") {
            alert("Introduzca DNI del solicitante")
        } else {
            var parametros = {
                "idSeminario" : idSeminario,
                "dniSolicitante" : dniSolicitante
            };

            var url = "/seminarios/" + idSeminario + "/addsolicitante/" + dniSolicitante;
            updateView(url, parametros, "#soltext");
        }
    });

</script>

@stop