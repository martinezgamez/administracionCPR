@extends('layout')

@section('title') Cursos @stop

@section('section-title') Cursos @stop

@section('topbar')

    <a href="{!! URL::to('/cursos', array(), false); !!}" class="btn btn-sm btn-primary pull-left">Listado de cursos</a>

@append

@section('content')

    <h1>{!! $idCurso !!} - {!! $nombreCurso !!}</h1>

    <hr />

    <div class="row">
        <div class="col-md-6">
            <ul id="nav-form" class="nav nav-pills">
              <li role="presentation"><a href="{!! action("CursosController@cursoDatos", $idCurso) !!}">Datos</a></li>
              <li role="presentation"><a href="{!! action("CursosController@cursoDetalles", $idCurso) !!}">Detalles</a></li>
              <li role="presentation"><a href="{!! action("CursosController@cursoPonentes", $idCurso) !!}">Ponentes</a></li>
              <li role="presentation"><a href="{!! action("CursosController@cursoSolicitudes", $idCurso) !!}">Solicitudes</a></li>
              <li role="presentation"><a href="{!! action("CursosController@cursoMateriales", $idCurso) !!}">Materiales</a></li>
              <li role="presentation"><a href="{!! action("CursosController@cursoAsistencia", $idCurso) !!}">Asistencia</a></li>
            </ul>
        </div>
        <div class="col-md-6">
            <div class="btn-group pull-right" role="group">
                <a class="btn btn-sm btn-success" href="{!! URL::to('informes/curso/'.$idCurso.'/informe-inicial') !!}"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Informe inicial</a>
                <a class="btn btn-sm btn-success" href="{!! URL::to('informes/curso/'.$idCurso.'/informe-final') !!}"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Informe final</a>
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
    function updateView(url, view, parametros, callback){
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
                    $(view).html(data);
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
                url:   url,
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