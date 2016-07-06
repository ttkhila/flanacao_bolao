<?php namespace flanacao\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Request;
use Response;
use Redirect;

class ConfigController extends Controller {

    function __construct(){
        $this->middleware('auth');
    }

// ******************************************************************
  public function dados() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $config = DB::table('configuracoes')->first();
    return view('configuracoes')->withConfig($config);
  }

// ******************************************************************
  public function salvar() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $pCompleto = Request::input('txtPlacarCompleto');
    $resultado = Request::input('txtResultado');
    $umPlacar = Request::input('txtUmPlacar');
    DB::table('configuracoes')
        ->update(['pontuacao_placar_completo' => $pCompleto, 'pontuacao_resultado' => $resultado, 'pontuacao_um_placar' => $umPlacar]);
    return Redirect::back()->with('msg', 'Configurações salvas!');
  }




}
