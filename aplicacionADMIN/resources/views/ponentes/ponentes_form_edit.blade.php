@extends('layout')

@section('title') Ponentes @stop

@section('section-title') Ponentes @stop

@section('topbar')

    <a href="{!! URL::to('/ponentes', array(), false); !!}" class="btn btn-sm btn-primary pull-left">Listado de ponentes</a>
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}

@stop

@section('content')

    <h1>{!! $ponente->NOMBRE !!} {!! $ponente->APELLIDO1 !!}</h1>

    <hr />

    {!! Form::open(array('form' => 'ponentesForm', 'action' => ['PonentesController@edit', $ponente->CODIGO], 'method' => 'post')) !!}

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {!! $error !!} </br>
                @endforeach
            </div>
        @endif

        {!! Form::hidden('CODIGO', $ponente->CODIGO) !!}

        <div class="row">
            <div class="col-md-6">
                <div class="box">

                    <h4 class="heading">Datos básicos</h4>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('DNI', 'DNI') !!}
                                {!! Form::text('DNI', $ponente->DNI, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('NOMBRE', 'Nombre') !!}
                                {!! Form::text('NOMBRE', $ponente->NOMBRE, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('APELLIDO1', 'Primer Apellido') !!}
                                {!! Form::text('APELLIDO1', $ponente->APELLIDO1, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('APELLIDO2', 'Segundo Apellido') !!}
                                {!! Form::text('APELLIDO2', $ponente->APELLIDO2, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('DOMICILIO', 'Domicilio') !!}
                                {!! Form::text('DOMICILIO', $ponente->DOMICILIO, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('LOCALIDAD', 'Localidad') !!}
                                {!! Form::text('LOCALIDAD', $ponente->LOCALIDAD, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('PROVINCIA', 'Provincia') !!}
                                {!! Form::text('PROVINCIA', $ponente->PROVINCIA, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('CP', 'Código Postal') !!}
                                {!! Form::text('CP', $ponente->CP, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('MAIL', 'Email') !!}
                                {!! Form::text('MAIL', $ponente->MAIL, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('PUESTO', 'Puesto') !!}
                                {!! Form::text('PUESTO', $ponente->PUESTO, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('TELEFONO', 'Teléfono') !!}
                                {!! Form::text('TELEFONO', $ponente->TELEFONO, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('MOVIL', 'Móvil') !!}
                                {!! Form::text('MOVIL', $ponente->MOVIL, array('class' => 'form-control')) !!}
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
                                {!! Form::text('ENTIDAD', $ponente->ENTIDAD, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('SUCURSAL', 'Sucursal') !!}
                                {!! Form::text('SUCURSAL', $ponente->SUCURSAL, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::label('CONTROL', 'Control') !!}
                                {!! Form::text('CONTROL', $ponente->CONTROL, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('CUENTA', 'Cuenta') !!}
                                {!! Form::text('CUENTA', $ponente->CUENTA, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('BANCO', 'Banco') !!}
                                {!! Form::text('BANCO', $ponente->BANCO, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('OFICINA', 'Oficina') !!}
                                {!! Form::text('OFICINA', $ponente->OFICINA, array('class' => 'form-control')) !!}
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
                                {!! Form::text('COCHE', $ponente->COCHE, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('BAJA', '1', $ponente->BAJA) !!}
                                        ¿Dar de baja?
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('PUBLICO', '1', $ponente->PUBLICO) !!}
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
                                {!! Form::text('DIRECCIONT', $ponente->DIRECCIONT, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('LOCALIDADT', 'Localidad T') !!}
                                {!! Form::text('LOCALIDADT', $ponente->LOCALIDADT, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('PROVINCIAT', 'Provincia T') !!}
                                {!! Form::text('PROVINCIAT', $ponente->PROVINCIAT, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('CPT', 'Código Postal T') !!}
                                {!! Form::text('CPT', $ponente->CPT, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('TELEFONOT', 'Teléfono T') !!}
                                {!! Form::text('TELEFONOT', $ponente->TELEFONOT, array('class' => 'form-control')) !!}
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
                                {!! Form::text('CUERPO', $ponente->CUERPO, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('CENTRO', 'Centro') !!}
                                {!! Form::text('CENTRO', $ponente->CENTRO, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('DEPARTAMENTO', 'Departamento') !!}
                                {!! Form::text('DEPARTAMENTO', $ponente->DEPARTAMENTO, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('TITULACIONES', 'Titulaciones') !!}
                                {!! Form::text('TITULACIONES', $ponente->TITULACIONES, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('PUBLICACIONES', 'Publicaciones') !!}
                                {!! Form::text('PUBLICACIONES', $ponente->PUBLICACIONES, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('CURSOS', 'Cursos') !!}
                                {!! Form::text('CURSOS', $ponente->CURSOS, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {!! Form::submit('Guardar', array('id' => 'submitForm', 'class' => 'hidden')) !!}

    {!! Form::close() !!}

@stop