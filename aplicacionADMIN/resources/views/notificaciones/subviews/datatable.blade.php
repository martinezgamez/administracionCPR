    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Fecha notificación</th>
                <th>Información</th>
            </tr>
        </thead>
        <tbody>
            @if(count($notificaciones->items()) > 0)
                @foreach($notificaciones->items() as $notificacion)
                    <tr>
                        <td>{!! date("d/m/Y H:i:s", strtotime($notificacion->fecha)) !!}</td>
                        <td>{!! $notificacion->informacion !!}</td>
                        <td>
                            <a id="btn-delete-notificacion" href="{!! URL::to("notificaciones/".$notificacion->id."/delete") !!}" class="confirm btn btn-xs btn-danger center-block" onclick="return confirm('¿Borrar notificación?')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">
                        No hay resultados
                    </td>
                </tr>
            @endif

        </tbody>
    </table>

    @if($notificaciones)
    <div class="row">
        <div class="col-sm-6 col-sm-offset-6">
            <div class="pull-right">
                {!! $notificaciones->appends(['search' => $search, 'items' => $items])->links(); !!}
            </div>
        </div>
    </div>
    @endif