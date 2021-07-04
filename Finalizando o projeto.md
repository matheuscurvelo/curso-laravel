# Fianlizando o projeto
## 1 - Implementando o cadastro de fornecedores

Criar a pasta :file_folder:resourses/views/app/fornecedor e o colocar :page_facing_up:fornecedor.blade.php dentro dela e renomear este arquivo para :page_facing_up:index.blade.php.

:page_facing_up:FornecedorController.php
```php
class FornecedorController extends Controller
{
    public function index()
    {
        return view('app.fornecedor.index');
    }

    public function listar(Request $request)
    {
        $fornecedores = Fornecedor::where('nome','like',"%".$request->input('nome')."%")
            ->where('email','like',"%".$request->input('email')."%")
            ->where('uf','like',"%".$request->input('uf')."%")
            ->where('site','like',"%".$request->input('site')."%")
            ->get();

        return view('app.fornecedor.listar',compact('fornecedores'));
    }

    public function adicionar(Request $request)
    {
        $msg = "";

        if ($request->input('_token') != '' && $request->input('id') == '') {
            
            //validacao
            $regras = [
                'nome' => 'required|min:3|max:40',
                'site' => 'required',
                'uf' => 'required|min:2|max:2',
                'email' => 'email'
            ];

            $feedback = [
                'required' => "O campo :attribute deve ser preenchido",
                'nome.min' => "O campo :attribute deve ter no minimo 3 caracteres",
                'nome.max' => "O campo :attribute deve ter no minimo 40 caracteres",
                'uf.min' => "O campo :attribute deve ter no minimo 2 caracteres",
                'uf.max' => "O campo :attribute deve ter no maximo 2 caracteres",
                'email' => "O campo :attribute não foi preenchido corretamente",
            ];

            $request->validate($regras,$feedback);
            
            Fornecedor::create($request->all());

            $msg = "Cadastro Feito com sucesso";

        }elseif ($request->input('_token') != '' && $request->input('id') != '') {
            $id = $request->input('id');
            $update = Fornecedor::find($id)->update($request->all());
            if ($update) {
                $msg = 'Update realizado com sucesso';
            }else{
                $msg = 'Update Falhou';
            }

            return redirect()->route('app.fornecedor.editar',compact('msg','id'));
        }

        return view('app.fornecedor.adicionar',compact('msg'));

    }

    public function editar($id,$msg = '')
    {
        $fornecedor = Fornecedor::find($id);

        return view('app.fornecedor.adicionar', compact('fornecedor','msg'));
    }

    public function excluir($id)
    {
        Fornecedor::find($id)->delete();
        
        return redirect()->route('app.fornecedor');
    }
}
```

:page_facing_up:web.php
```php
    Route::get('/fornecedor','FornecedorController@index')->name('app.fornecedor'); 
    Route::post('/fornecedor/listar','FornecedorController@listar')->name('app.fornecedor.listar'); 
    Route::get('/fornecedor/adicionar','FornecedorController@adicionar')->name('app.fornecedor.adicionar'); 
    Route::post('/fornecedor/adicionar','FornecedorController@adicionar')->name('app.fornecedor.adicionar'); 
    Route::get('/fornecedor/editar/{id}/{msg?}','FornecedorController@editar')->name('app.fornecedor.editar'); 
    Route::get('/fornecedor/excluir/{id}','FornecedorController@excluir')->name('app.fornecedor.excluir'); 

```

:page_facing_up:index.blade.php
```html
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Fornecedor</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('app.fornecedor.adicionar') }}">Novo</a></li>
                <li><a href="{{ route('app.fornecedor') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 30%; margin: 0 auto">
                <form action="{{ route('app.fornecedor.listar') }}" method="POST">
                    @csrf
                    <input type="text" name="nome" placeholder="nome" class="borda-preta">
                    <input type="text" name="site" placeholder="site" class="borda-preta">
                    <input type="text" name="uf" placeholder="UF" class="borda-preta">
                    <input type="text" name="email" placeholder="email" class="borda-preta">
                    <button type="submit" class="borda-preta">Pesquisa</button>
                </form>
            </div>
        </div>
    </div>
@endsection
```

:page_facing_up:adicionar.blade.php

