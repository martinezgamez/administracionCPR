@extends('seminarios.seminarios_form_layout')

@section('top-bar')
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}
@stop

@section('form-tab')

    {!! Form::open(array('form' => 'seminarioActasForm', 'action' => ['SeminariosController@guardarActas', $idSeminario], 'method' => 'post')) !!}
    {!! Form::hidden('CODIGO', $idSeminario) !!}
    {!! Form::hidden('SESION', $idSesion) !!}

    <!-- Sección de formulario -->
    <div class="form-tab">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">
                <h4 class="heading">Actas</h4>
                <div class="row">
                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Sesiones</h4>
                            <div class="row">
                                
                                <table class="table table-bordered table-hover table-condensed" id="tabla-sesiones">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>FECHA</th>
                                            <th>INICIO</th>
                                            <th>FIN</th>
                                            <th>LUGAR</th>
                                            <th>REALIZADA</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if(isset($sesiones) && count($sesiones) > 0)
                                            @foreach($sesiones as $sesion)
                                                @if($sesion->NUMERO == $idSesion)
                                                <tr id="{{ $sesion->NUMERO }}" style="background-color: lightblue">
                                                @else
                                                <tr id="{{ $sesion->NUMERO }}">
                                                @endif
                                                    <td class="col-md-1">
                                                        {!! $sesion->NUMERO !!}
                                                    </td>
                                                    <td class="col-md-1">
                                                        {!! $sesion->FECHA !!}
                                                    </td>
                                                    <td class="col-md-1">
                                                        {!! $sesion->INICIO !!}
                                                    </td>
                                                    <td class="col-md-1">
                                                        {!! $sesion->FIN !!}
                                                    </td>
                                                    <td class="col-md-7">
                                                        {!! $sesion->LUGAR !!}
                                                    </td>
                                                    <td class="col-md-1">
                                                        @if ($sesion->REALIZADA == 1)
                                                            <input type="checkbox" id="s:{{ $sesion->NUMERO }}" value="0" onclick="sesionPulsada(event)" checked>
                                                        @else
                                                            <input type="checkbox" id="s:{{ $sesion->NUMERO }}" value="1" onclick="sesionPulsada(event)">
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6">
                                                    No hay ninguna sesión para este seminario.
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Listado de asistencia</h4>
                            <div class="row">

                                <table class="table table-bordered table-hover table-condensed">
                                    <thead>
                                        <tr>
                                            <th>DNI</th>
                                            <th>NOMBRE</th>
                                            <th>APELLIDO1</th>
                                            <th>APELLIDO2</th>
                                            <th>ASISTIDO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($profesores) && count($profesores) > 0)
                                            @foreach($profesores as $profesor)
                                            @if ($profesor->NUMERO == $idSesion)
                                                <tr>
                                                    <td class="col-md-2">
                                                        {!! $profesor->DNI !!}
                                                    </td>
                                                    <td class="col-md-3">
                                                        {!! $profesor->NOMBRE !!}
                                                    </td>
                                                    <td class="col-md-3">
                                                        {!! $profesor->APELLIDO1 !!}
                                                    </td>
                                                    <td class="col-md-3">
                                                        {!! $profesor->APELLIDO2 !!}
                                                    </td>
                                                    <td class="col-md-1">
                                                        @if ($profesor->ASISTIDO == 1)
                                                            <input type="checkbox" id="p:{{ $profesor->PROFESOR }}:s:{{ $profesor->NUMERO }}" value="0" onclick="asistidoPulsado(event)" checked>
                                                        @else
                                                            <input type="checkbox" id="p:{{ $profesor->PROFESOR }}:s:{{ $profesor->NUMERO }}" value="1" onclick="asistidoPulsado(event)">
                                                        @endif
                                                    </td>
                                            @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">
                                                    Sin lista de asistencia para esta sesión.
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->
                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Datos adicionales</h4>
                            <div class="row">
                                <!-- Fila -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('TEMAS', 'Temas tratados:') !!}
                                        {!! Form::textarea('TEMAS', Helper::issetor($infoSesion['TEMAS']), array('class' => 'form-control', 'id' =>'temas','rows' => '5', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('ACUERDOS', 'Acuerdos adoptados:') !!}
                                        {!! Form::textarea('ACUERDOS', Helper::issetor($infoSesion['ACUERDOS']), array('class' => 'form-control', 'id' =>'acuerdos','rows' => '5', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('OBSERVACIONES', 'Observaciones:') !!}
                                        {!! Form::textarea('OBSERVACIONES', Helper::issetor($infoSesion['OBSERVACIONES']), array('class' => 'form-control', 'id' =>'observaciones', 'rows' => '5', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Sección de formulario -->
    {!! Form::submit('Guardar', array('id' => 'submitForm', )) !!}
    {!! Form::close() !!}

@stop

@section('scripts')

<script>

    $("#tabla-sesiones tr").click(function() {
        var trid = $(this).closest('tr').attr('id');
        var idSeminario = {!! $idSeminario !!};
        var idSesion = trid;

        if(!isNaN(idSesion))
        {
            var parametros = {
                "idSeminario" : idSeminario,
                "idSesion" : idSesion
            };
            var url = "/seminarios/" + idSeminario + "/actas/" + idSesion;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                data:  parametros,
                url:   url,
                type:  'get',
                beforeSend: function () {

                },
                success:  function (data) {
                    $("body").html(data);
                },
                error: function( error ) {
                    console.log(error.responseText);
                }
            });
        }
    });

    function sesionPulsada(e) {
        var checkbox = document.getElementById(e.target.id);
        var idSeminario = {!! $idSeminario !!};
        var idSesion = checkbox.id.split(":")[1]
        var realizado = checkbox.checked? "si":"no";
        var parametros = {
            "idSeminario" : idSeminario,
            "idSesion" : idSesion,
            "realizado" : realizado
        };
        var url = "/seminarios/" + idSeminario + "/actas/" + idSesion + "/" + realizado;
        
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
                //location.reload();
            },
            error: function( error ) {
                console.log(error.responseText);
            }
        });
    }

    function asistidoPulsado(e) {
        var checkbox = document.getElementById(e.target.id);
        var idSeminario = {!! $idSeminario !!};
        var idProfesor = checkbox.id.split(":")[1]
        var idSesion = checkbox.id.split(":")[3]
        var asistido = checkbox.checked? "si":"no";

        var parametros = {
            "idSeminario" : idSeminario,
            "idSesion" : idSesion,
            "idProfesor": idProfesor,
            "asistido" : asistido
        };
        var url = "/seminarios/" + idSeminario + "/actas/" + idSesion + "/profesor/" + idProfesor + "/" + asistido;
        
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
                //location.reload();
            },
            error: function( error ) {
                console.log(error.responseText);
            }
        });
    }
</script>

@stop