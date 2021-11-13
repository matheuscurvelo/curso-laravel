@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                Tarefas
                            </div>
                            <div class="col-6">
                                <div class="float-right">
                                    <a href="{{ route('tarefa.create') }}" type="button" class="mr-3">Adicionar</a>
                                    <a href="{{ route('tarefa.exportacao',['extensao'=>'xlsx']) }}" type="button" class="mr-3">XLSX</a>            
                                    <a href="{{ route('tarefa.exportacao',['extensao'=>'csv']) }}" type="button" class="mr-3">CSV</a>            
                                    <a href="{{ route('tarefa.exportacao',['extensao'=>'pdf']) }}" type="button" class="mr-3">PDF</a>            
                                    <a href="{{ route('tarefa.exportar') }}" target="_blank" type="button" class="mr-3">PDF v2</a>            
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Tarefa</th>
                                    <th scope="col">Data Limite Conclusao</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarefas as $tarefa)
                                    <tr>
                                        <th scope="row"> {{ $tarefa->id }}</th>
                                        <td> {{ $tarefa->tarefa }} </td>
                                        <td> {{ date('d/m/Y', strtotime($tarefa->data_limite_conclusao)) }} </td>
                                        <td>
                                            <a href=" {{ route('tarefa.edit',$tarefa->id) }} ">Editar</a>
                                            <a href="#" onclick="document.getElementById('form_{{ $tarefa->id }}').submit()">Excluir</a>
                                            <form id="form_{{ $tarefa->id }}" action="{{ route('tarefa.destroy',$tarefa->id) }} " method="post">@method('DELETE')@csrf
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <nav>
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="{{ $tarefas->previousPageUrl() }}">Voltar</a></li>
                                @for ($i = 1; $i <= $tarefas->lastPage(); $i++)
                                    <li class="page-item {{ $tarefas->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $tarefas->url($i) }}">{{ $i }}</a>
                                    </li>    
                                @endfor
                                <li class="page-item"><a class="page-link" href="{{ $tarefas->nextPageUrl() }}">Avançar</a></li>
                            </ul>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
