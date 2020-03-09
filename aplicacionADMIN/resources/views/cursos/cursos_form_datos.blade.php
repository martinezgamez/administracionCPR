@extends('cursos.cursos_form_layout')

@section('top-bar')
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}
@stop

@section('form-tab')

    {!! Form::open(array('form' => 'cursoDatosForm', 'action' => ['CursosController@editDatos', $datos->CODIGO], 'method' => 'post')) !!}
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
        <div class="col-md-6">
            <div class="box">

                <h4 class="heading">Datos básicos</h4>

                <div class="row">

                    <!-- Fila -->
                    <div class="col-md-10">
                        <div class="form-group">
                            {!! Form::label('NOMBRE', 'Denominación') !!}
                            {!! Form::text('NOMBRE', Helper::issetor($datos->NOMBRE), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('ANNO', 'Curso') !!}
                            {!! Form::text('ANNO', Helper::issetor($datos->ANNO), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('PLAZAS', 'Plazas') !!}
                            {!! Form::text('PLAZAS', Helper::issetor($datos->PLAZAS), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('HORARIO', 'Horario') !!}
                            {!! Form::text('HORARIO', Helper::issetor($datos->HORARIO), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('NIVEL', 'Nivel') !!}
                            {!! Form::select('NIVEL', $niveles, Helper::issetor($datos->NIVEL), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('LUGAR', 'Lugar') !!}
                            {!! Form::text('LUGAR', Helper::issetor($datos->LUGAR), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('DURACION', 'Duración') !!}
                            {!! Form::text('DURACION', Helper::issetor($datos->DURACION), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('PRESENCIAL', 'Presencial') !!}
                            {!! Form::text('PRESENCIAL', Helper::issetor($datos->PRESENCIAL), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('NOPRESENCIAL', 'No Presencial') !!}
                            {!! Form::text('NOPRESENCIAL', '', array('class' => 'form-control', 'readonly' => true, 'disabled' => true)) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('DESTINO', 'Dirigido a') !!}
                            {!! Form::text('DESTINO', Helper::issetor($dirigido_a->DESTINO), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('CNOMBRE', 'Coordina') !!}
                            {!! Form::text('CNOMBRE', Helper::issetor($datos->CNOMBRE), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('CCARGO', 'Cargo de coordinador') !!}
                            {!! Form::text('CCARGO', Helper::issetor($datos->CCARGO), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('CNIF', 'DNI de coordinador') !!}
                            {!! Form::text('CNIF', Helper::issetor($datos->CNIF), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('DNOMBRE', 'Dirige') !!}
                            {!! Form::text('DNOMBRE', Helper::issetor($datos->DNOMBRE), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('DCARGO', 'Cargo de director') !!}
                            {!! Form::text('DCARGO', Helper::issetor($datos->DCARGO), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('DNIF', 'DNI de director') !!}
                            {!! Form::text('DNIF', Helper::issetor($datos->DNIF), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('AREA', 'Area') !!}
                            {!! Form::text('AREA', Helper::issetor($datos->AREA), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('MODALIDAD', 'Modalidad formativa') !!}
                            {!! Form::text('MODALIDAD', Helper::issetor($datos->MODALIDAD), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Fin Box -->
        <!-- Box -->
        <div class="col-md-6">
            <div class="box">

                <h4 class="heading">Fechas</h4>

                <div class="row">

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('INICIO', 'Fecha inicio:') !!}
                            {!! Form::input("date", "INICIO", Helper::issetor($datos->INICIO), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('FIN', 'Fecha fin:') !!}
                            {!! Form::input("date", "FIN", Helper::issetor($datos->FIN), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('MATRICULACION', 'Fecha fin matriculación:') !!}
                            {!! Form::input("date", "MATRICULACION", Helper::issetor($datos->MATRICULACION), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('CIERRE', 'Fecha cierre') !!}
                            {!! Form::input("date", "CIERRE", Helper::issetor($datos->CIERRE), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('ANUNCIO', 'Fecha publicación') !!}
                            {!! Form::input("date", "ANUNCIO", Helper::issetor($datos->ANUNCIO), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('CERTIFICACION', 'Fecha certificación') !!}
                            {!! Form::input("date", "CERTIFICACION", Helper::issetor($datos->CERTIFICACION), array( 'class' => 'form-control' )) !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- Fin Box -->
        <!-- Box -->
        <div class="col-md-6">
            <div class="box">

                <h4 class="heading">Requisitos</h4>

                <div class="row">

                    <!-- Fila -->
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('REQUISITOS', 'Requisitos:') !!}
                            {!! Form::textarea('REQUISITOS', Helper::issetor($datos->REQUISITOS), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Fin Box -->
    </div>
    <!-- Fin sección de formulario -->
    {!! Form::submit('Guardar', array('id' => 'submitForm', )) !!}
    {!! Form::close() !!}

@stop

@section('scripts')

<script>

    function actualizarNoPresenciales(){
        var duracion = $("#DURACION").attr("value");
        var presencial = $("#PRESENCIAL").attr("value");
        var noPresencial = duracion - presencial;
        $("#NOPRESENCIAL").attr("value", noPresencial);
        //$("#NOPRESENCIAL").html(noPresencial);
    }

    actualizarNoPresenciales();

    $('#DURACION').focusout(actualizarNoPresenciales());
    $('#PRESENCIAL').focusout(actualizarNoPresenciales());

</script>

@stop