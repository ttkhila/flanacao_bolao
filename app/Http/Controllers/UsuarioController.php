<?php namespace flanacao\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Request;
use Redirect;

class UsuarioController extends Controller {

  function __construct(){
      $this->middleware('auth');
  }

// ******************************************************************
  public function lista() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $usu_pend = DB::select("Select * FROM users WHERE inscricao_liberada = 0 ORDER BY login");
    $usu_total = DB::select("Select * FROM users ORDER BY login");

    return view('usuarios.gerencia')->with(['usu_pend' => $usu_pend, 'usu_total' => $usu_total]);
  }

// ******************************************************************
  public function aprovar() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $valor = $_GET['v'];
    $id = $_GET['i'];

    if ($valor == 1) { //aprovado
      DB::table('users')
      ->where('id', $id)
      ->update(['inscricao_liberada' => $valor]);

      // cria registros de palpites para os jogos cadastrados
      $jogos = DB::select('SELECT id FROM jogos');
      foreach ($jogos as $j) {
        $idJogo = $j->id;
        DB::table('palpites')->insert(
          ['usuario_id' => $id, 'jogo_id' => $idJogo]
        );
      }
      return "Inscrição liberada!";

    } else { //recusado
      DB::table('users')->where('id', '=', $id)->delete();
      return "Usuário excluído!";
    }
  }

// ******************************************************************
  public function ativar($u, $f) {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    DB::table('users')
      ->where('id', $u)
      ->update(['active' => $f]);

    return redirect('/usuarios/gerencia');
  }

// ******************************************************************
  public function lancarPontos() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');
      
    $id = $_GET['id'];
    $valor = intval($_GET['v']);
    $motivo = $_GET['m'];
    $dt = date('Y-m-d');

    DB::table('pontuacoes')->insert(
      ['usuario_id' => $id, 'pontuacao' => $valor, 'data' => $dt, 'motivo' => $motivo]
    );

  }



}
