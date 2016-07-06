@extends('layouts.principal')

@section('conteudo')

<?php
  if ($dia_hora) {
    $hora_jogo = explode(":", $dia_hora->hora_jogo);
    $hora_jogo[0] = intval($hora_jogo[0]) - 1;
    $hora_jogo = implode(":", $hora_jogo);
    $format = str_replace("-", "/", $dia_hora->data_jogo)." ".$hora_jogo;
  }
?>
<h2>Palpites</h2>
@if(empty($jogos))
  <center><span class="alert alert-danger">Não existem palpites a serem efetuados. Volte em outro momento.</span></center>
@else
  
  <input type="hidden" id="dia_hora" value="<?php echo $format.':00'; ?>">
  <div class="countdown">Tempo restante até o bloqueio dos palpites da rodada:<br /><span id="clock"></span></div>

  <table class="table table-striped table-responsive">
    <thead>
      <tr>
        <th width="15%">Data:</th>
        <th width="15%">Hora:</th>
        <th width="18%">Mandante</th>
        <th width="2%"></th>
        <th width="2%">Palpite</th>
        <th width="2%">x</th>
        <th width="2%">Palpite</th>
        <th width="2%"></th>
        <th width="23%">Visitante</th>
        <th width="19%">Salvar</th>
      </tr>
    </thead>
    <tbody>
      <?php $cont = 1; ?>
      @foreach($jogos as $j)
        <tr>
          <td>{{ date('d/m/Y', strtotime($j->data_jogo)) }}</td>
          <td>{{ $j->hora_jogo }}</td>
          <td align="right">{{ $j->mandante }}</td>
          <td align="center"><img src="/img/upload/{{$j->escudo1}}" width="20" height="20"></td>
          <td><input type="number" name="pMandante{{$j->id}}" min="0" max="10" value="{{$j->palpite_mandante}}" style="text-align:center"></td>
          <td>x</td>
          <td><input type="number" name="pVisitante{{$j->id}}" min="0" max="10" value="{{$j->palpite_visitante}}" style="text-align:center"></td>
          <td align="center"><img src="/img/upload/{{$j->escudo2}}" width="20" height="20"></td>
          <td>{{ $j->visitante }}</td>
          <td>
            <button name="palpiteJogo_{{$j->id}}" class="btn btn-sm btn-primary">Salvar</button>
            @if($cont == 1 AND !empty($pal))
              <button name="verPalpites" class="btn btn-sm btn-success">Outros palpites</button>
            @endif
          </td>
        </tr>

        @if($cont == 1 AND !empty($pal))
          <tr name="tr-outros-palpites" style="display:none;">
            <td colspan="10">
              @foreach($pal as $p)
                <span class="sp-inline">{{ $p->login }}</span>
                {{ $j->mandante }}
                <strong>{{ $p->palpite_mandante }}</strong>
                 x 
                <strong>{{ $p->palpite_visitante }}</strong>
                {{ $j->visitante }}<br />  
              @endforeach
              <br /> <br /> 
            </td>
          </tr>
        @endif
        <?php $cont++; ?>
      @endforeach
    </tbody>
  </table>
  <input type="hidden" name="hid-user" value="{{Auth::user()->id}}">
@endif
@stop
