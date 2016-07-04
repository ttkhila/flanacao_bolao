@extends('layouts.principal')

@section('conteudo')
<h2>Bloqueio / Desbloqueio dos Palpites</h2>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Status atual do Bloqueio</h3>
  </div>
  <div class="panel-body">
    @if($config->bloquear_palpites == 1)
      <div class="alert alert-danger">BLOQUEADOS</div>
      <center><a role="button" class="btn btn-lg btn-success" href="/jogos/mudaBloq/{{$config->bloquear_palpites}}">Desbloquear todos os palpites</a></center>
    @else
      <div class="alert alert-success">DESBLOQUEADOS</div>
      <center><a role="button" class="btn btn-lg btn-danger" href="/jogos/mudaBloq/{{$config->bloquear_palpites}}">Bloquear todos os palpites</a></center>
    @endif
  </div>
</div>
@stop
