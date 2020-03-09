@extends('cursos.cursos_form_layout')

@section('form-tab')

<!-- Sección de formulario -->
    <div class="form-tab active">
        <!-- Box -->
        <div class="col-md-12">
            <div class="box">

                <h4 class="heading">Enviar e-mail</h4>

                @if(count($errors) > 0)
                    <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {!! $error !!} </br>
                    @endforeach
                    </div>
                @endif
                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                
                <div class="form-group">
                    <button type="button" onclick="soloAdmitidos()">Sólo admitidos</button>
                    <button type="button" onclick="soloExcluidos()">Sólo excluidos</button>
                    <button type="button" onclick="todos()">Todos</button>
                </div>
            
                <form id="datos" action="{!! URL::to('/cursos/enviar-email') !!}" method="post">
                    @csrf
                    <div class="form-group">
                        <label>De:</label>
                        <input class="form-control" type="text" name="de" id="de" value="{!! $emisor !!}">
                    </div>
                    <div class="form-group">
                        <label>Destinatarios:</label>
                        @if(session('receptor') != NULL)
                            <textarea class="form-control" name="direciones" id="direciones" rows="5" style="resize: none;">{!! session('receptor') !!}</textarea>
                        @else
                            <textarea class="form-control" name="direciones" id="direciones" rows="5" style="resize: none;"></textarea>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Asunto:</label>
                        @if(session('asunto') != NULL)
                            <textarea class="form-control" name="asunto" id="asunto" rows="1" style="resize: none;">{!! session('asunto') !!}</textarea>
                        @else
                            <textarea class="form-control" name="asunto" id="asunto" rows="1" style="resize: none;"></textarea>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Mensaje:</label>
                        @if(session('mensaje') != NULL)
                            <textarea class="form-control" name="mensaje" id="mensaje" rows="20" style="resize: none;">{!! session('mensaje') !!}</textarea>
                        @else
                            <textarea class="form-control" name="mensaje" id="mensaje" rows="20" style="resize: none;"></textarea>
                        @endif
                    </div>

                    <a onclick="document.getElementById('datos').submit();" class="btn btn-success"><span class="glyphicon glyphicon-send" aria-hidden="true"></span> Enviar</a>

                
                </form>
                    
                
                
            </div>
        </div>
    </div>
@stop

@section('scripts')

<script>
    function soloAdmitidos() {
        document.getElementById('direciones').value = '{{ $admitidos }}';
    }

    function soloExcluidos() {
        document.getElementById('direciones').value = '{{ $excluidos }}';
    }

    function todos() {
        document.getElementById('direciones').value = '{{ $admitidos .", ". $excluidos }}';
    }
</script>

@stop