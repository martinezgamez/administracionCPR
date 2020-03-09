@extends('cursos.cursos_form_layout')

@section('form-tab')

    <!-- Sección de formulario -->
    <div class="form-tab active">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">

                <h4 class="heading">Solicitudes</h4>

                <div class="row">

                    <div class="col-md-6">
                        <a href="{!! URL::to('informes/curso/'.$idCurso.'/listados-solicitudes') !!}" class="btn btn-success"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Listados</a>
                        <a href="{!! URL::to('informes/curso/'.$idCurso.'/certificacion') !!}" class="btn btn-success"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Propuesta de certificación</a>
                        <a href="{!! URL::to('informes/curso/'.$idCurso.'/excel-admitidos') !!}" class="btn btn-success"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Excel admitidos</a>
                        <a href="{!! URL::to('cursos/'.$idCurso.'/email') !!}" class="btn btn-success"><span class="glyphicon glyphicon-send" aria-hidden="true"></span> Enviar e-mail</a>
                    </div>

                    <div class="col-md-6">
                        <div class="pull-right">
                            <div class="form-inline">
                                {!! Form::open(array('id' => 'addProfesorForm', 'form' => 'materialForm', 'action' => ['CursosController@addSolicitud', $idCurso, 'dniProfesor'], 'method' => 'post')) !!}
                                    {!! Form::hidden('idCurso', $idCurso) !!}
                                    {!! Form::text('dniProfesor', '', array('class' => 'form-control', 'placeholder' => 'DNI')) !!}
                                    <a href="#" id="buscar-dni-profesor-btn" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></a>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>

                </div>

                <hr />

                <div id="solicitudes-list" class="row">

                    <table id="table-solicitudes" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Fecha de solicitud</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(isset($solicitudes) && count($solicitudes) > 0)
                                @foreach($solicitudes as $solicitud)
                                    <tr>
                                        <td>
                                            {!! $solicitud->CODIGO !!}
                                        </td>
                                        <td>
                                            {!! $solicitud->DNI !!}
                                        </td>
                                        <td>
                                            {!! $solicitud->NOMBRE !!} {!! $solicitud->APELLIDO1 !!} {!! $solicitud->APELLIDO2 !!}
                                        </td>
                                        <td>
                                            {!! date("d/m/Y", strtotime($solicitud->FECHA)) !!}
                                        </td>
                                        <td class="text-center">
                                            <a id="{!! $solicitud->CODIGO !!}" class="admitir-btn" href="{!! action('CursosController@admitirSolicitud', [$idCurso, $solicitud->CODIGO]) !!}"><span class="label label-success">Admitir</span></a>
                                        </td>
                                        <td class="text-center">
                                            <a id="{!! $solicitud->CODIGO !!}" class="excluir-btn" href="{!! action('CursosController@excluirSolicitud', [$idCurso, $solicitud->CODIGO]) !!}"><span class="label label-danger">Denegar</span></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">
                                        No hay ninguna solicitud pendiente para este curso.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                    <?php /*
                    @if($solicitudes)
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-6">
                            <div class="pull-right">
                                {!! $solicitudes->links() !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    */?>
                </div>

            </div>
        </div>
        <!-- Fin Box -->

        <!-- Box -->
        <div class="col-md-6">
            <div class="box">

                <h4 class="heading">Admitidos</h4>

                <div id="admitidos-list" class="row">

                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(isset($admitidos) && count($admitidos) > 0)
                                @foreach($admitidos as $admitido)
                                    <tr>
                                        <td>
                                            {!! $admitido->CODIGO !!}
                                        </td>
                                        <td>
                                            {!! $admitido->DNI !!}
                                        </td>
                                        <td>
                                            {!! $admitido->NOMBRE !!} {!! $admitido->APELLIDO1 !!} {!! $admitido->APELLIDO2 !!}
                                        </td>
                                        <td>
                                            <a id="btn-delete-admitido" href="{!! URL::to("cursos/".$idCurso."/solicitudes/admitido/delete/".$admitido->CODIGO) !!}" class="btn btn-xs btn-danger center-block"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        Aún no hay ningún alumno admitido para este curso.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                    <?php /*
                    @if($admitidos)
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-6">
                            <div class="pull-right">
                                {!! $admitidos->links() !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    */?>
                </div>

            </div>
        </div>
        <!-- Fin Box -->

        <!-- Box -->
        <div class="col-md-6">
            <div class="box">

                <h4 class="heading">Excluidos</h4>

                <div id="excluidos-list" class="row">

                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(isset($excluidos) && count($excluidos) > 0)
                                @foreach($excluidos as $excluido)
                                    <tr>
                                        <td>
                                            {!! $excluido->CODIGO !!}
                                        </td>
                                        <td>
                                            {!! $excluido->DNI !!}
                                        </td>
                                        <td>
                                            {!! $excluido->NOMBRE !!} {!! $excluido->APELLIDO1 !!} {!! $excluido->APELLIDO2 !!}
                                        </td>
                                        <td>
                                            <a id="btn-delete-excluido" href="{!! URL::to("cursos/".$idCurso."/solicitudes/excluido/delete/".$excluido->CODIGO) !!}" class="btn btn-xs btn-danger center-block"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        Aún no hay ningún alumno excluido para este curso.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                    <?php /*
                    @if($excluidos)
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-6">
                            <div class="pull-right">
                                {!! $excluidos->links() !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    */?>
                </div>

            </div>
        </div>
        <!-- Fin Box -->

    </div>
    <!-- Fin Sección de formulario -->

@stop

@section('scripts')

<script>

    // Añadir solicitud
    $('#buscar-dni-profesor-btn').click(function(e){

        e.preventDefault();

        $('#addProfesorForm').submit();

    });

</script>

@stop