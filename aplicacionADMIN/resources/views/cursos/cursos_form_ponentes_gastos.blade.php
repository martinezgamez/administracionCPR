@extends('cursos.cursos_form_layout')

@section('top-bar')

@stop

@section('form-tab')

    <!-- Sección de formulario -->
    <div class="form-tab active">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">

                <div class="row">

                    <div class="col-md-6">

                        <ul id="nav-form" class="nav nav-pills">
                            <li><a href="{!! URL::to('/cursos/'.$idCurso.'/ponentes') !!}">Ponencia</a></li>
                            <li><a href="{!! URL::to('/cursos/'.$idCurso.'/ponentes/presupuesto') !!}">Presupuestos</a></li>
                            <li><a href="{!! URL::to('/cursos/'.$idCurso.'/ponentes/gastos') !!}">Gastos</a></li>
                        </ul>

                    </div>

                    <div class="col-md-6">
                        <div class="pull-right">
                            <div class="form-inline">
                                {!! Form::text('buscar_dni', '', array('class' => 'form-control', 'placeholder' => 'DNI', 'id' => 'buscar_dni_ponente')) !!}
                                <a href="#" id="buscar-dni-ponente-btn" class="btn btn-success"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></a>
                            </div>
                        </div>
                    </div>

                </div>

                <hr />

                <h4 class="heading">Ponentes: Gastos</h4>

                <div id="ponentes-list" class="row">

                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Recibos</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(isset($ponentes) && count($ponentes) > 0)
                                @foreach($ponentes as $ponente)

                                    <tr>
                                        <td>
                                            {!! $ponente->CODIGO !!}
                                        </td>
                                        <td>
                                            {!! $ponente->DNI !!}
                                        </td>
                                        <td>
                                            <a href="{!! URL::to('/cursos/'.$idCurso.'/ponentes/gastos/'.$ponente->PONENTE) !!}">{!! $ponente->NOMBRE !!} {!! $ponente->APELLIDO1 !!} {!! $ponente->APELLIDO2 !!}</a>
                                        </td>
                                        <td>
                                            <div class="btn-group pull-right" role="group">
                                                <a class="btn btn-xs btn-success" href="{!! URL::to('informes/curso/'.$idCurso.'/ponente/'.$ponente->PONENTE.'/recibos/locomocion') !!}"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Locomoción</a>
                                                <a class="btn btn-xs btn-success" href="{!! URL::to('informes/curso/'.$idCurso.'/ponente/'.$ponente->PONENTE.'/recibos/dietas') !!}"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Dietas</a>
                                                <a class="btn btn-xs btn-success" href="{!! URL::to('informes/curso/'.$idCurso.'/ponente/'.$ponente->PONENTE.'/recibos/manutencion') !!}"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Manutención</a>
                                                <a class="btn btn-xs btn-success" href="{!! URL::to('informes/curso/'.$idCurso.'/ponente/'.$ponente->PONENTE.'/recibos/docencia') !!}"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Docencia</a>
                                                <a class="btn btn-xs btn-success" href="{!! URL::to('informes/curso/'.$idCurso.'/ponente/'.$ponente->PONENTE.'/irpf') !!}"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> IRPF</a>
                                            </div>
                                        </td>
                                    </tr>

                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        Aún no hay ningún ponente para este curso.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>

                </div>

                @if(isset($dietas))
                    <h4 class="heading">Ponentes: Gastos dietas/alojamientos</h4>

                    <div id="ponentes-gastos-dieta" class="row">

                        <table class="table table-bordered table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th>Fecha salida</th>
                                    <th>Hora salida</th>
                                    <th>Fecha llegada</th>
                                    <th>Hora llegada</th>
                                    <th>Días dietas</th>
                                    <th>Precio dietas</th>
                                    <th>Días alojamiento</th>
                                    <th>Precio alojamiento</th>
                                    <th>Días dietas reducidas</th>
                                    <th>Precio dietas reducidas</th>
                                    <th>Total</th>
                                    <th>
                                        <a id="btn-add-dieta" href="#" class="btn btn-xs btn-success center-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(count($dietas) > 0)
                                    <?php $total = 0 ?>
                                    @foreach($dietas as $dieta)

                                        <tr>
                                            <td class="editable-cell">
                                                {!! Form::input("date", "INICIO:".$dieta->NUMERO, $dieta->INICIO, array( 'class' => 'form-control' )) !!}
                                            </td>
                                            <td class="editable-cell">
                                                {!! Form::input("time", "LLEGADA:".$dieta->NUMERO, $dieta->LLEGADA, array( 'class' => 'form-control' )) !!}
                                            </td>
                                            <td class="editable-cell">
                                                {!! Form::input("date", "FIN:".$dieta->NUMERO, $dieta->FIN, array( 'class' => 'form-control' )) !!}
                                            </td>
                                            <td class="editable-cell">
                                                {!! Form::input("time", "SALIDAD:".$dieta->NUMERO, $dieta->SALIDAD, array( 'class' => 'form-control' )) !!}
                                            </td>
                                            <td class="editable-cell">
                                                {!! Form::text("DIASDIETAS:".$dieta->NUMERO, $dieta->DIASDIETAS, array( 'id' => 'diasDietas', 'class' => 'form-control' )) !!}
                                            </td>
                                            <td class="editable-cell">
                                                {!! Form::text("DIETAS:".$dieta->NUMERO, $dieta->DIETAS, array( 'id' => 'dietas', 'class' => 'form-control' )) !!}
                                            </td>
                                            <td class="editable-cell">
                                                {!! Form::text("DIASALOJAMIENTO:".$dieta->NUMERO, $dieta->DIASALOJAMIENTO, array( 'id' => 'diasAlojamiento', 'class' => 'form-control' )) !!}
                                            </td>
                                            <td class="editable-cell">
                                                {!! Form::text("ALOJAMIENTO:".$dieta->NUMERO, $dieta->ALOJAMIENTO, array( 'id' => 'alojamiento', 'class' => 'form-control' )) !!}
                                            </td>
                                            <td class="editable-cell">
                                                {!! Form::text("DIASEVENTUAL:".$dieta->NUMERO, $dieta->DIASEVENTUAL, array( 'id' => 'diasReducidas', 'class' => 'form-control' )) !!}
                                            </td>
                                            <td class="editable-cell">
                                                {!! Form::text("EVENTUAL:".$dieta->NUMERO, $dieta->EVENTUAL, array( 'id' => 'reducidas', 'class' => 'form-control' )) !!}
                                            </td>
                                            <td>
                                                <?php $totalDieta = ($dieta->DIASDIETAS * $dieta->DIETAS) + ($dieta->DIASALOJAMIENTO * $dieta->ALOJAMIENTO) + ($dieta->DIASEVENTUAL * $dieta->EVENTUAL); ?>
                                                {!! Form::text("TOTALDIETA:".$dieta->NUMERO, $totalDieta, array( 'id' => 'totalDieta'.$dieta->NUMERO, 'class' => 'form-control', 'readonly' => true )) !!}
                                            </td>
                                            <td class="button-cell">
                                                <a id="btn-delete-dieta:{!! $dieta->NUMERO !!}" href="#" class="btn btn-xs btn-danger center-block btn-delete-dieta"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                            </td>
                                        </tr>
                                        <?php $total += $totalDieta ?>
                                    @endforeach
                                    <td colspan="10" ></td>
                                    <td class="text-right">
                                        {!! Form::text("TOTALDIETAS", $total, array( 'id' => 'totalDietas', 'class' => 'form-control', 'readonly' => true )) !!}
                                    </td>
                                @else
                                    <tr>
                                        <td colspan="12">
                                            Aún no hay ningún gasto para este ponente.
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>

                    </div>
                @endif

                @if(isset($manutencion))
                <h4 class="heading">Ponentes: Gastos manutención</h4>

                <div id="ponentes-gastos-manutencion" class="row">

                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Fecha salida</th>
                                <th>Hora salida</th>
                                <th>Fecha llegada</th>
                                <th>Hora llegada</th>
                                <th>Fecha almuerzo</th>
                                <th>Almuerzo</th>
                                <th>Fecha cena</th>
                                <th>Cena</th>
                                <th>Total</th>
                                <th>
                                    <a id="btn-add-manutencion" href="#" class="btn btn-xs btn-success center-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(count($manutencion) > 0)
                                <?php $total=0 ?>
                                @foreach($manutencion as $gasto)

                                    <tr>
                                        <td class="editable-cell">
                                            {!! Form::input("date", "INICIO:".$gasto->NUMERO, $gasto->INICIO, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::input("time", "LLEGADA:".$gasto->NUMERO, $gasto->LLEGADA, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::input("date", "FIN:".$gasto->NUMERO, $gasto->FIN, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::input("time", "SALIDA:".$gasto->NUMERO, $gasto->SALIDA, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::input("date", "FALMUERZO:".$gasto->NUMERO, $gasto->FALMUERZO, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::text("ALMUERZO:".$gasto->NUMERO, $gasto->ALMUERZO, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::input("date", "FCENA:".$gasto->NUMERO, $gasto->FCENA, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::text("CENA:".$gasto->NUMERO, $gasto->CENA, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td>
                                            <?php $totalManutencion = $gasto->ALMUERZO + $gasto->CENA ?>
                                            {!! Form::text("TOTALMANUTENCION:".$gasto->NUMERO, $totalManutencion, array( 'class' => 'form-control', 'readonly' => true )) !!}
                                        </td>
                                        <td class="button-cell">
                                            <a id="btn-delete-manutencion:{!! $gasto->NUMERO !!}" href="#" class="btn btn-xs btn-danger center-block btn-delete-manutencion"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                        </td>
                                    </tr>
                                    <?php $total += $totalManutencion ?>
                                @endforeach
                                <tr>
                                    <td colspan="8"></td>
                                    <td>
                                        {!! Form::text("TOTALMANUTENCIONES", $total, array( 'id' => 'totalManutenciones', 'class' => 'form-control', 'readonly' => true )) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="10">
                                        Aún no hay ningún gasto para este ponente.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>

                </div>
            @endif

            @if(isset($locomocion))
                <h4 class="heading">Ponentes: Gastos locomoción</h4>

                <div id="ponentes-gastos-locomocion" class="row">

                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Fecha salida</th>
                                <th>Hora salida</th>
                                <th>Fecha llegada</th>
                                <th>Hora llegada</th>
                                <th>Transporte</th>
                                <th>Itinerario</th>
                                <th>Km</th>
                                <th>Precio/Km</th>
                                <th>Importe</th>
                                <th>
                                    <a id="btn-add-locomocion" href="#" class="btn btn-xs btn-success center-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(count($locomocion) > 0)
                                <?php $total = 0 ?>
                                @foreach($locomocion as $gasto)

                                    <tr>
                                        <td class="editable-cell">
                                            {!! Form::input("date", "INICIO:".$gasto->NUMERO, $gasto->INICIO, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::input("time", "HORA:".$gasto->NUMERO, $gasto->HORA, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::input("date", "FIN:".$gasto->NUMERO, $gasto->FIN, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::input("time", "HORAF:".$gasto->NUMERO, $gasto->HORAF, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::select("TRANSPORTE:".$gasto->NUMERO, $transportes, $gasto->TRANSPORTE, array('class' => 'form-control')) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::text("ITINERARIO:".$gasto->NUMERO, $gasto->ITINERARIO, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::text("KM:".$gasto->NUMERO, $gasto->KM, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::text("IMPORTEKM:".$gasto->NUMERO, $gasto->IMPORTEKM, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="editable-cell">
                                            {!! Form::text("IMPORTE:".$gasto->NUMERO, $gasto->IMPORTE, array( 'class' => 'form-control' )) !!}
                                        </td>
                                        <td class="button-cell">
                                            <a id="btn-delete-locomocion:{!! $gasto->NUMERO !!}" href="#" class="btn btn-xs btn-danger center-block btn-delete-locomocion"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                        </td>
                                    </tr>
                                    <?php $total += $gasto->IMPORTE ?>
                                @endforeach
                                <tr>
                                    <td colspan="8"></td>
                                    <td>
                                        {!! Form::text("TOTALLOCOMOCION", $total, array( 'id' => 'totalLocomocion', 'class' => 'form-control', 'readonly' => true )) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="10">
                                        Aún no hay ningún gasto para este ponente.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>

                </div>
            @endif

            </div>
        </div>
        <!-- Fin Box -->
    </div>
    <!-- Fin Sección de formulario -->

@stop

@section('scripts')

<script>

    // Añadir ponente
    $('#buscar-dni-ponente-btn').click(function(e){

        e.preventDefault();

        var dniPonente = $('#buscar_dni_ponente').val();
        var route = "ponente/"+dniPonente;
        console.log(route);

        updateData(route);
        location.reload();

    });
    /****************
     * Dietas
     ****************/
    $('#btn-add-dieta').click(function(e){

        e.preventDefault();

        {!! $currentUrl = URL::current(); !!}
        var url = "{!! $currentUrl !!}"+"/add/dieta";

        updateData(url, [], function(){
            location.reload();
        });

    });

    $('.btn-delete-dieta').click(function(e){

        e.preventDefault();

        var tmp = $(this).attr("id").split(":");

        {!! $currentUrl = URL::current(); !!}
        var url = "{!! $currentUrl !!}"+"/delete/dieta/"+tmp[1];

        updateData(url, [], function(){
            location.reload();
        });

    });

    $("#ponentes-gastos-dieta .editable-cell > input").blur(function(){

        var tmp = $(this).attr("name").split(":");
        parametros = {
            fieldName: tmp[0],
            value: $(this).val()
        };

        console.log($(this).val());

        {!! $currentUrl = URL::current(); !!}
        var url = "{!! $currentUrl !!}"+"/edit/dieta/"+tmp[1];

        updateData(url, parametros);

        //console.log(fieldName + " " + idCurso + " " + sesionNum + " " + idProfesor);

    });
    /****************
     * Fin Dietas
     ****************/

     /****************
      * Manutención
      ****************/
     $('#btn-add-manutencion').click(function(e){

         e.preventDefault();

         {!! $currentUrl = URL::current(); !!}
         var url = "{!! $currentUrl !!}"+"/add/manutencion";

         updateData(url, [], function(){
            location.reload();
         });

     });

     $('.btn-delete-manutencion').click(function(e){

         e.preventDefault();

         var tmp = $(this).attr("id").split(":");

         {!! $currentUrl = URL::current(); !!}
         var url = "{!! $currentUrl !!}"+"/delete/manutencion/"+tmp[1];

         updateData(url, [], function(){
            location.reload();
         });

     });

     $("#ponentes-gastos-manutencion .editable-cell > input").blur(function(){

         var tmp = $(this).attr("name").split(":");
         parametros = {
             fieldName: tmp[0],
             value: $(this).val()
         };

         console.log($(this).val());

         {!! $currentUrl = URL::current(); !!}
         var url = "{!! $currentUrl !!}"+"/edit/manutencion/"+tmp[1];

         updateData(url, parametros);

         //console.log(fieldName + " " + idCurso + " " + sesionNum + " " + idProfesor);

     });
     /****************
      * Fin Manutención
      ****************/

      /****************
        * Locomoción
        ****************/
       $('#btn-add-locomocion').click(function(e){

           e.preventDefault();

           {!! $currentUrl = URL::current(); !!}
           var url = "{!! $currentUrl !!}"+"/add/locomocion";

           updateData(url, [], function(){
               location.reload();
           });

       });

       $('.btn-delete-locomocion').click(function(e){

           e.preventDefault();

           var tmp = $(this).attr("id").split(":");

           {!! $currentUrl = URL::current(); !!}
           var url = "{!! $currentUrl !!}"+"/delete/locomocion/"+tmp[1];

           updateData(url, [], function(){
               location.reload();
           });

       });

       $("#ponentes-gastos-locomocion .editable-cell > input, #ponentes-gastos-locomocion .editable-cell > select").blur(function(){

           var tmp = $(this).attr("name").split(":");
           parametros = {
               fieldName: tmp[0],
               value: $(this).val()
           };

           console.log($(this).val());

           {!! $currentUrl = URL::current(); !!}
           var url = "{!! $currentUrl !!}"+"/edit/locomocion/"+tmp[1];

           updateData(url, parametros);

           //console.log(fieldName + " " + idCurso + " " + sesionNum + " " + idProfesor);

       });
       /****************
        * Fin Locomoción
        ****************/

</script>

@stop