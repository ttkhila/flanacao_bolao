@extends('layouts.principal')

@section('conteudo')
<h2>Lançamentos de Placares</h2>
<table class="table table-striped table-responsive">
  <thead>
    <tr>
      <th width="2%">Status</th>
      <th width="18%">Data:</th>
      <th width="22%">Mandante</th>
      <th width="2%">Placar</th>
      <th width="2%">x</th>
      <th width="2%">Placar</th>
      <th width="24%">Visitante</th>
      <th width="28%">Ação</th>
    </tr>
  </thead>
  <tbody>
    @foreach($jogos as $j)
      <tr>
        <td>
          @if($j->liberado == 0)
            <div class="label label-danger"><span class="glyphicon glyphicon-lock" title="Palpite Bloqueado (dependente do bloqueio geral)"></span></div>
          @else
            <div class="label label-success"><span class="glyphicon glyphicon-ok-sign" title="Palpite Liberado (dependente do bloqueio geral)"></span></div>
          @endif
        </td>
        <td>{{ date('d/m/Y', strtotime($j->data_jogo)) }} - {{ $j->hora_jogo }}</td>
        <td align="right">{{ $j->mandante }}</td>
        <td><input type="number" name="pMandante{{$j->id}}" min="0" max="10" value="{{$j->placar1}}" style="text-align:center"></td>
        <td>x</td>
        <td><input type="number" name="pVisitante{{$j->id}}" min="0" max="10" value="{{$j->placar2}}" style="text-align:center"></td>
        <td>{{ $j->visitante }}</td>
        <td>
          <button name="jogo_{{$j->id}}" class="btn btn-sm btn-primary">Salvar</button>
          <span id="alert{{$j->id}}" class="label label-danger" style="display:none;"></span>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
<center><a role="button" href="/jogos/calcula" class="btn btn-primary" id="btn-calcular">Calcular Pontuações</a></center>
@stop
