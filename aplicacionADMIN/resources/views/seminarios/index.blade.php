@extends('layout')

@section('title') Seminarios/grupos de trabajo @stop

@section('section-title') Seminarios/grupos de trabajo @stop

@section('topbar')
    <a href="{!! URL::to('/', array(), false); !!}" class="btn btn-sm btn-primary pull-left">Dashboard</a>
    <a href="{!! URL::to("seminarios/create") !!}" class="btn btn-sm btn-default pull-right">Nuevo seminario/grupo de trabajo</a>
@stop

@section('content')

    <h1>Lista de seminarios/grupos de trabajo</h1>

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
                @if($activos == 'si')
                    <input type="checkbox" id="cbactivos" value="si" onclick="checkboxPulsado()" checked> Mostrar sólo seminarios activos
                @else
                    <input type="checkbox" id="cbactivos" value="no" onclick="checkboxPulsado()"> Mostrar sólo seminarios activos
                @endif

                {!! Form::hidden('ORDEN', $orden, ['id' => 'ORDEN']) !!}
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
            var checkBox = document.getElementById("cbactivos");
            var orden = document.getElementById("ORDEN").value;

            if (checkBox.checked == true){
                dbSearch($(this).val(), $('#items_per_page').val(), "si", orden);
            } else {
                dbSearch($(this).val(), $('#items_per_page').val(), "no", orden);
            }
        });

        $('#items_per_page').change(function(){
            var checkBox = document.getElementById("cbactivos");
            var orden = document.getElementById("ORDEN").value;
            
            if (checkBox.checked == true){
                dbSearch($('#buscar').val(), $(this).val(), "si", orden);
            } else {
                dbSearch($('#buscar').val(), $(this).val(), "no", orden);
            }
        });

        function checkboxPulsado() {
            var checkBox = document.getElementById("cbactivos");
            var orden = document.getElementById("ORDEN").value;

            if (checkBox.checked == true){
                checkBox.value = 'si';
                dbSearch($('#buscar').val(), $('#items_per_page').val(), "si", orden);
            } else {
                checkBox.value = 'no';
                dbSearch($('#buscar').val(), $('#items_per_page').val(), "no", orden);
            }
        }

        function dbSearch(searchStr, items_per_page, checkbox, orden){
            var parametros = {
                    "searchStr" : searchStr,
                    "items_per_page" : items_per_page,
                    "activos" : checkbox,
                    "orden" : orden
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                data:  parametros,
                url:   '{!! URL::to('/seminarios/search', array(), false); !!}',
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

        function ordenar(orden) {
            var checkBox = document.getElementById("cbactivos");
            var ordenHidden = document.getElementById("ORDEN");
            ordenHidden.value = orden;

            if (checkBox.checked == true){
                checkBox.value = 'si';
                dbSearch($('#buscar').val(), $('#items_per_page').val(), "si", orden);
            } else {
                checkBox.value = 'no';
                dbSearch($('#buscar').val(), $('#items_per_page').val(), "no", orden);
            }
        }
    </script>

@stop