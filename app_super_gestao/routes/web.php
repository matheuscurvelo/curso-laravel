<?php

use App\Http\Middleware\LogAcessoMiddleware;
use App\PedidoProduto;
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
Route::post('/contato', 'ContatoController@salvar')->name('site.contato');

Route::get('/login/{erro?}','LoginController@index')->name('site.login');
Route::post('/login','LoginController@autenticar')->name('site.login');


Route::prefix('/app')->middleware('autenticacao:padrao,visitante,p3,p4')->group(function(){
    Route::get('/home','HomeController@index')->name('app.home');
    Route::get('/sair','LoginController@sair')->name('app.sair');


    Route::get('/fornecedor','FornecedorController@index')->name('app.fornecedor'); 
    Route::post('/fornecedor/listar','FornecedorController@listar')->name('app.fornecedor.listar'); 
    Route::get('/fornecedor/listar','FornecedorController@listar')->name('app.fornecedor.listar'); 
    Route::get('/fornecedor/adicionar','FornecedorController@adicionar')->name('app.fornecedor.adicionar'); 
    Route::post('/fornecedor/adicionar','FornecedorController@adicionar')->name('app.fornecedor.adicionar'); 
    Route::get('/fornecedor/editar/{id}/{msg?}','FornecedorController@editar')->name('app.fornecedor.editar'); 
    Route::get('/fornecedor/excluir/{id}','FornecedorController@excluir')->name('app.fornecedor.excluir'); 

    Route::resource('produto', 'ProdutoController');
    Route::resource("produto-detalhe","ProdutoDetalheController");

    Route::resource('cliente', ClienteController::class);
    Route::resource('pedido', PedidoController::class);
    Route::get('pedido-produto/create/{pedido_id}','PedidoProdutoController@create')->name('pedido-produto.create');
    Route::post('pedido-produto/store/{pedido_id}','PedidoProdutoController@store')->name('pedido-produto.store');
    //Route::delete('pedido-produto/delete/{pedido_id}/{produto_id}','PedidoProdutoController@destroy')->name('pedido-produto.destroy');
    Route::delete('pedido-produto/delete/{pedido_produto_id}/{pedido_id}','PedidoProdutoController@destroy')->name('pedido-produto.destroy');
});

Route::get('/teste/{p1}/{p2}','TesteController@teste')->name('teste');

//fallback (quando a pagina não existe) erro 404
Route::fallback(function ()
{
    echo "A rota acessada não existe. Volte para a <a href='".route('site.index')."'>pagina inicial</a>";
});