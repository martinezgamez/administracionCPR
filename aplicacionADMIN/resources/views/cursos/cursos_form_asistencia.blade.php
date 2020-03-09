@extends('cursos.cursos_form_layout')

@section('top-bar')

@stop

@section('form-tab')

    <!-- Sección de formulario -->
    <div class="form-tab active">
        <!-- Box -->
        <div class="col-md-4">
            <div class="box">

                <h4 class="heading">Sesiones</h4>

                <div id="sesiones-list" class="row">

                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="col-xs-1">Nº</th>
                                <th class="col-xs-8">FECHA</th>
                                <th class="col-xs-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($sesiones) && count($sesiones) > 0)
                                @foreach($sesiones as $sesion)
                                    <?php /*
                                    La sesión cuyo numero es 0 son las horas no presenciales,
                                    otra genialidad en el diseño de esta Base de Datos
                                    con la que he tenido el privilegio de trabajar ¬¬ ...
                                    */?>

                                    <tr>
                                        <td>
                                            <a href="{!! action("CursosController@cursoAsistencia", [$idCurso, $sesion->SESION]) !!}">{!! $sesion->SESION !!}</a>
                                        </td>
                                        <td>
                                            <a href="{!! action("CursosController@cursoAsistencia", [$idCurso, $sesion->SESION]) !!}">{!! date("d/m/Y", strtotime($sesion->FECHA)) !!}</a>
                                        </td>
                                        <td>
                                            <div class="btn-group pull-right" role="group">
                                                <a id="btn-print-sesion" href="{!! URL::to("informes/curso/".$idCurso."/sesion/".$sesion->SESION) !!}" class="btn btn-sm btn-success center-block"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>
                                                <a id="btn-delete-sesion" href="{!! URL::to("cursos/".$idCurso."/asistencia/delete/".$sesion->SESION) !!}" class="btn btn-sm btn-danger center-block"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">
                                        Aún no hay ninguna sesión para este curso.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>

                </div>

                <hr />
                @if($haveNoPresenciales)
                    <a id="no-presenciales-btn" href="{!! action("CursosController@cursoAsistencia", [$idCurso, 0]) !!}" class="btn btn-primary center-block">Horas no presenciales</a>
                @else
                    {!! Form::open(array('form' => 'noPresencialesForm', 'action' => ['CursosController@noPresencialesForm', $idCurso], 'method' => 'post', 'class' => 'form-inline')) !!}
                        {!! Form::hidden('CURSO', $idCurso) !!}
                        <div class="text-center">
                            {!! Form::text("HORAS", '', array('class' => 'form-control')) !!}
                            {!! Form::submit('Añadir horas no presenciales', array('class' => 'btn btn-primary')) !!}
                        </div>
                    {!! Form::close() !!}
                @endif
                <hr />

                <h4 class="heading">Añadir sesión</h4>

                {!! Form::open(array('form' => 'sesionForm', 'action' => ['CursosController@sesionForm', $idCurso], 'method' => 'post')) !!}
                    {!! Form::hidden('CURSO', $idCurso) !!}
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('HORAS', 'Horas:') !!}
                            {!! Form::text('HORAS', '', array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('FECHA', 'Fecha:') !!}
                            {!! Form::text('FECHA', '', array('class' => 'form-control datepicker')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::submit('Añadir', array('class' => 'btn btn-primary btn-block')) !!}
                    </div>
                {!! Form::close() !!}

            </div>
        </div>
        <!-- Fin Box -->

        @if(isset($sesionAlumnos) && count($sesionAlumnos) > 0)
        <!-- Box -->
        <div class="col-md-8">
            <div class="box">

                @if($isNoPresencial)
                    <h4 class="heading">Horas no presenciales</h4>
                @else
                    <h4 class="heading">Sesion del {!! date("d/m/Y", strtotime($sesiones[$sesionNum-1]->FECHA)) !!}</h4>
                @endif

                <div id="sesiones-list" class="row">

                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>NOMBRE</th>
                                <th>HORAS</th>
                                <th>JUSTIFICADAS</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(count($sesionAlumnos) > 0)
                                @foreach($sesionAlumnos as $alumno)
                                    <tr>
                                        <td>
                                            {!! $alumno->DNI !!}
                                        </td>
                                        <td>
                                            {!! $alumno->NOMBRE !!} {!! $alumno->APELLIDO1 !!} {!! $alumno->APELLIDO2 !!}
                                        </td>
                                        <td class="editable-cell">
                                             {!! Form::text("HORAS:".$idCurso.":".$sesionNum.":".$alumno->CODIGO, $alumno->HORAS, array()) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::text("JUSTIFICADAS:".$idCurso.":".$sesionNum.":".$alumno->CODIGO, $alumno->JUSTIFICADAS, array()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>
                                        No hay alumnos admitidos en este curso
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
        <!-- Fin Box -->
        @endif

    </div>
    <!-- Fin Sección de formulario -->

@stop

@section('scripts')

<script>
    $(function(){
        $("#FECHA").datepicker({ dateFormat: 'yy-mm-dd' });
    });

    $(".editable-cell > input[type=text]").blur(function(){

        var tmp = $(this).attr("name").split(":");
        parametros = {
            fieldName: tmp[0],
            //idCurso: tmp[1],
            //sesionNum: tmp[2],
            idProfesor: tmp[3],
            value: $(this).val()
        };
        
        var url = "edit/"+tmp[2];
        updateData(url, parametros);

        //console.log($(this).val());
        //console.log(fieldName + " " + idCurso + " " + sesionNum + " " + idProfesor);

    });

</script>

@stop