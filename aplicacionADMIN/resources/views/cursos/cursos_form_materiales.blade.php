@extends('cursos.cursos_form_layout')

@section('top-bar')

@stop

@section('form-tab')

    <!-- Sección de formulario -->
    <div class="form-tab active">
        <div class="row">
            <!-- Box -->
            <div class="col-md-6">
                <div class="box">

                    <h4 class="heading">Materiales</h4>

                    <div id="materiales-list">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>CONCEPTO</th>
                                    <th>CANTIDAD</th>
                                    <th>PRECIO</th>
                                    <th>TOTAL</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(isset($materiales) && count($materiales) > 0)
                                    @foreach($materiales as $material)
                                        <tr>
                                            <td>
                                                {!! $material->CONCEPTO !!}
                                            </td>
                                            <td>
                                                {!! $material->CANTIDAD !!}
                                            </td>
                                            <td>
                                                {!! $material->PRECIO !!}
                                            </td>
                                            <td>
                                                {!! $material->CANTIDAD * $material->PRECIO !!}
                                            </td>
                                            <td class="col-xs-1">
                                                <a id="btn-delete-material" href="{!! URL::to("cursos/".$idCurso."/materiales/delete/".$material->NUMERO) !!}" class="btn btn-xs btn-danger center-block"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">
                                            Aún no hay ningún material en este curso.
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>

                        @if($materiales)
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-6">
                                <div class="pull-right">
                                    {!! $materiales->links() !!}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            <!-- Fin Box -->
            <!-- Box -->
            <div class="col-md-6">
                <div class="box">

                    <h4 class="heading">Otros gastos</h4>

                    <div id="gastos-list">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>CONCEPTO</th>
                                    <th>PRECIO</th>
                                    <th class="col-xs-1"></th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(isset($otros) && count($otros) > 0)
                                    @foreach($otros as $gasto)
                                        <tr>
                                            <td>
                                                {!! $gasto->CONCEPTO !!}
                                            </td>
                                            <td>
                                                {!! $gasto->PRECIO !!}
                                            </td>
                                            <td>
                                                <a id="btn-delete-gasto" href="{!! URL::to("cursos/".$idCurso."/gastos/delete/".$gasto->NUMERO) !!}" class="btn btn-xs btn-danger center-block"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">
                                            Aún no hay ningún gasto adicional en este curso.
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>

                        @if($otros)
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-6">
                                <div class="pull-right">
                                    {!! $otros->links() !!}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            <!-- Fin Box -->
        </div>
        <div class="row">
            <!-- Box -->
            <div class="col-md-6">
                <div class="box">

                    <h4 class="heading">Añadir Materiales</h4>

                    {!! Form::open(array('id' => 'materialForm', 'form' => 'materialForm', 'action' => ['CursosController@addMaterial', $idCurso], 'method' => 'post')) !!}
                    {!! Form::hidden('CURSO', $idCurso) !!}
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('CANTIDAD', 'Cantidad:') !!}
                            {!! Form::text('CANTIDAD', '', array('id' => 'CANTIDAD', 'class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('PRECIO', 'Precio:') !!}
                            {!! Form::text('PRECIO', '', array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('CONCEPTO', 'Concepto') !!}
                            {!! Form::textarea('CONCEPTO', '', array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::submit('Guardar', array('class' => 'btn btn-primary btn-block')) !!}
                    </div>

                    {!! Form::close() !!}

                    <div class="clearfix"></div>

                </div>
            </div>
            <!-- Fin Box -->
            <!-- Box -->
            <div class="col-md-6">
                <div class="box">

                    <h4 class="heading">Añadir otros gastos</h4>

                    {!! Form::open(array('id' => 'gastoForm', 'form' => 'gastoForm', 'action' => ['CursosController@addGasto', $idCurso], 'method' => 'post')) !!}
                    {!! Form::hidden('CURSO', $idCurso) !!}
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('PRECIO', 'Precio:') !!}
                            {!! Form::text('PRECIO', '', array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('CONCEPTO', 'Concepto') !!}
                            {!! Form::textarea('CONCEPTO', '', array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::submit('Guardar', array('class' => 'btn btn-primary btn-block')) !!}
                    </div>

                    {!! Form::close() !!}

                    <div class="clearfix"></div>

                </div>
            </div>
            <!-- Fin Box -->
        </div>
    </div>
    <!-- Fin Sección de formulario -->

@stop

@section('scripts')

<script>



</script>

@stop