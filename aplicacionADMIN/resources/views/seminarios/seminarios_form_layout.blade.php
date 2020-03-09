@extends('layout')

@section('title') Seminarios/grupos de trabajo @stop

@section('section-title') Seminarios/grupos de trabajo @stop

@section('topbar')

    <a href="{!! URL::to('/seminarios', array(), false); !!}" class="btn btn-sm btn-primary pull-left">Listado de seminarios/grupos de trabajo</a>

@append

@section('content')

    <h1>{!! $idSeminario !!} - {!! $nombreSeminario !!}</h1>

    <hr />

    <div class="row">
        <div class="col-md-6">
            <ul id="nav-form" class="nav nav-pills">
              <li role="presentation"><a href="{!! action("SeminariosController@datos", $idSeminario) !!}">Datos</a></li>
              <li role="presentation"><a href="{!! action("SeminariosController@participantes", $idSeminario) !!}">Participantes</a></li>
              <li role="presentation"><a href="{!! action("SeminariosController@otrosDatos", $idSeminario) !!}">Otros datos</a></li>
              <li role="presentation"><a href="{!! action("SeminariosController@asesor", $idSeminario) !!}">Asesor final</a></li>
              <li role="presentation"><a href="{!! action("SeminariosController@seguimiento", $idSeminario) !!}">Seguimiento</a></li>
              <li role="presentation"><a href="{!! action("SeminariosController@control", $idSeminario) !!}">Control</a></li>
              <li role="presentation"><a href="{!! action("SeminariosController@actas", $idSeminario) !!}">Actas</a></li>
              <li role="presentation"><a href="{!! action("SeminariosController@memoria", $idSeminario) !!}">Memoria</a></li>
            </ul>
        </div>
        <div class="col-md-6">
            <div class="btn-group pull-right" role="group">
                <a class="btn btn-sm btn-success" href="{!! URL::to('informes/seminario/'.$idSeminario.'/informe-final-asesor') !!}"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Informe final del asesor</a>
                <a class="btn btn-sm btn-success" href="{!! URL::to('informes/seminario/'.$idSeminario.'/propuesta-certificacion') !!}"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Propuesta de certificación</a>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-multi-tab">

        @yield('form-tab')

    </div>

@stop

@section('scripts')

<script>
    // Actualizacion de vistas
    function updateView(url, parametros, view, callback){
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
                    $(view).val(data);
                    if(callback){
                        callback();
                    }
                },
                error: function( error ) {
                    console.log(error.responseText);
                }
        });
    }

    // Inserción de datos
    function updateData(url, parametros, callback){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
                url:  url,
                data: parametros,
                type:  'post',
                beforeSend: function () {

                },
                success:  function (data) {
                    if(callback){
                        callback();
                    }
                    console.log(data);
                },
                error: function( error ) {
                    console.log(error.responseText);
                }
        });
    }
</script>

@append