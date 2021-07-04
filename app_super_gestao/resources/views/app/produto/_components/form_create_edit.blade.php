    @if (isset($produto->id))
    <form action="{{ route('produto.update',$produto->id) }}" method="POST">
        @method('PUT')
    @else
    <form action="{{ route('produto.store') }}" method="POST">
        @method('POST')
    @endif
        @csrf
        {{ $msg ?? ''}}
        <input type="text" name="nome" placeholder="nome" class="borda-preta" value="{{$produto->nome ?? old('nome')}}">
        {{$errors->has('nome') ? $errors->first('nome') : ''}}
        <input type="text" name="descricao" placeholder="descricao" class="borda-preta" value="{{$produto->descricao ?? old('descricao')}}">
        {{$errors->has('descricao') ? $errors->first('descricao') : ''}}
        <input type="text" name="peso" placeholder="peso" class="borda-preta" value="{{$produto->peso ?? old('peso')}}">
        {{$errors->has('peso') ? $errors->first('peso') : ''}}
        <select name="unidade_id">
            <option selected>--Selecione a unidade de medida--</option>
            @foreach ($unidades as $unidade)
                <option value={{$unidade->id}} {{($produto->unidade_id ?? old('unidade_id')) == $unidade->id ? 'selected' : ''}}>{{$unidade->descricao}}</option>
            @endforeach
        </select>
        {{$errors->has('unidade_id') ? $errors->first('unidade_id') : ''}}
        <button type="submit" class="borda-preta">
            @if (isset($produto->id))
                Alterar
            @else
                Cadastrar
            @endif
        </button>
    </form>