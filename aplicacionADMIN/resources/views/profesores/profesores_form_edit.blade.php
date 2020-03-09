@extends('layout')

@section('title') Profesores @stop

@section('section-title') Profesores @stop

@section('topbar')

    <a href="{!! URL::to('/profesores', array(), false); !!}" class="btn btn-sm btn-primary pull-left">Listado de profesores</a>
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}

@stop

@section('content')

    <h1>{!! $profesor->NOMBRE !!} {!! $profesor->APELLIDO1 !!}</h1>

    <hr />

    {!! Form::open(array('form' => 'profesorForm', 'action' => ['ProfesoresController@edit', $profesor->CODIGO], 'method' => 'post')) !!}

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {!! $error !!} </br>
                @endforeach
            </div>
        @endif

        {!! Form::hidden('CODIGO', $profesor->CODIGO) !!}

        <div class="col-md-6">
            <div class="box">

                <h4 class="heading">Datos básicos</h4>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('DNI', 'DNI') !!}
                            {!! Form::text('DNI', $profesor->DNI, array('class' => 'form-control', 'placeholder' => '12345678-A')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('NOMBRE', 'Nombre') !!}
                            {!! Form::text('NOMBRE', $profesor->NOMBRE, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('APELLIDO1', 'Primer Apellido') !!}
                            {!! Form::text('APELLIDO1', $profesor->APELLIDO1, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('APELLIDO2', 'Segundo Apellido') !!}
                            {!! Form::text('APELLIDO2', $profesor->APELLIDO2, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('EXPERIENCIA', 'Año de ingreso') !!}
                            {!! Form::text('EXPERIENCIA', $profesor->EXPERIENCIA, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('ESPECIALIDAD', 'Especialidad') !!}
                            {!! Form::text('ESPECIALIDAD', $profesor->ESPECIALIDAD, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('CENTRO', 'Centro de destino') !!}
                            {!! Form::select('CENTRO', $centros, $profesor->CENTRO, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('CUERPO', 'Cuerpo docente') !!}
                            {!! Form::select('CUERPO', $cuerpos, $profesor->CUERPO, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('ADMINISTRACION', 'Situación administrativa') !!}
                            {!! Form::select('ADMINISTRACION', $administraciones, $profesor->ADMINISTRACION, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('DOMICILIO', 'Domicilio') !!}
                            {!! Form::text('DOMICILIO', $profesor->DOMICILIO, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('LOCALIDAD', 'Localidad') !!}
                            {!! Form::text('LOCALIDAD', $profesor->LOCALIDAD, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('PROVINCIA', 'Provincia') !!}
                            {!! Form::text('PROVINCIA', $profesor->PROVINCIA, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('CP', 'Código Postal') !!}
                            {!! Form::text('CP', $profesor->CP, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('MAIL', 'Email') !!}
                            {!! Form::text('MAIL', $profesor->MAIL, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('TELEFONO', 'Teléfono') !!}
                            {!! Form::text('TELEFONO', $profesor->TELEFONO, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('MOVIL', 'Móvil') !!}
                            {!! Form::text('MOVIL', $profesor->MOVIL, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box">

                <h4 class="heading">¿Está verificado?¿Está activo?</h4>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('VERIFICADO', '1', $profesor->VERIFICADO) !!}
                                    Verificado
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('BAJA', '1', !$profesor->BAJA) !!}
                                    Activo
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {!! Form::submit('Guardar', array('id' => 'submitForm', 'class' => 'hidden')) !!}

    {!! Form::close() !!}

@stop