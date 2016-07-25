<?php namespace flanacao\Http\Controllers;

use Illuminate\Support\Facades\DB;
use DateTime;
use DateTimeZone;
//use Illuminate\Support\Facades\Auth;
use Request;
use Redirect;

class JogoController extends Controller {

  function __construct(){
    $this->middleware('auth', 
      ['except' => ['classificacao']]);
  }

// ******************************************************************
  public function lista() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $jogos = DB::select("Select j.*, t1.nome as mandante, t2.nome as visitante FROM jogos j, times t1, times t2
      WHERE (j.time1_id = t1.id) AND (j.time2_id = t2.id) ORDER BY data_jogo desc");

    return view('jogos.lista')->withJogos($jogos);
  }

// ******************************************************************
  public function salvar_resultados() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $jogo = $_GET['j'];
    $pMandante = $_GET['pm'];
    $pVisitante = $_GET['pv'];

    if (!is_numeric($pMandante) || !is_numeric($pVisitante)){ echo "Entrar com placares válidos!"; exit; }

    DB::table('jogos')
            ->where('id', $jogo)
            ->update(['placar1' => $pMandante, 'placar2' => $pVisitante, 'liberado' => 0]);

    //Grava LOG
    $file = fopen('l.txt', "a");
    $user = \Auth::user()->login;
    date_default_timezone_set('America/Sao_Paulo');
    $dt = date('d / m / Y - H : i : s');
    $txt = "$dt - Usuario $user lançou resultado para o jogo $jogo. Placar: $pMandante x $pVisitante".PHP_EOL;
    fwrite($file, $txt);
    fclose($file);
    
    echo "1";
    exit;
  }

// ******************************************************************
  public function cadastrar() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

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
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $mandante = Request::input('mandante');
    $visitante = Request::input('visitante');
    $campeonato = Request::input('campeonato');
    $data_jogo = Request::input('data_jogo');
    $hora_jogo1 = Request::input('hora_jogo1');
    $hora_jogo2 = Request::input('hora_jogo2');
    $hora_jogo = $hora_jogo1.":".$hora_jogo2;

    if ($campeonato == "0") 
      return Redirect::back()->with('err', 'ERRO: Informar o campeonato.')->withInput(Request::all()); 

    if ($mandante === $visitante)
      return Redirect::back()->with('err', 'ERRO: Informar times diferentes.')->withInput(Request::all());

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
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $users = DB::select("SELECT id FROM users WHERE inscricao_liberada = 1");
    foreach ($users as $u) {
      DB::table('palpites')->insert(
        ['usuario_id' => $u->id, 'jogo_id' => $id]
      );
    }
  }

// ******************************************************************
  public function editar() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

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
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $jogo = $_GET['jogo'];
    $flag = $_GET['flag'];

    $flag = ($flag == 1) ? 0 : 1;
    DB::table('jogos')
      ->where('id', $jogo)
      ->update(['liberado' => $flag]);

    //Grava LOG
    $file = fopen('l.txt', "a");
    $user = \Auth::user()->login;
    date_default_timezone_set('America/Sao_Paulo');
    $dt = date('d / m / Y - H : i : s');
    $flag = ($flag == 1) ? "LIBERADO" : "BLOQUEADO";
    $txt = "$dt - Usuario $user alterou o jogo $jogo para $flag.".PHP_EOL;
    fwrite($file, $txt);
    fclose($file);
  }

// ******************************************************************
  public function excluir() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $jogo = $_GET['jogo'];
    DB::table('jogos')->where('id', '=', $jogo)->delete();
    DB::table('palpites')->where('jogo_id', '=', $jogo)->delete();

    //Grava LOG
    $file = fopen('l.txt', "a");
    $user = \Auth::user()->login;
    date_default_timezone_set('America/Sao_Paulo');
    $dt = date('d / m / Y - H : i : s');
    $txt = "$dt - Usuario $user excluiu o jogo $jogo.".PHP_EOL;
    fwrite($file, $txt);
    fclose($file);
  }

// ******************************************************************
  public function listarPalpites() {
    $user = \Auth::user()->id; 

    //verifica se os palpites não estão bloqueados
    $config = DB::table('configuracoes')->first();
    if ($config->bloquear_palpites == 1)
      return view('jogos.palpites')->with('bloq', "Palpites bloqueados no momento. Volte mais tarde!");

    //verifica se o usuário logado está ativo
    $at = DB::table('users')->where('id', $user)->first();
    if ($at->active == 0)
      return view('jogos.palpites')->with('inat', "Você está impedido de realizar palpites.");
    
    $jogos = DB::select("SELECT j.*, t1.nome as mandante, t1.arquivo as escudo1, t2.nome as visitante, t2.arquivo as escudo2, p.palpite_mandante, p.palpite_visitante  
      FROM jogos j, times t1, times t2, palpites p  
      WHERE (t1.id = j.time1_id) AND (t2.id = j.time2_id) AND (j.liberado = 1) AND (p.jogo_id = j.id) AND (p.usuario_id = $user) ORDER BY j.data_jogo");

    //palpites efetuados para o jogo atual
    $pal = $this->getJogo_mais_recente();

    // Dia e Hora do jogo mais próximo
    $dia_hora = DB::table('jogos')
      ->where('liberado', 1)
      ->orderBy('data_jogo', 'asc')
      ->first();

    return view('jogos.palpites')->with(['jogos' => $jogos, 'pal' => $pal, 'dia_hora' => $dia_hora]);
  }
// ******************************************************************
  public function visualizarPalpites() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $pal = $this->getJogo_mais_recente();
    return view('jogos.visualiza')->withPal($pal);
  }
