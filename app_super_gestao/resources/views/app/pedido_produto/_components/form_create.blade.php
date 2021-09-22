<form action="{{ route('pedido-produto.store', ['pedido_id'=>$pedido]) }}" method="POST">
    @csrf   
    {{ $msg ?? ''}}
    
    <select name="produto_id">
        <option selected>--Selecione o Produto--</option>
        @foreach ($produtos as $produto)
            <option value={{ $produto->id }} {{ old('produto_id') == $produto->id ? 'selected' : ''}}>{{$produto->nome}}</option>
        @endforeach
    </select>
    {{$errors->has('produto_id') ? $errors->first('produto_id') : ''}}
    <input type="number" name="quantidade" value="{{ old('quantidade') ? old('quantidade') : '' }}" placeholder="Quantidade" class="borda-preta">
    {{$errors->has('quantidade') ? $errors->first('quantidade') : ''}}
    <button type="submit" class="borda-preta">
        @if (isset($pedido->id))
            Alterar
        @else
            Cadastrar
        @endif
    </button>
</form>