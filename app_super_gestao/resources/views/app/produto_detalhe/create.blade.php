@extends('app.layouts.basico')

@section('titulo','Detalhes do Produto')
    
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Adicionar detalhes do Produto</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('produto-detalhe.index') }}">Voltar</a></li>
            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 30%; margin: 0 auto">
                @component('app.produto_detalhe._components.form_create_edit',compact('unidades'))
                    
                @endcomponent
            </div>
        </div>
    </div>
@endsection