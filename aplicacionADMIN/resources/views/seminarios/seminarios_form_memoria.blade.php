@extends('seminarios.seminarios_form_layout')

@section('top-bar')
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}
@stop

@section('form-tab')

    {!! Form::open(array('form' => 'seminarioMemoriaForm', 'action' => ['SeminariosController@guardarMemoria', $idSeminario], 'method' => 'post')) !!}
    {!! Form::hidden('CODIGO', $idSeminario) !!}
    <!-- Sección de formulario -->
    <div class="form-tab">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">
                <h4 class="heading">Memoria</h4>
                <div class="row">

                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Características generales</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::textarea('CARACTERISTICAS', Helper::issetor($memoria->CARACTERISTICAS), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->

                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Objetivos</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::textarea('OBJETIVOS', Helper::issetor($memoria->OBJETIVOS), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->

                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Participantes</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::textarea('PARTICIPANTES', Helper::issetor($memoria->PARTICIPANTES), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->

                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Desarrollo</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('CONTENIDOS', 'Contenidos y actividades desarrolladas:') !!}
                                        {!! Form::textarea('CONTENIDOS', Helper::issetor($memoria->CONTENIDOS), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('METODOLOGIA', 'Metodología de trabajo:') !!}
                                        {!! Form::textarea('METODOLOGIA', Helper::issetor($memoria->METODOLOGIA), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('FORMACION', 'Persona/s que han llevado a cabo la formación:') !!}
                                        {!! Form::textarea('FORMACION', Helper::issetor($memoria->FORMACION), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->

                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Cambios</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('COBJETIVOS', 'Objetivos:') !!}
                                        {!! Form::textarea('COBJETIVOS', Helper::issetor($memoria->COBJETIVOS), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('CCONTENIDOS', 'Contenidos:') !!}
                                        {!! Form::textarea('CCONTENIDOS', Helper::issetor($memoria->CCONTENIDOS), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('cactividades', 'Actividades:') !!}
                                        {!! Form::textarea('cactividades', Helper::issetor($memoria->cactividades), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('CMETODOLOGIA', 'Metodología:') !!}
                                        {!! Form::textarea('CMETODOLOGIA', Helper::issetor($memoria->CMETODOLOGIA), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('CRECURSOS', 'Recursos:') !!}
                                        {!! Form::textarea('CRECURSOS', Helper::issetor($memoria->CRECURSOS), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('CTEMPORALIZACION', 'Temporalización:') !!}
                                        {!! Form::textarea('CTEMPORALIZACION', Helper::issetor($memoria->CTEMPORALIZACION), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->

                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Materiales elaborados</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::textarea('MATERIALES', Helper::issetor($memoria->MATERIALES), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->

                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Necesidades de formación</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::textarea('NECESIDADES', Helper::issetor($memoria->NECESIDADES), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->

                </div>
            </div>
        </div>
        <!-- Fin Box -->
    </div>
    <!-- Fin Sección de formulario -->
    {!! Form::submit('Guardar', array('id' => 'submitForm')) !!}
    {!! Form::close() !!}

@stop

@section('scripts')

<script>

</script>

@stop