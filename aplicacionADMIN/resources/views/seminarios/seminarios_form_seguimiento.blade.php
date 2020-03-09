@extends('seminarios.seminarios_form_layout')

@section('top-bar')
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}
@stop

@section('form-tab')

    {!! Form::open(array('form' => 'seminarioSeguimientoForm', 'action' => ['SeminariosController@guardarSeguimiento', $idSeminario], 'method' => 'post')) !!}
    {!! Form::hidden('CODIGO', $idSeminario) !!}

    <!-- Sección de formulario -->
    <div class="form-tab">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">
                <h4 class="heading">Seguimiento</h4>
                <div class="row">
                    <!-- Fila -->
                    <div class="col-md-12">
                        <div class="form-group">
                            @if ($checkbox == 1)
                                <input type="checkbox" name="cb" id="cb"  value="1" onclick="cbPulsado(event)" checked> Informe favorable
                            @else
                                <input type="checkbox" name="cb" id="cb"  value="0" onclick="cbPulsado(event)"> Informe favorable
                            @endif
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('SEGDESARROLLO', 'Desarrollo de las actividades propuestas:') !!}
                            {!! Form::textarea('SEGDESARROLLO', Helper::issetor($seguimiento->SEGDESARROLLO), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('REALIZADO', '% Trabajo propuesto realizado:') !!}
                            {!! Form::textarea('REALIZADO', Helper::issetor($seguimiento->REALIZADO), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('METAPLICADA', 'Metodología aplicada:') !!}
                            {!! Form::textarea('METAPLICADA', Helper::issetor($seguimiento->METAPLICADA), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('EMPLEADOS', 'Materiales empleados:') !!}
                            {!! Form::textarea('EMPLEADOS', Helper::issetor($seguimiento->EMPLEADOS), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('CUANTITATIVA', 'Descipción de los materiales elaborados y número de actividades o recursos:') !!}
                            {!! Form::textarea('CUANTITATIVA', Helper::issetor($seguimiento->CUANTITATIVA), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <!-- Fila -->
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('SEGCOORDINADOR', 'Observaciones del coordinador/a:') !!}
                            {!! Form::textarea('SEGCOORDINADOR', Helper::issetor($seguimiento->SEGCOORDINADOR), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('SEGASESOR', 'Observaciones del asesor/a:') !!}
                            {!! Form::textarea('SEGASESOR', Helper::issetor($seguimiento->SEGASESOR), array('class' => 'form-control', 'rows' => '10', 'style' => 'resize: none;')) !!}
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
    function cbPulsado(e) {
        var checkbox = document.getElementById(e.target.id);
        var idSeminario = {!! $idSeminario !!};
        var favorable = checkbox.checked? "si":"no";
        var parametros = {
            "idSeminario" : idSeminario,
            "favorable" : favorable
        };

        var url = "/seminarios/" + idSeminario + "/seguimiento/" + favorable;
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {

            },
            success:  function (data) {

            },
            error: function( error ) {
                console.log(error.responseText);
            }
        });
    }
</script>

@stop