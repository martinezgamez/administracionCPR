@extends('seminarios.seminarios_form_layout')

@section('top-bar')
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}
@stop

@section('form-tab')

    {!! Form::open(array('form' => 'SeminarioAsesorForm', 'action' => ['SeminariosController@guardarAsesor', $idSeminario], 'method' => 'post')) !!}
    @if(isset($obs))
        {!! Form::hidden('CODIGO', $idSeminario) !!}
    @endif

    <!-- Sección de formulario -->
    <div class="form-tab">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">
                <h4 class="heading">Asesor final</h4>
                <div class="row">
                    <!-- Fila -->
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('VALFINALSOL', 'Valoración:') !!}
                            {!! Form::textarea('VALFINALSOL', Helper::issetor($obs->VALFINALSOL), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('OBSFINALSOL', 'Observaciones del asesor:') !!}
                            {!! Form::textarea('OBSFINALSOL', Helper::issetor($obs->OBSFINALSOL), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

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