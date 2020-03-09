    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Fecha del extracto</th>
                <th>Profesor</th>
                <th>DNI</th>
                <th>Móvil</th>
                <th>Correo</th>
            </tr>
        </thead>
        <tbody>
            @if(count($extractos->items()) > 0)
                @foreach($extractos->items() as $extracto)
                    <tr>
                        <td>{!! date("d/m/Y H:i:s", strtotime($extracto->NOTIFICACION->fecha)) !!}</td>
                        <td>{!! $extracto->NOMBRE !!} {!! $extracto->APELLIDO1 !!} {!! $extracto->APELLIDO2 !!}</td>
                        <td>{!! $extracto->DNI !!}</td>
                        <td>{!! $extracto->MOVIL !!}</td>
                        <td>{!! $extracto->MAIL !!}</td>
                        <td>
                            <a id="btn-delete-extracto" href="{!! URL::to("extractos/".$extracto->NOTIFICACION->id."/delete") !!}" class="confirm btn btn-xs btn-danger center-block" onclick="return confirm('¿Borrar notificación?')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6">
                        No hay resultados
                    </td>
                </tr>
            @endif

        </tbody>
    </table>

    @if($extractos)
    <div class="row">
        <div class="col-sm-6 col-sm-offset-6">
            <div class="pull-right">
                {!! $extractos->appends(['search' => $search, 'items' => $items])->links(); !!}
            </div>
        </div>
    </div>
    @endif