    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Curso</th>
            </tr>
        </thead>
        <tbody>
            @if(count($cursos->items()) > 0)
                @foreach($cursos as $curso)
                    <tr>
                        <td>
                            <a href="{!! action('CursosController@cursoDatos', $curso->CODIGO); !!}">
                                {!! $curso->CODIGO !!}
                            </a>
                        </td>
                        <td>
                            <a href="{!! action('CursosController@cursoDatos', $curso->CODIGO); !!}">
                                {!! $curso->NOMBRE !!}
                            </a>
                        </td>
                        <td>
                            {!! $curso->INICIO !!}
                        </td>
                        <td>
                            {!! $curso->FIN !!}
                        </td>
                        <td>
                            {!! $curso->ANNO !!}
                        </td>
                        <td>
                            <a id="btn-delete-curso" href="{!! URL::to("cursos/".$curso->CODIGO."/delete") !!}" class="confirm btn btn-xs btn-danger center-block" onclick="return confirm('¿Borrar curso?')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
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

    @if($cursos)
    <div class="row">
        <div class="col-sm-6 col-sm-offset-6">
            <div class="pull-right">
                {!! $cursos->appends(['search' => $search, 'items' => $items])->links(); !!}
            </div>
        </div>
    </div>
    @endif