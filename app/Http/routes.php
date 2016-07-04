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

//JOGOS
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

Route::get('/jogos/bloqueio', 'JogoController@listaOpcoes');
Route::get('/jogos/mudaBloq/{valor}', 'JogoController@bloquearGeral');

Route::get('/classificacao', 'JogoController@classificacao');
Route::get('/jogos', 'JogoController@palpites');

//USUARIOS
Route::get('/usuarios/gerencia', 'UsuarioController@lista');
Route::get('/usuarios/aprovacao', 'UsuarioController@aprovar');
Route::get('/usuarios/pontos', 'UsuarioController@lancarPontos');
Route::get('/usuarios/ativa/{u}/{f}', 'UsuarioController@ativar')->where(['u' => '[0-9]+', 'f' => '[0-1]+']);

//TIMES
Route::get('/times/cadastro', 'TimeController@lista');
Route::get('/times/ativacao', 'TimeController@ativar');
Route::post('/times/upload', 'TimeController@uploadFiles');

//campeonatos
Route::get('/campeonatos/gerencia', 'CampeonatoController@lista');
Route::post('/campeonatos/novo', 'CampeonatoController@novo');
Route::get('/campeonatos/ativacao', 'CampeonatoController@ativar');

//Route::get('home', 'HomeController@index');
Route::controllers([	
  'auth' => 'Auth\AuthController',
  'password' => 'Auth\PasswordController',
]);

Route::auth();

Route::get('/home', 'HomeController@index');
