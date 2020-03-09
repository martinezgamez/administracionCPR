<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title')</title>

<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-theme.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/form-multi-tab.css') }}">
<script src="{{ asset('js/app.js') }}"></script>

<link rel="stylesheet" href="{{ asset('js/jquery-ui-1.12.1/jquery-ui.min.css') }}">
<script src="{{ asset('js/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>

@yield('style')

</head>
<body>

<div id="sidebar">

    <h2>{!! link_to('/', 'Menú Principal') !!}</h2>

    <ul id="main-menu" class="open-active">
        <li class="">
            <a href="{!! action('ProfesoresController@index') !!}">Profesores</a>
        </li>
        <li>
            <a href="{!! action('PonentesController@index') !!}" >Ponentes</a>
        </li>
        <li>
            <a href="{!! action('CursosController@index') !!}" >Cursos</a>
        </li>
        <li>
            <a href="{!! action('SeminariosController@index') !!}" >Seminarios/grupos de trabajo</a>
        </li>
        <li>
            <a href="{!! action('ExtractosController@index') !!}" >Extractos ({!! Helper::num_extractos() !!})</a>
        </li>
        <li>
            <a href="{!! action('NotificacionesController@index') !!}" >Notificaciones ({!! Helper::num_notificaciones() !!})</a>
        </li>
    </ul>

    <hr>

    <h3>Cuenta</h3>
    <ul class="link-group">
        <!--<li>
            <a href="#" ><i class="fa fa-users"></i>Administradores</a>
        </li>-->
        <li><a href="{!! action('HomeController@getLogout') !!}"><i class="fa fa-sign-out"></i>Salir</a></li>
    </ul>

</div>

<div id="section-title">
    <h1>@yield('section-title')</h1>
</div>

<div id="topbar">
    @yield('topbar')
</div>

<div id="main">

    <div id="content">
        @yield('content')
        <div class="clearfix"></div>
    </div>
</div>

<footer>

</footer>

@yield('scripts')

</body>
</html>