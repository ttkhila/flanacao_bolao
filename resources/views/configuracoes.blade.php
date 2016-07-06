@extends('layouts.principal')

@section('conteudo')
<h2>Configurações</h2>
<form method="POST" action="/configuracoes/salva">
  <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
  <table class="table table-responsive">
    <thead>
      <tr>
        <th width="70%">Descrição</th>
        <th width="15%">Valor atual</th>
        <th width="15%">Alteração</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Pontuação referente a acerto do resultado <strong>E</strong> do placar correto</td>
        <td>{{$config->pontuacao_placar_completo}}</td>
        <td><input class="input-number" type="number" name="txtPlacarCompleto" value="{{$config->pontuacao_placar_completo}}" required></td>
      </tr>
      <tr>
        <td>Pontuação referente a acerto do resultado <strong>SOMENTE</strong></td>
        <td>{{$config->pontuacao_resultado}}</td>
        <td><input class="input-number" type="number" name="txtResultado" value="{{$config->pontuacao_resultado}}" required></td>
      </tr>
      <tr>
        <td>Pontuação referente a acerto do placar de <strong>UM</strong> dos times somente</td>
        <td>{{$config->pontuacao_um_placar}}</td>
        <td><input class="input-number" type="number" name="txtUmPlacar" value="{{$config->pontuacao_um_placar}}" required></td>
      </tr>
      <tr>
        <td align="center" colspan="3">
          <button class="btn btn-primary" type="submit">Salvar</button>
        </td>
      </tr>
    </tbody>
  </table>
  @if(Session::has('msg'))
    <div class="alert alert-success"><center>{{ Session::get('msg') }}</center></div>
  @endif
</form>
@stop
