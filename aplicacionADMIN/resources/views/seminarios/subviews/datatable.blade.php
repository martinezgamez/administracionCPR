    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>NI</th>
                <th>Nombre</th>
                <th>Modalidad formativa</th>
                <th id="finicio" onclick="ordenarInicio(event)">
                    @if ($orden == 'ninguno')
                        Inicio ▲▼
                    @elseif ($orden == 'descendente')
                        Inicio ▼
                    @else
                        Inicio ▲
                    @endif
                </th>
                <th>Fin</th>
            </tr>
        </thead>
        <tbody>
            @if(count($seminarios->items()) > 0)
                @foreach($seminarios as $seminario)
                    <tr>
                        <td>
                            <a href="{!! action('SeminariosController@datos', $seminario->CODIGO); !!}">
                                {!! $seminario->CODIGO !!}
                            </a>
                        </td>
                        <td>
                            {!! $seminario->NI !!}
                        </td>
                        <td>
                            <a href="{!! action('SeminariosController@datos', $seminario->CODIGO); !!}">
                                {!! $seminario->NOMBRE !!}
                            </a>
                        </td>
                        <td>
                            {!! $seminario->TIPO !!}
                        </td>
                        <td>
                            {!! date("d/m/Y", strtotime($seminario->INICIO)); !!}
                        </td>
                        <td>
                            {!! date("d/m/Y", strtotime($seminario->FIN)); !!}
                        </td>
                        <td>
                            <a id="btn-delete-seminario" href="{!! URL::to("seminarios/".$seminario->CODIGO."/delete") !!}" class="confirm btn btn-xs btn-danger center-block" onclick="return confirm('¿Borrar seminario/grupo de trabajo?')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">
                        No hay resultados
                    </td>
                </tr>
            @endif

        </tbody>
    </table>

    @if($seminarios)
    <div class="row">
        <div class="col-sm-6 col-sm-offset-6">
            <div class="pull-right">
                {!! $seminarios->appends(['search' => $search, 'items' => $items, 'activos' => $activos, 'orden' => $orden])->links(); !!}
            </div>
        </div>
    </div>
    @endif
<script>
    function ordenarInicio(e) {
        var celda = document.getElementById(e.target.id);
        var texto = celda.innerText;
        
        if(texto.includes('▲')) {
            ordenar('descendente');
            celda.innerText = 'Inicio ▼';

        } else if (texto.includes('▼')) {
            ordenar('ascendente');
            celda.innerText = 'Inicio ▲'
        } else {
            ordenar('descendente');
            celda.innerText = 'Inicio ▼'
        }
    }
</script>