    @if (isset($produto_detalhe->id))
    <form action="{{ route('produto-detalhe.update',$produto_detalhe->id) }}" method="POST">
        @method('PUT')
    @else
    <form action="{{ route('produto-detalhe.store') }}" method="POST">
    @endif
        @csrf
        {{ $msg ?? ''}}
        <input type="text" name="produto_id" placeholder="Id do produto" class="borda-preta" value="{{$produto_detalhe->produto_id ?? old('produto_id')}}">
        {{$errors->has('produto_id') ? $errors->first('produto_id') : ''}}
        <input type="text" name="comprimento" placeholder="comprimento" class="borda-preta" value="{{$produto_detalhe->comprimento ?? old('comprimento')}}">
        {{$errors->has('comprimento') ? $errors->first('comprimento') : ''}}
        <input type="text" name="largura" placeholder="largura" class="borda-preta" value="{{$produto_detalhe->largura ?? old('largura')}}">
        {{$errors->has('largura') ? $errors->first('largura') : ''}}
        <input type="text" name="altura" placeholder="altura" class="borda-preta" value="{{$produto_detalhe->altura ?? old('altura')}}">
        {{$errors->has('altura') ? $errors->first('altura') : ''}}
        <select name="unidade_id">
            <option selected>--Selecione a unidade de medida--</option>
            @foreach ($unidades as $unidade)
                <option value={{$unidade->id}} {{($produto_detalhe->unidade_id ?? old('unidade_id')) == $unidade->id ? 'selected' : ''}}>{{$unidade->descricao}}</option>
            @endforeach
        </select>
        {{$errors->has('unidade_id') ? $errors->first('unidade_id') : ''}}
        <button type="submit" class="borda-preta">
            @if (isset($produto_detalhe->id))
                Alterar
            @else
                Cadastrar
            @endif
        </button>
        {{print_r($errors)}}
    </form>