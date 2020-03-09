@extends('cursos.cursos_form_layout')

@section('top-bar')

@stop

@section('form-tab')

    <!-- Sección de formulario -->
    <div class="form-tab active">

        <!-- Box -->
        <div class="col-md-12">
            <div class="box">

                <div class="row">

                    <div class="col-md-6">

                        <ul id="nav-form" class="nav nav-pills">
                            <li><a href="{!! URL::to('/cursos/'.$idCurso.'/ponentes') !!}">Ponencia</a></li>
                            <li><a href="{!! URL::to('/cursos/'.$idCurso.'/ponentes/presupuesto') !!}">Presupuestos</a></li>
                            <li><a href="{!! URL::to('/cursos/'.$idCurso.'/ponentes/gastos') !!}">Gastos</a></li>
                        </ul>

                    </div>

                    <div class="col-md-6">
                        <div class="pull-right">
                            <div class="form-inline">
                                {!! Form::open(array('id' => 'addPonenteForm', 'form' => 'addPonenteForm', 'action' => ['CursosController@addPonente', $idCurso, 'dniPonente'], 'method' => 'post')) !!}
                                    {!! Form::hidden('idCurso', $idCurso) !!}
                                    {!! Form::text('dniPonente', '', array('class' => 'form-control', 'placeholder' => 'DNI')) !!}
                                    <a href="#" id="buscar-dni-ponente-btn" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></a>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>

                </div>

                <hr />

                <h4 class="heading">Ponentes</h4>

                <div id="ponentes-list" class="row">

                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Fecha de inicio</th>
                                <th>Fecha de fin</th>
                                <th>Ponencia</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(isset($ponentes) && count($ponentes) > 0)
                                @foreach($ponentes as $ponente)
                                    <tr>
                                        <td class="col-md-1">
                                            {!! $ponente->CODIGO !!}
                                        </td>
                                        <td class="col-md-1">
                                            {!! $ponente->DNI !!}
                                        </td>
                                        <td class="col-md-3">
                                            {!! $ponente->NOMBRE !!} {!! $ponente->APELLIDO1 !!} {!! $ponente->APELLIDO2 !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::input("date", "INICIO:".$ponente->PONENTE, $ponente->INICIO, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-1">
                                            {!! Form::input("date", "FIN:".$ponente->PONENTE, $ponente->FIN, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell col-md-5">
                                            {!! Form::text("PONENCIA:".$ponente->PONENTE, $ponente->PONENCIA, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td>
                                            <a id="btn-delete-ponente" href="{!! URL::to("cursos/".$ponente->CURSO."/ponentes/delete/".$ponente->PONENTE) !!}" class="btn btn-danger center-block"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        Aún no hay ningún ponente para este curso.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>

                    <?php /*
                    {{--@if($ponentes)
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-6">
                            <div class="pull-right">
                                {!! $ponentes->links() !!}
                            </div>
                        </div>
                    </div>
                    @endif--}}
                    */ ?>

                </div>

            </div>
        </div>
        <!-- Fin Box -->
    </div>
    <!-- Fin Sección de formulario -->

@stop

@section('scripts')

<script>

    // Añadir ponente
    $('#buscar-dni-ponente-btn').click(function(e){
        e.preventDefault();

        $('#addPonenteForm').submit();

    });

    $(".editable-cell > input[type=text], .editable-cell > input[type=date]").blur(function(){

        var tmp = $(this).attr("name").split(":");
        parametros = {
            fieldName: tmp[0],
            value: $(this).val()
        };

        console.log($(this).val());

        var url = "ponentes/edit/"+tmp[1];
        updateData(url, parametros);

        //console.log(fieldName + " " + idCurso + " " + sesionNum + " " + idProfesor);

    });

</script>

@stop