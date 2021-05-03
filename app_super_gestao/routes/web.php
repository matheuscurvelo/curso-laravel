<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PrincipalController@principal')->name('site.index');
Route::get('/sobre-nos', 'SobreNosController@sobrenos')->name('site.sobrenos');
Route::get('/contato', 'ContatoController@contato')->name('site.contato');
Route::post('/contato', 'ContatoController@contato')->name('site.contato');
Route::get('/login',function (){ return 'login'; })->name('site.login');


Route::prefix('/app')->group(function(){
    Route::get('/clientes',function (){ return 'clientes'; })->name('app.clientes');
    Route::get('/fornecedores','FornecedorController@index')->name('app.fornecedores');    
    Route::get('/produtos',function (){ return 'produtos'; })->name('app.produtos');
});

Route::get('/teste/{p1}/{p2}','TesteController@teste')->name('teste');

//fallback (quando a pagina não existe) erro 404
Route::fallback(function ()
{
    echo "A rota acessada não existe. Volte para a <a href='".route('site.index')."'>pagina inicial</a>";
});


/*
    Route::get('/contato/{nome}/{categoria?}', function(string $nome, int $categoria = 0){
        echo "Estamos aqui: $nome <br>";
        echo "Categoria: $categoria <br>";
    })->where('nome','[A-Za-z]+')->where('categoria','[0-9]+');
*/