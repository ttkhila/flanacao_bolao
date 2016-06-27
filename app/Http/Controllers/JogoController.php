<?php namespace flanacao\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Request;
use Redirect;

class JogoController extends Controller {

  function __construct(){
    # code...
  }

// ******************************************************************
  public function lista() {
    $jogos = DB::select("Select j.*, t1.nome as mandante, t2.nome as visitante FROM jogos j, times t1, times t2
      WHERE (j.time1_id = t1.id) AND (j.time2_id = t2.id) ORDER BY data_jogo desc");

    return view('jogos.lista')->withJogos($jogos);
  }

// ******************************************************************
  public function salvar_resultados() {
    $jogo = $_GET['j'];
    $pMandante = $_GET['pm'];
    $pVisitante = $_GET['pv'];

    if (!is_numeric($pMandante) || !is_numeric($pVisitante)){ echo "Entrar com placares válidos!"; exit; }

    DB::table('jogos')
            ->where('id', $jogo)
            ->update(['placar1' => $pMandante, 'placar2' => $pVisitante, 'liberado' => 0]);
    
    echo "1";
    exit;
  }

// ******************************************************************
  public function cadastrar() {
    $times = DB::select("Select * FROM times WHERE ativo = 1");
    $campeonatos = DB::select("Select * FROM campeonatos WHERE ativo = 1 ORDER BY nome");

    $jogos = DB::select("SELECT t1.nome as mandante, t2.nome as visitante, c.nome as campeonato, j.* 
      FROM jogos j, times t1, times t2, campeonatos c 
      WHERE (t1.id = j.time1_id) AND (t2.id = j.time2_id) AND (c.id = j.campeonato_id) 
      ORDER BY j.data_jogo desc");

    return view('jogos.cadastro', ['times' => $times, 'campeonatos' => $campeonatos, 'jogos' => $jogos]);
  }

// ******************************************************************
  public function novo() {
    $mandante = Request::input('mandante');
    $visitante = Request::input('visitante');
    $campeonato = Request::input('campeonato');
    $data_jogo = Request::input('data_jogo');
    $hora_jogo1 = Request::input('hora_jogo1');
    $hora_jogo2 = Request::input('hora_jogo2');
    $hora_jogo = $hora_jogo1.":".$hora_jogo2;

    if ($campeonato == "0") 
      return Redirect::back()->with('err', 'ERRO: Informar o campeonato.'); 

    if ($mandante === $visitante)
      return Redirect::back()->with('err', 'ERRO: Informar times diferentes.');

    $id = DB::table('jogos')->insertGetId(
      [
        'time1_id' => $mandante, 'time2_id' => $visitante, 'data_jogo' => $data_jogo, 
        'hora_jogo' => $hora_jogo, 'campeonato_id' => $campeonato
      ]);

    //cria registros na tabela PALPITES
    $this->cria_registros($id);
    
    return Redirect::back()->with('msg', 'SUCESSO! Jogo cadastrado.');
  }

// ******************************************************************
  private function cria_registros($id) {
    $users = DB::select("SELECT id FROM users WHERE inscricao_liberada = 1");
    foreach ($users as $u) {
      DB::table('palpites')->insert(
        ['usuario_id' => $u->id, 'jogo_id' => $id]
      );
    }
  }

// ******************************************************************
  public function editar() {
    $mandante = Request::input('mandante');
    $visitante = Request::input('visitante');
    $campeonato = Request::input('campeonato');
    $data_jogo = Request::input('data_jogo');
    $hora_jogo1 = Request::input('hora_jogo1');
    $hora_jogo2 = Request::input('hora_jogo2');
    $idJogo = Request::input('hidIdJogo');
    $hora_jogo = $hora_jogo1.":".$hora_jogo2;

    if ($campeonato == "0") 
      return Redirect::back()->with('err', 'ERRO: Campeonato não informado.
        Selecione novamente o jogo para edição.'); 

    if ($mandante === $visitante)
      return Redirect::back()->with('err', 'ERRO: Os times são iguais. 
        Selecione novamente o jogo para edição.');

    DB::table('jogos')
            ->where('id', $idJogo)
            ->update(['time1_id' => $mandante, 
                      'time2_id' => $visitante, 
                      'campeonato_id' => $campeonato,
                      'data_jogo' => $data_jogo,
                      'hora_jogo' => $hora_jogo
                      ]);
    
    return Redirect::back()->with('msg', 'SUCESSO! Jogo alterado.');
  } 

// ******************************************************************
  public function mudarLiberado() {
    $jogo = $_GET['jogo'];
    $flag = $_GET['flag'];

    $flag = ($flag == 1) ? 0 : 1;
    DB::table('jogos')
      ->where('id', $jogo)
      ->update(['liberado' => $flag]);
  }

// ******************************************************************
  public function excluir() {
    $jogo = $_GET['jogo'];
    DB::table('jogos')->where('id', '=', $jogo)->delete();
    DB::table('palpites')->where('jogo_id', '=', $jogo)->delete();
  }

// ******************************************************************
  public function listarPalpites() {
    $user = 1; //pegar esse valor da sessão

    //verifica se os palpites não estão bloqueados
    $config = DB::table('configuracoes')->first();
    if ($config->bloquear_palpites == 1)
      return ("Palpites bloqueados no momento.<br />
        Volte mais tarde!<br /><a href='/'>voltar</a>");

    //verifica se o usuário logado está ativo
    $at = DB::table('users')->where('id', $user)->first();
    if ($at->active == 0)
      return ("Você está impedido de realizar palpites.<br />
        Entre em contato com o administrador do bolão. <br /><a href='/'>voltar</a>");
    
    $jogos = DB::select("SELECT j.*, t1.nome as mandante, t2.nome as visitante, p.palpite_mandante, p.palpite_visitante  
      FROM jogos j, times t1, times t2, palpites p  
      WHERE (t1.id = j.time1_id) AND (t2.id = j.time2_id) AND (j.liberado = 1) AND (p.jogo_id = j.id) AND (p.usuario_id = $user)");

    return view('jogos.palpites')->withJogos($jogos);
  }

// ******************************************************************
  public function salvarPalpites() {
    $jogo = $_GET['j'];
    $user = $_GET['u'];
    $pMandante = $_GET['pm'];
    $pVisitante = $_GET['pv'];

    //Faz verificações pra saber se não houve bloqueio antes dos palpites
    $config = DB::table('configuracoes')->first();
    if ($config->bloquear_palpites == 1) return "0";

    $at = DB::table('users')->where('id', $user)->first();
    if ($at->active == 0) return "0";

    if (!is_numeric($pMandante) || !is_numeric($pVisitante)){ echo "Entrar com placares válidos!"; exit; }

    DB::table('palpites')
            ->where(['jogo_id' => $jogo, 'usuario_id' => $user])
            ->update(['palpite_mandante' => $pMandante, 'palpite_visitante' => $pVisitante]);
    echo "1";
    exit;
  }

// ******************************************************************
  public function calcularPontuacoes() {
    $config = DB::table('configuracoes')->first();
    $placarCompleto = $config->pontuacao_placar_completo;
    $resultado = $config->pontuacao_resultado;
    $umPlacar = $config->pontuacao_um_placar;

    $palpites = DB::select("SELECT p.id, p.palpite_mandante, p.palpite_visitante, j.placar1, j.placar2
      FROM palpites p, jogos j 
      WHERE (p.jogo_id = j.id) AND (p.palpite_mandante >= 0) AND (j.placar1 >= 0)");
    if (!empty($palpites)) {
      foreach ($palpites as $p) {
        $pontos = 0;
        $id = $p->id;
        $pm = $p->palpite_mandante;
        $pv = $p->palpite_visitante;
        $p1 = $p->placar1;
        $p2 = $p->placar2;

        if ($p1 == $pm && $p2 == $pv) //Acertou resultado e placar - finaliza
          $pontos = $placarCompleto;
        else {
          if ($p1 > $p2) { //vitoria mandante
            if ($pm > $pv) { // acertou resultado
              $pontos = $resultado;
            }
          }

          if ($p2 > $p1) { //vitoria visitante
            if ($pv > $pm) { // acertou resultado
              $pontos = $resultado;
            }
          }

          if ($p1 == $p2) { // empate
            if ($pm == $pv) { // acertou resultado
              $pontos = $resultado;
            }
          }

          //verifica se acertou apenas um dos placares
          if ($p1 == $pm || $p2 == $pv) {
            $pontos += $umPlacar;
          }
        }

        DB::table('palpites')
            ->where('id', $id)
            ->update(['pontuacao' => $pontos]);
      }
      return redirect('/classificacao');
    }
  }

// ******************************************************************
  public function classificacao() {
    $clas = DB::select("SELECT SUM(p.pontuacao) as pontuacao, p.usuario_id, u.name FROM palpites p, users u WHERE (u.id = p.usuario_id) GROUP BY usuario_id");

    return view('classificacao', ['clas' => $clas]);
  }





}
