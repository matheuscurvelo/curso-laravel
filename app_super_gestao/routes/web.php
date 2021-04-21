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

Route::get('/', 'PrincipalController@principal');
Route::get('/sobrenos', 'SobreNosController@sobrenos');
Route::get('/contato', 'ContatoController@contato');

Route::get('/contato/{nome}/{categoria?}', function(string $nome, int $categoria = 0){
    echo "Estamos aqui: $nome <br>";
    echo "Categoria: $categoria <br>";
})->where('nome','[A-Za-z]+')->where('categoria','[0-9]+');