<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>

<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">

</head>
<body>

<div id="login-container">

    <div id="logo">
        {{-- <img src="/static/img/lumpaapp.png" alt="Logo"> --}}
    </div>

    <div id="login">

        <h3>Formación Melilla</h3>

        <h5>Introduzca sus credenciales para iniciar sesión</h5>

        <?php  //Preguntamos si hay algún mensaje de error y si hay lo mostramos?>
        @if(Session::has('mensaje_error'))
            {!! Session::get('mensaje_error') !!}
        @endif

        {!! Form::open(array('url' => '/login')) !!}

            @if($errors)
                <div>{!! $errors->first('email') !!}</div>
            @endif
            <div class="form-group">
                {!! Form::text('email', Input::old('email'), array('class' => 'form-control', 'placeholder' => 'Email')); !!}
            </div>
            <div class="form-group">
                {!! Form::password('password', array('class' => 'form-control', 'placeholder' => 'Contraseña')); !!}
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('rememberme', false) !!}
                        Recordar contraseña
                    </label>
                </div>
            </div>

            <div class="form-group">
                {!! Form::submit('Enviar', array('class' => 'btn btn-primary btn-block')) !!}
            </div>

        {!! Form::close() !!}

        <a href="#" class="btn btn-default">¿Olvidó su contraseña?</a>

    </div>

</div>

</body>
</html>