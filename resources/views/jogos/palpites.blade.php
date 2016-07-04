@extends('layouts.principal')

@section('conteudo')
<h2>Palpites</h2>
@if(empty($jogos))
  <center><span class="alert alert-danger">Não existem palpites a serem efetuados. Volte em outro momento.</span></center>
@else
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
            <span id="alert{{$j->id}}" class="label label-danger" style="display:none;"></span>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <input type="hidden" name="hid-user" value="{{Auth::user()->id}}">
@endif
@stop
