@extends('layouts.principal')

@section('conteudo')
<h2>Cadastramento de Jogos</h2>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Formulário de Cadastro</h3>
  </div>
  <div class="panel-body">

    <form id="frmCadastroJogo" method="POST" action="/jogos/novo">
      <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
      <div class="form-group">
        <label>Time Mandante</label>
        <select class="form-control" name="mandante">
          @foreach($times as $t)
            @if(old('mandante') AND $t->id == old('mandante'))
              <option value="{{old('mandante')}}" selected='selected'>{{$t->nome}}</option>
            @else
              <option value="{{$t->id}}" {{ ($t->id == 1) ? "selected='selected'" : "" }}>{{$t->nome}}</option>
            @endif
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Time Visitante</label>
        <select class="form-control" name="visitante">
          @foreach($times as $t)
            @if(old('visitante') AND $t->id == old('visitante'))
              <option value="{{old('visitante')}}" selected='selected'>{{$t->nome}}</option>
            @else
              <option value="{{$t->id}}" {{ ($t->id == 1) ? "selected='selected'" : "" }}>{{$t->nome}}</option>
            @endif
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Campeonato</label>
        <select class="form-control" name="campeonato" required>
            <option value="0"></option>
          @foreach($campeonatos as $c)
            @if(old('campeonato') AND $c->id == old('campeonato'))
              <option value="{{old('campeonato')}}" selected="selected">{{$c->nome}}</option>
            @else
              <option value="{{$c->id}}">{{$c->nome}}</option>
            @endif
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Data do jogo</label>
        <input class="form-control" type="date" name="data_jogo" value="{{ old('data_jogo') }}" required>
      </div>

      <div class="form-group">
        <label>Hora do jogo</label><br />
        <input type="number" name="hora_jogo1" min="0" max="23" value="{{ old('hora_jogo1') }}"> hs 
        <input type="number" name="hora_jogo2" min="00" max="59" value="{{ old('hora_jogo2') }}"> min
      </div>

      <button class="btn btn-primary form-control" type="submit">Cadastrar</button><br /><br />
      <a href="/jogos/cadastro" class="btn btn-success" style="display:none;">Limpar</a>
      <input type='hidden' name="hidIdJogo">
    </form>

    @if(Session::has('err'))
      <div class="alert alert-danger">{{ Session::get('err') }}</div>
    @endif

    @if(Session::has('msg'))
      <div class="alert alert-success">{{ Session::get('msg') }}</div>
    @endif

  </div>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title">Lista de Jogos Cadastrados</h3>
  </div>
  <div class="panel-body">
    <h4>
      <span class="label label-success">Jogos Liberados para palpites.</span>
      <span class="label label-danger">Jogos Bloqueados para palpites.</span>
    </h4>
    <table class="table table-bordered table-responsive" style="font-weight:bold;">
      <thead>
        <tr>
          <th width="10%">Data</th>
          <th width="8%">Hora</th>
          <th width="16%">Mandante</th>
          <th width="2%"></th>
          <th width="16%">Visitante</th>
          <th width="23%">Campeonato</th>
          <th width="25%">Editar :: liberar/bloquear palpites</th>
        </tr>
      </thead>
      <tbody>
        @foreach($jogos as $j)
        <tr class="{{($j->liberado == 0) ? 'danger' : 'success'}}">
          <td>{{ date('d/m/Y', strtotime($j->data_jogo)) }}</td>
          <td>{{ $j->hora_jogo }} hs</td>
          <td align="right">{{ $j->mandante }}</td>
          <td align="center">x</td>
          <td>{{ $j->visitante }}</td>
          <td>{{ $j->campeonato }}</td>
          <td>
            <button class="btn btn-xs btn-primary glyphicon glyphicon-edit" name="btnAltera-jogo_{{$j->id}}"> Editar</button>
            @if($j->liberado == 0)
              <button class="btn btn-xs btn-success glyphicon glyphicon-folder-open" name="btnLibera-jogo_{{$j->id}}" data-liberado="{{$j->liberado}}"> Liberar</button>
            @else
              <button class="btn btn-xs btn-warning glyphicon glyphicon-lock" name="btnLibera-jogo_{{$j->id}}" data-liberado="{{$j->liberado}}"> Bloquear</button>
            @endif
            <button class="btn btn-xs btn-danger glyphicon glyphicon-remove" name="btnExclui-jogo_{{$j->id}}"> Excluir</button>
            <span id="alert{{$j->id}}" class="label label-danger" style="display:none;"></span>

            <input type="hidden" name="hidTime1" value="{{$j->time1_id}}">
            <input type="hidden" name="hidTime2" value="{{$j->time2_id}}">
            <input type="hidden" name="hidCampeonato" value="{{$j->campeonato_id}}">
            <input type="hidden" name="hidHora" value="{{$j->hora_jogo}}">
            <input type="hidden" name="hidData" value="{{$j->data_jogo}}">
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@stop
