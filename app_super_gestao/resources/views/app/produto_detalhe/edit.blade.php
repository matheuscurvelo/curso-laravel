@extends('app.layouts.basico')

@section('titulo','Produto')
    
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Editar Produto</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('produto.index') }}">Voltar</a></li>
                <li><a href="{{ route('produto.index') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">

            <h4>Produto</h4>
            <div>Nome: {{ $produto_detalhe->produto->nome }}</div>
            <br>
            <div>Descrição: {{ $produto_detalhe->produto->descricao }}</div>
            <br>
            <div style="width: 30%; margin: 0 auto">
                @component('app.produto_detalhe._components.form_create_edit',compact('produto_detalhe','unidades'))
                    
                @endcomponent
            </div>
        </div>
    </div>
@endsection