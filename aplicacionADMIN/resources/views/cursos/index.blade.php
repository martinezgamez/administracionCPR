@extends('layout')

@section('title') Cursos @stop

@section('section-title') Cursos @stop

@section('topbar')
    <a href="{!! URL::to('/', array(), false); !!}" class="btn btn-sm btn-primary pull-left">Dashboard</a>
    <a href="{!! URL::to("cursos/create") !!}" class="btn btn-sm btn-default pull-right">Nuevo curso</a>
@stop

@section('content')

    <h1>Lista de cursos</h1>

    <hr />

    <div class="row">
        <div class="col-md-12">

            <div class="box">

                <div class="row datatable_head">

                    <div class="col-md-6">
                        <div class="pull-left">
                            {!! Form::select('items_per_page', array("10"=>"10","25"=>"25","50"=>"50","100"=>"100"), $items, array('class' => 'form-control', 'id' => 'items_per_page')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="pull-right">
                            {!! Form::text('buscar', $search, array('class' => 'form-control', 'placeholder' => 'Buscar...', 'id' => 'buscar')) !!}
                        </div>
                    </div>

                </div>

                <!-- Subvista con la tabla de cursos -->
                <div id="datatablediv">
                    {!! $datatable !!}
                </div>

            </div>
        </div>
    </div>

@stop

@section('scripts')

    <script>

        $('#buscar').keyup(function(){
            dbSearch($(this).val(), $('#items_per_page').val());
        });

        $('#items_per_page').change(function(){
            dbSearch($('#buscar').val(), $(this).val());
        });

        function dbSearch(searchStr, items_per_page){

                var parametros = {
                        "searchStr" : searchStr,
                        "items_per_page" : items_per_page
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                        data:  parametros,
                        url:   '{!! URL::to('/cursos/search', array(), false); !!}',
                        type:  'post',
                        beforeSend: function () {

                        },
                        success:  function (data) {
                            $('#datatablediv').html(data);
                        },
                        error: function( error ) {
                            console.log(error.responseText);
                        }
                });
        }
    </script>

@stop