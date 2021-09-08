@extends('app.layouts.basico')

@section('titulo','Pedido')
    
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Adicionar Pedido</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('pedido.index') }}">Voltar</a></li>
                <li><a href="{{ route('pedido.index') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 30%; margin: 0 auto">
                @component('app.pedido._components.form_create_edit',compact('clientes'))
                    
                @endcomponent
            </div>
        </div>
    </div>
@endsection