@extends('layout')

@section('title') Profesores @stop

@section('section-title') Profesores @stop

@section('topbar')

    <a href="{!! URL::to('/profesores', array(), false); !!}" class="btn btn-sm btn-primary pull-left">Listado de profesores</a>
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}

@stop

@section('content')

    <h1>Nuevo profesor</h1>

    <hr />

    {!! Form::open(array('form' => 'profesorForm', 'action' => 'ProfesoresController@save', 'method' => 'post')) !!}

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {!! $error !!} </br>
                @endforeach
            </div>
        @endif

        <div class="col-md-6">
            <div class="box">

                <h4 class="heading">Datos básicos</h4>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('DNI', 'DNI') !!}
                            {!! Form::text('DNI', Input::old('DNI'), array('class' => 'form-control', 'placeholder' => '12345678-A')) !!}
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

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('EXPERIENCIA', 'Año de ingreso') !!}
                            {!! Form::text('EXPERIENCIA', Input::old('EXPERIENCIA'), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('ESPECIALIDAD', 'Especialidad') !!}
                            {!! Form::text('ESPECIALIDAD', Input::old('ESPECIALIDAD'), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('CENTRO', 'Centro de destino') !!}
                            {!! Form::select('CENTRO', $centros, Input::old('CENTRO'), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('CUERPO', 'Cuerpo docente') !!}
                            {!! Form::select('CUERPO', $cuerpos, Input::old('CUERPO'), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('ADMINISTRACION', 'Situación administrativa') !!}
                            {!! Form::select('ADMINISTRACION', $administraciones, Input::old('ADMINISTRACION'), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('DOMICILIO', 'Domicilio') !!}
                            {!! Form::text('DOMICILIO', Input::old('DOMICILIO'), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('LOCALIDAD', 'Localidad') !!}
                            {!! Form::text('LOCALIDAD', Input::old('LOCALIDAD'), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('PROVINCIA', 'Provincia') !!}
                            {!! Form::text('PROVINCIA', Input::old('PROVINCIA'), array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
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

                <h4 class="heading">¿Está verificado?¿Está activo?</h4>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                              <div class="checkbox">
                                  <label>
                                      {!! Form::checkbox('VERIFICADO', Input::old('VERIFICADO'), false) !!}
                                      Verificado
                                  </label>
                              </div>
                              <div class="checkbox">
                                  <label>
                                      {!! Form::checkbox('BAJA', !Input::old('BAJA'), false) !!}
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