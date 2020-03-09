@extends('seminarios.seminarios_form_layout')

@section('top-bar')
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}
@stop

@section('form-tab')

    {!! Form::open(array('form' => 'seminarioParticipantesForm', 'action' => ['SeminariosController@guardarParticipantes', $datos->codigo], 'method' => 'post')) !!}
    @if(count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {!! $error !!} </br>
            @endforeach
        </div>
    @endif

    @if(isset($datos))
        {!! Form::hidden('CODIGO', $datos->codigo) !!}
    @endif

    <!-- Sección de formulario -->
    <div class="form-tab active">

        <!-- Box -->
        <div class="col-md-12">
            <div class="box">
                <div class="row">
                    <div class="col-md-3">
                        <ul id="nav-form" class="nav nav-pills">
                            {!! Form::label('HORAS', 'Horas que certifican:') !!}
                            {!! Form::text('HORAS', Helper::issetor($datos->horas_cerfifican), array('class' => 'form-control')) !!}
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul id="nav-form" class="nav nav-pills">
                            {!! Form::label('PORCENTAJE', 'Porcentaje de créditos extras para el coordinador:') !!}
                            {!! Form::text('PORCENTAJE', Helper::issetor($datos->porcentaje), array('class' => 'form-control')) !!}
                        </ul>
                    </div>

                    <div class="col-md-5">
                        <div class="pull-right">
                            {!! Form::label('ADD', 'Añadir profesor:') !!}
                            <div class="form-inline">
                                {!! Form::text('dniParticipante', NULL, array('class' => 'form-control', 'placeholder' => 'DNI')) !!}
                                <a href="#" id="buscar-dni-profesor-btn" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></a>
                            </div>
                        </div>
                    </div>

                </div>

                <hr />

                <h4 class="heading">Participantes</h4>

                <div id="ponentes-list" class="row">

                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Centro</th>
                                <th>Situación administrativa</th>
                                <th>CR</th>
                                <th>CER</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(isset($participantes) && count($participantes) > 0)
                                @php
                                    $i = 0;
                                @endphp
                                @foreach($participantes as $participante)
                                    <tr>
                                        <td class="col-md-1">
                                            {!! $participante->dni !!}
                                        </td>
                                        <td class="col-md-5">
                                            {!! $participante->nombre !!} {!! $participante->apellido1 !!} {!! $participante->apellido2 !!}
                                        </td>
                                        <td class="col-md-3">
                                            {!! $participante->centro !!}
                                        </td>
                                        <td class="col-md-2">
                                            {!! $participante->administracion !!}
                                        </td>
                                        <td class="col-md-1">
                                            @if($participante->coordinador == '0')
                                                <input type="checkbox" id="{{ "cr:".$participante->codigo }}"  onclick="coordinadorPulsado(event)">
                                            @else
                                                <input type="checkbox" id="{{ "cr:".$participante->codigo }}"  onclick="coordinadorPulsado(event)" checked>
                                            @endif
                                        </td>
                                        <td class="col-md-1">
                                            @if($participante->certifica == '0')
                                                <input type="checkbox" id="{{ "cer:".$participante->codigo }}"  onclick="certificaPulsado(event)">
                                            @else
                                                <input type="checkbox" id="{{ "cer:".$participante->codigo }}"  onclick="certificaPulsado(event)" checked>
                                            @endif
                                        </td>
                                        <td>
                                            <a id="btn-delete-participante" href="{!! URL::to("seminarios/".$idSeminario."/participantes/".$participante->codigo."/delete") !!}" class="btn btn-danger center-block" onclick="return confirm('¿Borrar participante?')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                        </td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        Aún no hay ningún participante para este seminario.
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
    {!! Form::submit('Guardar', array('id' => 'submitForm')) !!}
    {!! Form::close() !!}

@stop

@section('scripts')

<script>

    function coordinadorPulsado(e) {
        var checkbox = document.getElementById(e.target.id);
        var idSeminario = {!! $idSeminario !!};
        var idParticipante = checkbox.id.split(":")[1];
        var coordina = checkbox.checked? "si":"no";
        var parametros = {
            "idSeminario" : idSeminario,
            "idParticipante" : idParticipante,
            "coordina" : coordina
        };

        var url = "/seminarios/" + idSeminario + "/crparticipante/" + idParticipante + "/" + coordina;
        
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

    function certificaPulsado(e) {
        var checkbox = document.getElementById(e.target.id);
        var idSeminario = {!! $idSeminario !!};
        var idParticipante = checkbox.id.split(":")[1];
        var certifica = checkbox.checked? "si":"no";
        var parametros = {
            "idSeminario" : idSeminario,
            "idParticipante" : idParticipante,
            "certifica" : certifica
        };

        var url = "/seminarios/" + idSeminario + "/cerparticipante/" + idParticipante + "/" + certifica;
        
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

    // añadir participante
    $('#buscar-dni-profesor-btn').click(function(e) {
        var dniParticipante = document.getElementsByName("dniParticipante")[0].value;
        var idSeminario = document.getElementsByName("CODIGO")[0].value;

        if (dniParticipante == "") {
            alert("Introduzca DNI del participante")
        } else {
            var parametros = {
                "idSeminario" : idSeminario,
                "dniParticipante" : dniParticipante
            };
            var url = "/seminarios/" + idSeminario + "/participantes/" + dniParticipante + "/add";
            
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
                success:  function () {
                    location.reload();
                },
                error: function( error ) {
                    console.log(error.responseText);
                }
            });
        }
    });

</script>

@stop