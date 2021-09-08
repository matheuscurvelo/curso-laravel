    @if (isset($cliente->id))
    <form action="{{ route('cliente.update',$cliente->id) }}" method="POST">
        @method('PUT')
    @else
    <form action="{{ route('cliente.store') }}" method="POST">
        @method('POST')
    @endif
        @csrf
        {{ $msg ?? ''}}
        
        <input type="text" name="nome" placeholder="nome" class="borda-preta" value="{{$cliente->nome ?? old('nome')}}">
        {{$errors->has('nome') ? $errors->first('nome') : ''}}
        
        <button type="submit" class="borda-preta">
            @if (isset($cliente->id))
                Alterar
            @else
                Cadastrar
            @endif
        </button>
    </form>