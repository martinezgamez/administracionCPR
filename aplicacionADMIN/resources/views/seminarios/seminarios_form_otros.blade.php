@extends('seminarios.seminarios_form_layout')

@section('top-bar')
    {!! Form::label('submitForm', 'Guardar', array('class' => 'btn btn-sm btn-default pull-right')) !!}
@stop

@section('form-tab')

    {!! Form::open(array('form' => 'seminarioOtrosForm', 'action' => ['SeminariosController@guardarOtros', $idSeminario], 'method' => 'post')) !!}
    @if(count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {!! $error !!} </br>
            @endforeach
        </div>
    @endif

    @if(isset($idSeminario))
        {!! Form::hidden('CODIGO', $idSeminario) !!}
    @endif

    <!-- Sección de formulario -->
    <div class="form-tab">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">
                <h4 class="heading">Otros datos</h4>
                <div class="row">
                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Contextualización y justificación</h4>
                            <div class="row">
                                <!-- Fila -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @if ($checkboxes->d1 == 1)
                                            <input type="checkbox" name="d1" id="d1"  value="{!! $checkboxes->d1 !!}" onclick="d1Pulsado()" checked> El centro NO cuenta con Proyecto de Formación
                                        @else
                                            <input type="checkbox" name="d1" id="d1"  value="{!! $checkboxes->d1 !!}" onclick="d1Pulsado()"> El centro NO cuenta con Proyecto de Formación
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        @if ($checkboxes->d2 == 1)
                                            <input type="checkbox" name="d2" id="d2"  value="{!! $checkboxes->d2 !!}" onclick="d2Pulsado()" checked> El centro SÍ cuenta con Proyecto de Formación
                                        @else
                                            <input type="checkbox" name="d2" id="d2"  value="{!! $checkboxes->d2 !!}" onclick="d2Pulsado()"> El centro SÍ cuenta con Proyecto de Formación
                                        @endif
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        @if ($checkboxes->d3 == 1)
                                            <input type="checkbox" name="d3" id="d3"  value="{!! $checkboxes->d3 !!}" onclick="d3Pulsado()" checked> Los participantes pertenecen al mismo centro
                                        @else
                                            <input type="checkbox" name="d3" id="d3"  value="{!! $checkboxes->d3 !!}" onclick="d3Pulsado()"> Los participantes pertenecen al mismo centro
                                        @endif
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @if ($checkboxes->d4 == 1)
                                            <input type="checkbox" name="d4" id="d4"  value="{!! $checkboxes->d4 !!}" onclick="d4Pulsado()" checked> Los participantes son de distinto centro/área o materia común
                                        @else
                                            <input type="checkbox" name="d4" id="d4"  value="{!! $checkboxes->d4 !!}" onclick="d4Pulsado()"> Los participantes son de distinto centro/área o materia común
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        @if ($checkboxes->d5 == 1)
                                            <input type="checkbox" name="d5" id="d5"  value="{!! $checkboxes->d5 !!}" onclick="d5Pulsado()" checked> Los participantes son de distinto centro/NO de área o materia común
                                        @else
                                            <input type="checkbox" name="d5" id="d5"  value="{!! $checkboxes->d5 !!}" onclick="d5Pulsado()"> Los participantes son de distinto centro/NO de área o materia común
                                        @endif
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('D4', 'Explicación de cómo se llevará a cabo la aplicabilidad cotidiana en el aula:') !!}
                                        {!! Form::textarea('D4', Helper::issetor($obs->D4), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('AREA', 'Áreas y/o materias:') !!}
                                        {!! Form::textarea('AREA', Helper::issetor($obs->AREA), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Interés de la temática para el centro o centros implicados en función de:</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('INTACADEMICO', 'Mejoras de los resultados académicos:') !!}
                                        {!! Form::textarea('INTACADEMICO', Helper::issetor($obs->INTACADEMICO), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('INTPROYECTOS', 'Proyectos específicos que se desarrollen en los centros educativos de la ciudad:') !!}
                                        {!! Form::textarea('INTPROYECTOS', Helper::issetor($obs->INTPROYECTOS), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('INTACTIVOS', 'El uso de metodologías activas:') !!}
                                        {!! Form::textarea('INTACTIVOS', Helper::issetor($obs->INTACTIVOS), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->
                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Datos adicionales</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-12">
                                    {!! Form::label('JUSTIFICACION', 'Objetivos:') !!}
                                    <div id="objetivos-list" class="row">

                                <table class="table table-bordered table-hover table-condensed">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Objetivo</th>
                                            <th>Evaluación</th>
                                            <th>Técnicas</th>
                                            <th>Momentos</th>
                                            <th>Personas</th>
                                            <th>
                                                <a id="btn-add-objetivo" href="{!! URL::to("seminarios/".$idSeminario."/objetivos/add") !!}" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if(isset($objetivos) && count($objetivos) > 0)
                                            @foreach($objetivos as $objetivo)
                                                <tr>
                                                    <td>
                                                        {!! $objetivo->numero !!}
                                                    </td>    
                                                    <td class="col-md-3">
                                                        <div contenteditable onfocusout="editar(event)" id="objetivo:{!! $objetivo->numero !!}">
                                                            {!! $objetivo->objetivo !!}
                                                        </div>
                                                    </td>
                                                    <td class="col-md-3">
                                                        <div contenteditable onfocusout="editar(event)" id="evaluacion:{!! $objetivo->numero !!}">
                                                            {!! $objetivo->evaluacion !!}
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div contenteditable onfocusout="editar(event)" id="tecnicas:{!! $objetivo->numero !!}">
                                                            {!! $objetivo->tecnicas !!}
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div contenteditable onfocusout="editar(event)" id="momentos:{!! $objetivo->numero !!}">
                                                            {!! $objetivo->momentos !!}
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div contenteditable onfocusout="editar(event)" id="personas:{!! $objetivo->numero !!}">
                                                            {!! $objetivo->personas !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a id="btn-delete-objetivo" href="{!! URL::to("seminarios/".$idSeminario."/objetivos/".$objetivo->numero."/delete") !!}" class="btn btn-danger center-block" onclick="return confirm('¿Borrar objetivo?')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">
                                                    No hay ningún objetivo para este seminario.
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('CONTENIDOS', 'Contenidos que se quiere trabajar:') !!}
                                        {!! Form::textarea('CONTENIDOS', Helper::issetor($obs->CONTENIDOS), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('METODOLOGIA', 'Metodología o sistema de trabajo:') !!}
                                        {!! Form::textarea('METODOLOGIA', Helper::issetor($obs->METODOLOGIA), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('SEXISTA', 'Lenguaje no sexista, prácticas correctoras de estereotipos sexistas:') !!}
                                        {!! Form::textarea('SEXISTA', Helper::issetor($obs->SEXISTA), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('TIC', 'Las TICs como herramientas de metodológica:') !!}
                                        {!! Form::textarea('TIC', Helper::issetor($obs->TIC), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('ASESORAMIENTO', 'Asesoramiento que se solicita:') !!}
                                        {!! Form::textarea('ASESORAMIENTO', Helper::issetor($obs->ASESORAMIENTO), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin Box -->
                    <!-- Box -->
                    <div class="col-md-12">
                        <div class="box">
                            <h4 class="heading">Materiales</h4>
                            <div class="row">

                                <!-- Fila -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('MATERIALES', 'Materiales a elaborar/recopilar:') !!}
                                        {!! Form::textarea('MATERIALES', Helper::issetor($obs->MATERIALES), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('REVISAR', 'Materiales a revisar/analizar:') !!}
                                        {!! Form::textarea('REVISAR', Helper::issetor($obs->REVISAR), array('class' => 'form-control', 'rows' => '6', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('REPROGRAFIA', 'Reprografía:') !!}
                                        {!! Form::textarea('REPROGRAFIA', Helper::issetor($rec->REPROGRAFIA), array('class' => 'form-control', 'rows' => '4', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('OTROS', 'Otros:') !!}
                                        {!! Form::textarea('OTROS', Helper::issetor($rec->OTROS), array('class' => 'form-control', 'rows' => '4', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('BIBLIOGRAFIA', 'Bibliografía:') !!}
                                        {!! Form::textarea('BIBLIOGRAFIA', Helper::issetor($rec->BIBLIOGRAFIA), array('class' => 'form-control', 'rows' => '4', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <!-- Fila -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('DREPROGRAFIA', 'Importe:') !!}
                                        {!! Form::textarea('DREPROGRAFIA', Helper::issetor($rec->DREPROGRAFIA), array('class' => 'form-control', 'rows' => '1', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('DOTROS', 'Importe:') !!}
                                        {!! Form::textarea('DOTROS', Helper::issetor($rec->DOTROS), array('class' => 'form-control', 'rows' => '1', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('DBIBLIOGRAFIA', 'Importe:') !!}
                                        {!! Form::textarea('DBIBLIOGRAFIA', Helper::issetor($rec->DBIBLIOGRAFIA), array('class' => 'form-control', 'rows' => '1', 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('TOTAL', 'Importe total:') !!}
                                        {!! Form::textarea('TOTAL', Helper::issetor($rec->TOTAL), array('class' => 'form-control', 'rows' => '1', 'readonly' => true, 'disabled' => true, 'style' => 'resize: none;')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Sección de formulario -->
    {!! Form::submit('Guardar', array('id' => 'submitForm')) !!}
    {!! Form::close() !!}

@stop

@section('scripts')

<script>

    function editar(e)
    {
        var idSeminario = {!! $idSeminario !!};
        var columna = e.target.id.split(":")[0];
        var idObjetivo = e.target.id.split(":")[1];
        var dato = document.getElementById(e.target.id).innerText;

        if(dato.length > 255)
        {
            alert('La cantidad de carateres introducidos excede los 255. No se guardará el resto.');
            dato = dato.substring(0, 255);
        }

        var parametros = {
            "idSeminario" : idSeminario,
            "idObjetivo" : idObjetivo,
            "columna" : columna,
            "dato" : dato
        };
        var url = "/seminarios/" + idSeminario + "/objetivos/" + idObjetivo + "/" + columna + "/" + dato;
        updateData(url, parametros);
    }
    
    function d1Pulsado() {
        var checkBox = document.getElementById("d1");
        checkBox.value = checkBox.checked ? 1 : 0;
    }

    function d2Pulsado() {
        var checkBox = document.getElementById("d2");
        checkBox.value = checkBox.checked ? 1 : 0;
    }

    function d3Pulsado() {
        var checkBox = document.getElementById("d3");
        checkBox.value = checkBox.checked ? 1 : 0;
    }

    function d4Pulsado() {
        var checkBox = document.getElementById("d4");
        checkBox.value = checkBox.checked ? 1 : 0;
    }

    function d5Pulsado() {
        var checkBox = document.getElementById("d5");
        checkBox.value = checkBox.checked ? 1 : 0;
    }
</script>

@stop