@extends('cursos.cursos_form_layout')

@section('top-bar')
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}
@stop

@section('form-tab')

    {!! Form::open(array('form' => 'cursoDetallesForm', 'action' => ['CursosController@editDetalles', $idCurso], 'method' => 'post')) !!}
    @if(isset($detalles))
        {!! Form::hidden('CURSO', $idCurso) !!}
    @endif

    <!-- Sección de formulario -->
    <div class="form-tab">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">

                <h4 class="heading">Detalles</h4>

                <div class="row">

                    <!-- Fila -->
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('JUSTIFICACION', 'Justificación') !!}
                            {!! Form::textarea('JUSTIFICACION', Helper::issetor($detalles->JUSTIFICACION), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('OBJETIVOS', 'Objetivos') !!}
                            {!! Form::textarea('OBJETIVOS', Helper::issetor($detalles->OBJETIVOS), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('CONTENIDOS', 'Contenidos') !!}
                            {!! Form::textarea('CONTENIDOS', Helper::issetor($detalles->CONTENIDOS), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('METODOLOGIA', 'Metodología') !!}
                            {!! Form::textarea('METODOLOGIA', Helper::issetor($detalles->METODOLOGIA), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('RECURSOS', 'Recursos') !!}
                            {!! Form::textarea('RECURSOS', Helper::issetor($detalles->RECURSOS), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('MATERIALES', 'Materiales') !!}
                            {!! Form::textarea('MATERIALES', Helper::issetor($detalles->MATERIALES), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('OBSERVACIONES', 'Observaciones') !!}
                            {!! Form::textarea('OBSERVACIONES', Helper::issetor($detalles->OBSERVACIONES), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('EVALUACION', 'Evaluación') !!}
                            {!! Form::textarea('EVALUACION', Helper::issetor($detalles->EVALUACION), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('VALORACION', 'Valoración') !!}
                            {!! Form::textarea('VALORACION', Helper::issetor($detalles->VALORACION), array('class' => 'form-control', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Fin Box -->

    </div>
    <!-- Fin Sección de formulario -->
    {!! Form::submit('Guardar', array('id' => 'submitForm', )) !!}
    {!! Form::close() !!}

@stop

@section('scripts')

<script>



</script>

@stop