<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Verificado</th>
        </tr>
    </thead>
    <tbody>
        @if(count($profesores->items()) > 0)
            @foreach($profesores as $profesor)
                <tr>
                    <td>{!! $profesor->CODIGO !!}</td>
                    <td>
                        <a href="{!! URL::to('profesores/editar', array($profesor->CODIGO), false); !!}">
                            {!! $profesor->DNI !!}
                        </a>
                    </td>
                    <td>
                        <a href="{!! URL::to('profesores/editar', array($profesor->CODIGO), false); !!}">
                            {!! $profesor->NOMBRE !!} {!! $profesor->APELLIDO1 !!} {!! $profesor->APELLIDO2 !!}
                        </a>
                    </td>
                    <td>
                        @if($profesor->BAJA == 0)
                            <span class="label label-success">Activo</span>
                        @else
                            <span class="label label-default">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        @if($profesor->VERIFICADO)
                            <span class="label label-success">Verificado</span>
                        @else
                            <span class="label label-default">No verificado</span>
                        @endif
                    </td>
                    <td>
                        <a id="btn-delete-curso" href="{!! URL::to("/profesores/".$profesor->CODIGO."/delete") !!}" class="confirm btn btn-xs btn-danger center-block" onclick="return confirm('¿Borrar profesor?')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
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

@if($profesores)
<div class="row">
    <div class="col-sm-6 col-sm-offset-6">
        <div class="pull-right">
            {!! $profesores->appends(['search' => $search, 'items' => $items])->links(); !!}
        </div>
    </div>
</div>
@endif