// ******************************************************************
  private function getJogo_mais_recente() {
    $dados = DB::select("SELECT p.*, u.login, t1.nome as mandante, t2.nome as visitante, j.data_jogo, j.hora_jogo FROM palpites p, users u, jogos j, times t1, times t2 WHERE j.data_jogo = (SELECT min(data_jogo) FROM jogos WHERE liberado = 1) AND (p.usuario_id = u.id) AND (j.id = p.jogo_id) AND (p.palpite_mandante >= 0) AND (j.time1_id = t1.id) AND (j.time2_id = t2.id) ORDER BY j.data_jogo ASC, u.login");
    return $dados;
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

    // Verifica se o jogo está bloqueado
    $jog = DB::table('jogos')->where('id', $jogo)->first();
    if ($jog->liberado == 0) return "0";

    $at = DB::table('users')->where('id', $user)->first();
    if ($at->active == 0) return "0";

    if (!is_numeric($pMandante) || !is_numeric($pVisitante)){ echo "Entrar com placares válidos!"; exit; }

    DB::table('palpites')
            ->where(['jogo_id' => $jogo, 'usuario_id' => $user])
            ->update(['palpite_mandante' => $pMandante, 'palpite_visitante' => $pVisitante]);

    //Grava LOG
    $file = fopen('l.txt', "a");
    $user = \Auth::user()->login;
    date_default_timezone_set('America/Sao_Paulo');
    $dt = date('d / m / Y - H : i : s');
    $txt = "$dt - Usuario $user efetuou palpites para o jogo $jogo. Placar $pMandante x $pVisitante".PHP_EOL;
    fwrite($file, $txt);
    fclose($file);

    echo "1";
    exit;
  }

// ******************************************************************
  public function calcularPontuacoes() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

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

      //Grava LOG
      $file = fopen('l.txt', "a");
      $user = \Auth::user()->login;
      date_default_timezone_set('America/Sao_Paulo');
      $dt = date('d / m / Y - H : i : s');
      $txt = "$dt - Usuario $user executou o cálculo das pontuações.".PHP_EOL;
      fwrite($file, $txt);
      fclose($file);

      return redirect('/classificacao');
    }
  }

// ******************************************************************
  public function classificacao() {
    $pontuacoes = DB::select("SELECT * FROM pontuacoes"); 
    $clas = DB::select("SELECT SUM(p.pontuacao) as pontuacao, p.usuario_id, u.name, u.login FROM palpites p, users u WHERE (u.id = p.usuario_id) AND (u.active = 1) GROUP BY usuario_id ORDER BY pontuacao DESC");

    $ids = array();
    $extras = array();
    // soma a pontuação extra a pontuação normal
    //if (!empty($pontuacoes)) { // verifica se a tabela 'pontuacoes' está vazia
    foreach ($clas as $reg) {
      $id = $reg->usuario_id;
      $pont = DB::select("SELECT sum(pontuacao) as p FROM pontuacoes 
        WHERE usuario_id = $id");
        foreach ($pont as $value) { 
          if ($value->p != 'NULL')
            $reg->pontuacao += ($value->p); 
        }   

      //carrega os detalhamentos dos palpites
      $ids[$id] = DB::select("SELECT t1.nome as mandante, t2.nome as visitante, j.placar1, j.placar2, p.palpite_mandante, p.palpite_visitante, p.pontuacao 
        FROM times t1, times t2, palpites p, jogos j 
        WHERE (t1.id = j.time1_id) AND (t2.id = j.time2_id) AND (p.jogo_id = j.id) AND 
        (p.usuario_id = $id) AND (j.placar1 >= 0) AND (p.palpite_mandante >= 0) 
        ORDER BY j.data_jogo DESC");

      $extras[$id] = DB::select("SELECT * FROM pontuacoes WHERE usuario_id = $id");
    }

    usort($clas, function($a, $b) {
      return strcmp($b->pontuacao, $a->pontuacao);
    });
    //}

    return view('classificacao', ['clas' => $clas, 'ids' => $ids, 'extras' => $extras]);
  }

  // ******************************************************************
  public function listaOpcoes() {
    if (\Auth::user()->adm != 1) // não é ADM
      return redirect('/classificacao');

    $config = DB::table('configuracoes')->first();
    return view('jogos.bloqueio')->withConfig($config);
  }

  // ******************************************************************
  public function bloquearGeral($valor) {
    if(\Auth::guest())
      return redirect('/classificacao');

    if ($valor == 1) $valor = 0;
    else $valor = 1;
    DB::table('configuracoes')
      ->update(['bloquear_palpites' => $valor]);

    //Grava LOG
    $file = fopen('l.txt', "a");
    $user = \Auth::user()->login;
    date_default_timezone_set('America/Sao_Paulo');
    $dt = date('d / m / Y - H : i : s');
    $valor = ($valor == 1) ? "BLOQUEOU" : "LIBEROU";
    $txt = "$dt - Usuario $user $valor todos os palpites.".PHP_EOL;
    fwrite($file, $txt);
    fclose($file);

    return redirect('/classificacao');
  }

  public function sincronizar() {
    $now = new DateTime(); 
    return $now->format("M j, Y H:i:s +0000")."\n";
  }


}
