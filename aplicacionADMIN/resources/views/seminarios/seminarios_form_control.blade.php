@extends('seminarios.seminarios_form_layout')

@section('top-bar')
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}
@stop

@section('form-tab')

    {!! Form::open(array('form' => 'semiarioControlForm', 'action' => ['SeminariosController@guardarControl', $idSeminario], 'method' => 'post')) !!}
    {!! Form::hidden('CODIGO', $idSeminario) !!}

    <!-- Sección de formulario -->
    <div class="form-tab">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">
                <h4 class="heading">Control</h4>
                <div class="row">
                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Fechas de visitas</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('T1', 'Primer trimestre:') !!}
                                        {!! Form::textarea('T1', Helper::issetor($dias->T1), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('T2', 'Segundo trimestre:') !!}
                                        {!! Form::textarea('T2', Helper::issetor($dias->T2), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('T3', 'Tercer trimestre:') !!}
                                        {!! Form::textarea('T3', Helper::issetor($dias->T3), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
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