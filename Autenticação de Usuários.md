# Autenticação de usuários
1. [Implementando Formulário de Login](#1---implementando-formulrio-de-login) 
2. [Recebendo os parametros de usuário e senha](#2---recebendo-os-parametros-de-usurio-e-senha)
3. [Validando a existência do usuário e senha no Banco de Dados](#3---validando-a-existncia-do-usurio-e-senha-no-banco-de-dados)
4. [Redirect com envio de parâmetros - Apresentando mensagem de erro de login](#4---redirect-com-envio-de-parmetros---apresentando-mensagem-de-erro-de-login)
5. [Iniciando a Superglobal Session e validando o acesso a rotas protegidas](#5---iniciando-a-superglobal-session-e-validando-o-acesso-a-rotas-protegidas)
6. [Implementando o menu de opções da área protegida da aplicação](#6---implementando-o-menu-de-opes-da-rea-protegida-da-aplicao)
7. [Adicionando a função logout](#7---adicionando-a-funo-logout)


## 1 - Implementando Formulário de Login
:open_file_folder: Web.php
```php
Route::get('/login','LoginController@index')->name('site.login');
Route::post('/login','LoginController@autenticar')->name('site.login');
```

``
php artisan make:controller LoginController
``

:open_file_folder: LoginController.php
```php
class LoginController extends Controller
{
    public function index()
    {
        return view('site.login',['titulo'=>'login']);
    }

    public function autenticar()
    {
        return "Chegamos até aqui";
    }
}
```

Criar o arquivo :open_file_folder:login.blade.php e copiar todo o conteúdo de :open_file_folder:login.blade.php e colar nele. Após isso, fazer as seguintes alterações neste arquivo:

```html
<div class="conteudo-pagina">
    <div class="titulo-pagina">
        <h1>Login</h1>
    </div>

    <div class="informacao-pagina">
        <div style="width: 30%; margin: 0 auto;">
            <form action="{{ route('site.login') }}" method="post">
                @csrf
                <input type="text" name="usuario" placeholder="Usuário" class="borda-preta">
                <input type="password" name="senha" placeholder="Senha" class="borda-preta">
                <button type="submit" class="borda-preta">Acessar</button>
            </form>
        </div>
    </div>  
</div>
```

## 2 - Recebendo os parametros de usuário e senha
:open_file_folder: LoginController.php
```php
class LoginController extends Controller
{
    public function autenticar()
    {
       $regras = [
            'usuario'=>'email',
            'senha'=>'required'
        ];
        $feedback = [
            'usuario.email' => 'O email do usuário é obrigatório',
            'senha.required'=> 'O campo senha é obrigatório'
        ];

        $request->validate($regras,$feedback);

        print_r($request->all());
    }
}
```

:open_file_folder: login.blade.php
```html
<input type="text" name="usuario" value="{{ old('usuario') }}" placeholder="Usuário" class="borda-preta">
{{ $errors->has('usuario') ? $errors->first('usuario') : '' }}
<input type="password" name="senha" value="{{ old('senha') }}" placeholder="Senha" class="borda-preta">
{{ $errors->has('senha') ? $errors->first('senha') : '' }}
```

## 3 - Validando a existência do usuário e senha no Banco de Dados

:open_file_folder: LoginController.php
```php
class LoginController extends Controller
{
    public function autenticar()
    {
       $regras = [
            'usuario'=>'email',
            'senha'=>'required'
        ];
        $feedback = [
            'usuario.email' => 'O email do usuário é obrigatório',
            'senha.required'=> 'O campo senha é obrigatório'
        ];

        //retorna para a ultima tela caso não passe pelo validate
        $request->validate($regras,$feedback);

       //recupera os parametros do formulario
        $email = $request->input('usuario');
        $password = $request->input('senha');

        //iniciar o model
        $user = new User();
        $usuario = $user->where('email',$email)->where('password',$password)->get()->first();
        
        if (isset($usuario->name)) {
            echo 'Usuário existe';
        }else{
            echo 'Usuário não existe';
        }
    }
}
```

## 4 - Redirect com envio de parâmetros - Apresentando mensagem de erro de login

:open_file_folder: LoginController.php
```php
class LoginController extends Controller
{
    public function index(Request $request)
    {
    
        $erro = $request->get('erro');

        switch ($erro) {
            case '1':
                $erro = 'Usuário e/ou Senha não existe';
                break;
            
            default:
                break;
        }

        return view('site.login',['titulo'=>'login', 'erro'=>$erro]);
    }

    public function autenticar()
    {
        //...

        if (isset($usuario->name)) {

            session_start();
            $_SESSION['nome'] = $usuario->nome;
            $_SESSION['email'] = $usuario->email;
            return redirect()->route('app.home');

        }else{
            echo redirect()->route('site.login',['erro'=>1]);
        }
    }
}
```

:open_file_folder: login.blade.php
```html
</form>
{{ !empty($erro) ? $erro : '' }}
```

## 5 - Iniciando a Superglobal Session e validando o acesso a rotas protegidas

:open_file_folder: LoginController.php
```php
class LoginController extends Controller
{
    public function index(Request $request)
    {
    
        $erro = $request->get('erro');

        switch ($erro) {
            case '1':
                $erro = 'Usuário e/ou Senha não existe';
                break;
            
            case '2':
                $erro = 'Necessário realizar login para ter acesso a página';
                break;

            default:
                break;
        }

        return view('site.login',['titulo'=>'login', 'erro'=>$erro]);
    }
}
```

:open_file_folder: AutenticacaoMiddleware.php
```php
class AutenticacaoMiddleware
{
    public function handle($request, Closure $next, $metodo_autenticacao, $perfil)
    {
        session_start();
        if (!empty($_SESSION['email'])) {
            return $next($request);
        }else {
            return redirect()->route('site.login',['erro'=>2]);
        }
    }
}
```

## 6 - Implementando o menu de opções da área protegida da aplicação

:open_file_folder: Web.php
```php
Route::prefix('/app')->middleware('autenticacao:padrao,visitante')->group(function(){
    Route::get('/home','HomeController@index')->name('app.home');
    Route::get('/sair','LoginController@sair')->name('app.sair');
    Route::get('/clientes','ClientesController@index')->name('app.clientes');
    Route::get('/fornecedores','FornecedorController@index')->name('app.fornecedores');    
    Route::get('/produtos','ProdutoController@index')->name('app.produtos');
});
```

``
php artisan make:controller HomeController
php artisan make:controller ClienteController
php artisan make:controller ProdutoController
``

Copiar o conteúdo do :open_file_folder:HomeController.php em :open_file_folder:ClienteController.php e :open_file_folder:ProdutoController.php

:open_file_folder: HomeController.php
```php
class HomeController extends Controller
{
    public function index()
    {
        return view('app.home');
    }
}
```

:open_file_folder: LoginController.php
```php
class LoginController extends Controller
{
    public function sair()
    {
        echo 'sair';
    }
}
```

Criar a pasta :open_file_folder:`resources/views/app` e criar os arquivos:
:open_file_folder:home.blade.php

:open_file_folder:produto.blade.php

:open_file_folder:cliente.blade.php

:open_file_folder:fornecedor.blade.php

Após isso, replicar o seguinte conteúdo em cada um destes arquivos, alterando apenas o titulo:
```html
@extends('site.layouts.basico')

@section('titulo','Home')
    
@section('conteudo')

@endsection
```

criar tambem a pasta :open_file_folder:`resources/views/app/layouts/_partials` e criar o arquivo:

:open_file_folder:topo.blade.php
```html
<div class="topo">

    <div class="logo">
        <img src="img/logo.png">
    </div>

    <div class="menu">
        <ul>
            <li><a href="{{ route('site.home') }}">Home</a></li>
            <li><a href="{{ route('site.cliente') }}">Cliente</a></li>
            <li><a href="{{ route('site.fornecedor') }}">Fornecedor</a></li>
            <li><a href="{{ route('site.produto') }}">Produto</a></li>
        </ul>
    </div>
    
</div>
```

E por último, criar tambem a pasta :open_file_folder:`resources/views/app/layouts` e criar o arquivo: 

:open_file_folder:app/basico.blade.php
```html
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Super Gestão - @yield('titulo')</title>
        <meta charset="utf-8">

        <link rel="stylesheet" href="{{ asset('css/estilo_basico.css') }}">    
    </head>
    <body>
        @include('app.layouts._partials.topo')
        @yield('conteudo')
    </body>
</html>
```

## 7 - Adicionando a função logout

:open_file_folder: LoginController.php
```php
    public function sair()
    {
        session_destroy();
        return redirect()->route('site.index');
    }
```