    @if (isset($pedido->id))
    <form action="{{ route('pedido.update',$pedido->id) }}" method="POST">
        @method('PUT')
    @else
    <form action="{{ route('pedido.store') }}" method="POST">
        @method('POST')
    @endif
        @csrf
        {{ $msg ?? ''}}
        
        <select name="cliente_id">
            <option selected>--Selecione o Cliente--</option>
            @foreach ($clientes as $cliente)
                <option value={{$cliente->id}} {{($pedido->cliente_id ?? old('cliente_id')) == $cliente->id ? 'selected' : ''}}>{{$cliente->nome}}</option>
            @endforeach
        </select>
        {{$errors->has('cliente_id') ? $errors->first('cliente_id') : ''}}
        
        <button type="submit" class="borda-preta">
            @if (isset($pedido->id))
                Alterar
            @else
                Cadastrar
            @endif
        </button>
    </form>