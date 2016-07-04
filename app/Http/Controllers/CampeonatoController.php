<?php namespace flanacao\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Request;
use Redirect;

class CampeonatoController extends Controller {

  function __construct(){
    $this->middleware('auth');
  }

// ******************************************************************
  public function lista() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $campeonatos = DB::select("SELECT * FROM campeonatos ORDER BY nome");

    return view('campeonatos.cadastro')->withCampeonatos($campeonatos);
  }

// ******************************************************************
  public function novo() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $nome = Request::input('nome');

    if ($nome == "") 
      return Redirect::back()->with('erro', 'ERRO: Informar o campeonato.')->withInput(Request::all()); 

    $id = DB::table('campeonatos')->insert(
      [
        'nome' => $nome
      ]);
    
    return Redirect::back()->with('message', 'SUCESSO! Campeonato cadastrado.');
  }

  // ******************************************************************
  public function ativar() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');
      
    $valor = $_GET['v'];
    $id = $_GET['i'];

    DB::table('campeonatos')
        ->where('id', $id)
        ->update(['ativo' => $valor]);

    return $valor;
  }





}
