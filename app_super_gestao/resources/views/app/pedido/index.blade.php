@extends('app.layouts.basico')

@section('titulo','Pedido')
    
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Listagem de Pedidos</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('pedido.create') }}">Novo</a></li>
                <li><a href="{{ route('pedido.index') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 90%; margin: 0 auto;">
                <table border="1" width="100%">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $pedido)
                            <tr>
                                <td>{{$pedido->id}}</td>                                
                                <td>{{$pedido->cliente_id}}</td>                                
                                <td><a href={{route('pedido-produto.create',$pedido->id)}}>Adicionar Produtos</a></td>
                                <td><a href={{route('pedido.show',$pedido->id)}}>Vizualizar</a></td>
                                <td>
                                    <form id="form_{{$pedido->id}}" action="{{route('pedido.destroy',$pedido->id)}}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    </form>
                                    <a href="#" onclick="document.getElementById('form_{{$pedido->id}}').submit()">Excluir</a>
                                </td>
                                <td><a href={{route('pedido.edit',$pedido->id)}}>Editar</a></td>
                            </tr>
                        @endforeach        
                    </tbody>
                </table>
                {{ $pedidos->appends($request)->links() }}
            </div>
        </div>
    </div>
@endsection