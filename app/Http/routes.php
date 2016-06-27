<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'JogoController@classificacao');
Route::get('/jogos/resultados', 'JogoController@lista');
Route::get('/jogos/resultados/salvar', 'JogoController@salvar_resultados');

Route::post('/jogos/novo', 'JogoController@novo');
Route::post('/jogos/edita', 'JogoController@editar');
Route::get('/jogos/cadastro', 'JogoController@cadastrar');
Route::get('/jogos/exclui', 'JogoController@excluir');
Route::get('/jogos/mudaLiberado', 'JogoController@mudarLiberado');

Route::get('/jogos/lista-palpites', 'JogoController@listarPalpites');
Route::get('/jogos/palpites/salvar', 'JogoController@salvarPalpites');
Route::get('/jogos/calcula', 'JogoController@calcularPontuacoes');

Route::get('/classificacao', 'JogoController@classificacao');

Route::get('/jogos', 'JogoController@palpites');

//Route::get('home', 'HomeController@index');
Route::controllers([
  'auth' => 'Auth\AuthController',
  'password' => 'Auth\PasswordController',
]);

Route::auth();

Route::get('/home', 'HomeController@index');
