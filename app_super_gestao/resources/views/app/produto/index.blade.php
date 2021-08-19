﻿@extends('app.layouts.basico')

@section('titulo','Produto')
    
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Listagem de Produtos</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('produto.create') }}">Novo</a></li>
                <li><a href="{{ route('produto.index') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 90%; margin: 0 auto;">
                <table border="1" width="100%">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Peso</th>
                            <th>Unidade ID</th>
                            <th>Comprimento</th>
                            <th>Altura</th>
                            <th>Largura</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produtos as $produto)
                            <tr>
                                <td>{{$produto->nome}}</td>
                                <td>{{$produto->descricao}}</td>
                                <td>{{$produto->peso}}</td>
                                <td>{{$produto->unidade_id}}</td>
                                <td>{{$produto->produtoDetalhe->comprimento ?? ''}}</td>
                                <td>{{$produto->produtoDetalhe->altura ?? ''}}</td>
                                <td>{{$produto->produtoDetalhe->largura ?? ''}}</td>
                                <td><a href={{route('produto.show',$produto->id)}}>Vizualizar</a></td>
                                <td>
                                    <form id="form_{{$produto->id}}" action="{{route('produto.destroy',$produto->id)}}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    </form>
                                    <a href="#" onclick="document.getElementById('form_{{$produto->id}}').submit()">Excluir</a>
                                </td>
                                <td><a href={{route('produto.edit',$produto->id)}}>Editar</a></td>
                            </tr>
                        @endforeach        
                    </tbody>
                </table>
                {{ $produtos->appends($request)->links() }}
            </div>
        </div>
    </div>
@endsection