Esta página terá o mesmo conteudo da página acima com algumas alterações
```html
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Fornecedor - Adicionar</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('app.fornecedor.adicionar') }}">Novo</a></li>
                <li><a href="{{ route('app.fornecedor') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 30%; margin: 0 auto">
                <form action="{{ route('app.fornecedor.adicionar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value={{ $fornecedor->id ?? ''}}>
                    {{ $msg ?? ''}}
                    <input type="text" name="nome" placeholder="nome" class="borda-preta" value="{{$fornecedor->nome ?? old('nome')}}">
                    {{ ($errors->has('nome')) ? $errors->first('nome') : '' }}

                    <input type="text" name="site" placeholder="site" class="borda-preta" value="{{$fornecedor->site ?? old('site')}}">
                    {{ ($errors->has('site')) ? $errors->first('site') : '' }}

                    <input type="text" name="uf" placeholder="UF" class="borda-preta" value="{{$fornecedor->uf ?? old('uf')}}">
                    {{ ($errors->has('uf')) ? $errors->first('uf') : '' }}

                    <input type="text" name="email" placeholder="email" class="borda-preta" value="{{$fornecedor->email ?? old('email')}}">
                    {{ ($errors->has('email')) ? $errors->first('email') : '' }}

                    <button type="submit" class="borda-preta">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
```

:page_facing_up:listar.blade.php
```html
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Fornecedor - Listar</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('app.fornecedor.adicionar') }}">Novo</a></li>
                <li><a href="{{ route('app.fornecedor') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 90%; margin: 0 auto;">
                <table border="1" width="100%">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Site</th>
                            <th>UF</th>
                            <th>Email</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fornecedores as $fornecedor)
                            <tr>
                                <td>{{$fornecedor->nome}}</td>
                                <td>{{$fornecedor->site}}</td>
                                <td>{{$fornecedor->uf}}</td>
                                <td>{{$fornecedor->email}}</td>
                                <td><a href={{route('app.fornecedor.excluir',$fornecedor->id)}}>Excluir</a></td>
                                <td><a href={{route('app.fornecedor.editar',$fornecedor->id)}}>Editar</a></td>
                            </tr>
                        @endforeach        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
```

## 2 - Paginação de registros

:page_facing_up:FornecedorController.php
```php
public function listar(Request $request)
{
    $fornecedores = Fornecedor::where('nome','like',"%".$request->input('nome')."%")
        ->where('email','like',"%".$request->input('email')."%")
        ->where('uf','like',"%".$request->input('uf')."%")
        ->where('site','like',"%".$request->input('site')."%")
        ->paginate(2);

    $request = $request->all();


    return view('app.fornecedor.listar',compact('fornecedores','request'));
}
```

Replicar a rota 'app.fornecedor.listar' e alterar metodo post para o método get
:page_facing_up:web.php
```php
    Route::get('/fornecedor/listar','FornecedorController@listar')->name('app.fornecedor.listar'); 
```

Adicionar após a tag `<table>`

:page_facing_up:listar.blade.php
```html
{{ $fornecedores->appends($request)->links() }}
```

Outros métodos do paginate() para serem usados no blade

`$fornecedores->count()` - Total de registros mostrados em cada página

`$fornecedores->total()` - Total de registros da consulta

`$fornecedores->first()` - nº primeiro registro da página

`$fornecedores->last()`  - nºúltimo registro da página

:page_facing_up:estilo_basico.css
```css
.pagination li{
    display: inline;
    margin: 0 5px;
}
```

## 3 - Controladores com resources

Com a instrução `php artisan make:controller -r [nome_do_controlador]` é possivel criar um controlador responsável por fazer o crud básico de um modelo

`index()` - exibe a lista de registros

`create()` - exibe formulário de criação do registro

`store()` - recebe formulário de criação do registro

`show()` - exibe um registro específico

`edit()` - exibe formulário de edição do registro

`update()` - recebe o formulário de edição do registro

`destroy()` - recebe os dados para remoção do registro

E então, em `web.php` só é necessário adicionar uma linha
```php
    Route::resource('produto', 'ProdutoController');    
```

## 4 - Implementando o cadastro de produtos

:page_facing_up:ProdutoController.php
```php
class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $produtos = Produto::paginate(3);

        $request = $request->all();

        return view('app.produto.index',compact('produtos','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unidades = Unidade::all();
        return view('app.produto.create',compact('unidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $regras = [
            'nome' => 'required|min:3|max:40',
            'descricao' => 'required|min:3|max:2000',
            'peso' => 'required|integer',
            'unidade_id' => 'exists:unidades,id',
        ];
        $request->validate($regras);

        Produto::create($request->all());
        return redirect()->route('produto.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produto = Produto::find($id);

        return view('app.produto.show',compact('produto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $produto = Produto::find($id);
        $unidades = Unidade::all();

        return view('app.produto.edit',compact('produto','unidades'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $produto = Produto::find($id)->update($request->all());
        return redirect()->route('produto.show',['produto'=>$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produto = Produto::destroy($id);
        return redirect()->route('produto.index');
    }
}
```


