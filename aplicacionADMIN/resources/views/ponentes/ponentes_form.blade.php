@extends('layout')

@section('title') Ponentes @stop

@section('section-title') Ponentes @stop

@section('topbar')

    <a href="{!! URL::to('/ponentes', array(), false); !!}" class="btn btn-sm btn-primary pull-left">Listado de ponentes</a>
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}

@stop

@section('content')

    <h1>Nuevo ponente</h1>

    <hr />

    {!! Form::open(array('form' => 'ponentesForm', 'action' => 'PonentesController@save', 'method' => 'post')) !!}

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {!! $error !!} </br>
                @endforeach
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="box">

                    <h4 class="heading">Datos básicos</h4>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('DNI', 'DNI') !!}
                                {!! Form::text('DNI', Input::old('DNI'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('NOMBRE', 'Nombre') !!}
                                {!! Form::text('NOMBRE', Input::old('NOMBRE'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('APELLIDO1', 'Primer Apellido') !!}
                                {!! Form::text('APELLIDO1', Input::old('APELLIDO1'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('APELLIDO2', 'Segundo Apellido') !!}
                                {!! Form::text('APELLIDO2', Input::old('APELLIDO2'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('DOMICILIO', 'Domicilio') !!}
                                {!! Form::text('DOMICILIO', Input::old('DOMICILIO'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('LOCALIDAD', 'Localidad') !!}
                                {!! Form::text('LOCALIDAD', Input::old('LOCALIDAD'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('PROVINCIA', 'Provincia') !!}
                                {!! Form::text('PROVINCIA', Input::old('PROVINCIA'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('CP', 'Código Postal') !!}
                                {!! Form::text('CP', Input::old('CP'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('MAIL', 'Email') !!}
                                {!! Form::text('MAIL', Input::old('MAIL'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('PUESTO', 'Puesto') !!}
                                {!! Form::text('PUESTO', Input::old('PUESTO'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('TELEFONO', 'Teléfono') !!}
                                {!! Form::text('TELEFONO', Input::old('TELEFONO'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('MOVIL', 'Móvil') !!}
                                {!! Form::text('MOVIL', Input::old('MOVIL'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box">

                    <h4 class="heading">Datos cuenta</h4>

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('ENTIDAD', 'IBAN y entidad') !!}
                                {!! Form::text('ENTIDAD', Input::old('ENTIDAD'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('SUCURSAL', 'Sucursal') !!}
                                {!! Form::text('SUCURSAL', Input::old('SUCURSAL'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::label('CONTROL', 'Control') !!}
                                {!! Form::text('CONTROL', Input::old('CONTROL'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('CUENTA', 'Cuenta') !!}
                                {!! Form::text('CUENTA', Input::old('CUENTA'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('BANCO', 'Banco') !!}
                                {!! Form::text('BANCO', Input::old('BANCO'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('OFICINA', 'Oficina') !!}
                                {!! Form::text('OFICINA', Input::old('OFICINA'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="box">

                    <h4 class="heading">Otros datos</h4>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('COCHE', 'Coche') !!}
                                {!! Form::text('COCHE', Input::old('COCHE'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('BAJA', Input::old('BAJA'), false) !!}
                                        ¿Dar de baja?
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('PUBLICO', Input::old('PUBLICO'), false) !!}
                                        ¿Es público?
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box">

                    <h4 class="heading">Datos T</h4>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('DIRECCIONT', 'Dirección T') !!}
                                {!! Form::text('DIRECCIONT', Input::old('DIRECCIONT'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('LOCALIDADT', 'Localidad T') !!}
                                {!! Form::text('LOCALIDADT', Input::old('LOCALIDADT'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('PROVINCIAT', 'Provincia T') !!}
                                {!! Form::text('PROVINCIAT', Input::old('PROVINCIAT'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('CPT', 'Código Postal T') !!}
                                {!! Form::text('CPT', Input::old('CPT'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('TELEFONOT', 'Teléfono T') !!}
                                {!! Form::text('TELEFONOT', Input::old('TELEFONOT'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box">

                    <h4 class="heading">Datos del centro</h4>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('CUERPO', 'Cuerpo') !!}
                                {!! Form::text('CUERPO', Input::old('CUERPO'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('CENTRO', 'Centro') !!}
                                {!! Form::text('CENTRO', Input::old('CENTRO'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('DEPARTAMENTO', 'Departamento') !!}
                                {!! Form::text('DEPARTAMENTO', Input::old('DEPARTAMENTO'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('TITULACIONES', 'Titulaciones') !!}
                                {!! Form::text('TITULACIONES', Input::old('TITULACIONES'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('PUBLICACIONES', 'Publicaciones') !!}
                                {!! Form::text('PUBLICACIONES', Input::old('PUBLICACIONES'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('CURSOS', 'Cursos') !!}
                                {!! Form::text('CURSOS', Input::old('CURSOS'), array('class' => 'form-control')) !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {!! Form::submit('Guardar', array('id' => 'submitForm', 'class' => 'hidden')) !!}

    {!! Form::close() !!}

@stop