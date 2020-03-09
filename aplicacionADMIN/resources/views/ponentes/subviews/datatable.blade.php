<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>DNI</th>
            <th>Nombre</th>
        </tr>
    </thead>
    <tbody>
        @if(count($ponentes->items()) > 0)
            @foreach($ponentes as $ponente)
                <tr>
                    <td>{!! $ponente->CODIGO !!}</td>
                    <td>
                        <a href="{!! URL::to('ponentes/editar', array($ponente->CODIGO), false); !!}">
                            {!! $ponente->DNI !!}
                        </a>
                    </td>
                    <td>
                        <a href="{!! URL::to('ponentes/editar', array($ponente->CODIGO), false); !!}">
                            {!! $ponente->NOMBRE !!} {!! $ponente->APELLIDO1 !!} {!! $ponente->APELLIDO2 !!}
                        </a>
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

@if($ponentes)
<div class="row">
    <div class="col-sm-6 col-sm-offset-6">
        <div class="pull-right">
            {!! $ponentes->appends(['search' => $search, 'items' => $items])->links(); !!}
        </div>
    </div>
</div>
@endif