:page_facing_up:index.blade.php
```html
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
```

:page_facing_up:create.blade.php
```html
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Adicionar Produto</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('produto.index') }}">Voltar</a></li>
                <li><a href="{{ route('produto.index') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 30%; margin: 0 auto">
                <form action="{{ route('produto.store') }}" method="POST">
                    @csrf
                    {{ $msg ?? ''}}
                    <input type="text" name="nome" placeholder="nome" class="borda-preta" value="{{old('nome')}}">
                    {{$errors->has('nome') ? $errors->first('nome') : ''}}
                    <input type="text" name="descricao" placeholder="descricao" class="borda-preta" value="{{old('descricao')}}">
                    {{$errors->has('descricao') ? $errors->first('descricao') : ''}}
                    <input type="text" name="peso" placeholder="peso" class="borda-preta" value="{{old('peso')}}">
                    {{$errors->has('peso') ? $errors->first('peso') : ''}}
                    <select name="unidade_id">
                        <option selected>--Selecione a unidade de medida--</option>
                        @foreach ($unidades as $unidade)
                            <option value={{$unidade->id}} {{old('unidade_id') == $unidade->id ? 'selected' : ''}}>{{$unidade->descricao}}</option>
                        @endforeach
                    </select>
                    {{$errors->has('unidade_id') ? $errors->first('unidade_id') : ''}}
                    <button type="submit" class="borda-preta">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
```

:page_facing_up:show.blade.php
```html
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Vizualizar Produto</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('produto.index') }}">Voltar</a></li>
                <li><a href="{{ route('produto.index') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 30%; margin: 0 auto">
                <table border="1">
                    <tr>
                        <td>ID:</td>
                        <td>{{$produto->id}}</td>
                    </tr>
                    <tr>
                        <td>Nome:</td>
                        <td>{{$produto->nome}}</td>
                    </tr>
                    <tr>
                        <td>Descricao:</td>
                        <td>{{$produto->descricao}}</td>
                    </tr>
                    <tr>
                        <td>Peso:</td>
                        <td>{{$produto->peso}} kg</td>
                    </tr>
                    <tr>
                        <td>Unidade de medida:</td>
                        <td>{{$produto->unidade_id}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
```

:page_facing_up:edit.blade.php
```html
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
            <div style="width: 30%; margin: 0 auto">
                <form action="{{ route('produto.update',$produto->id) }}" method="POST">
                    @method('PUT')
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
                    <button type="submit" class="borda-preta">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
```

Outras duas formas de fazer a edição dos registros:

### 4.1 - Modificando a forma de edição de registros

Apagar o arquivo :page_facing_up:create.blade.php e

Renomear o arquivo :page_facing_up:edit.blade.php para :page_facing_up:create.blade.php

Alterar `app.produto.edit` para `app.produto.create` no arquivo :page_facing_up:create.blade.php
```php
public function edit($id)
{
    $produto = Produto::find($id);
    $unidades = Unidade::all();

    return view('app.produto.create',compact('produto','unidades'));

}
```

:page_facing_up:create.blade.php
```html
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            @if (isset($produto->id))
                <p>Editar Produto</p>
            @else
                <p>Adicionar Produto</p>
            @endif
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('produto.index') }}">Voltar</a></li>
                <li><a href="{{ route('produto.index') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 30%; margin: 0 auto">
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
            </div>
        </div>
    </div>
@endsection
```

### 4.2 - Modificando a forma de edição de registros

Criar um componente dentro da pasta produto :open_folder:_component :page_facing_up:form_create_edit.blade.php

```html
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
```

:page_facing_up:create.blade.php
```html
@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Adicionar Produto</p>
        </div>
        <div class="menu">
            <ul>
                <li><a href="{{ route('produto.index') }}">Voltar</a></li>
                <li><a href="{{ route('produto.index') }}">Consulta</a></li>

            </ul>
        </div>
        <div class="informacao-pagina">
            <div style="width: 30%; margin: 0 auto">
                @component('app.produto._components.form_create_edit',compact('unidades'))
                    
                @endcomponent
            </div>
        </div>
    </div>
@endsection
```

:page_facing_up:edit.blade.php
```html
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
            <div style="width: 30%; margin: 0 auto">
                @component('app.produto._components.form_create_edit',compact('produto','unidades'))
                    
                @endcomponent
            </div>
        </div>
    </div>
@endsection
```