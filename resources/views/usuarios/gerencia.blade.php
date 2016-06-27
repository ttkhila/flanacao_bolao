@extends('layouts.principal')

@section('conteudo')
<h2>Gerenciar Usuários</h2>
<div class="panel panel-warning">
  <div class="panel-heading">
    <h3 class="panel-title">Usuários Pendentes de Aprovação</h3>
  </div>
  <div class="panel-body">
    @if(empty($usu_pend))
      <center>
        <div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span> Não há usuários pendentes de aprovação</div>
      </center>
    @else
      <table class="table table-striped table-responsive">
        <thead>
          <tr>
            <th width="20%">Apelido</th>
            <th width="40%">Nome</th>
            <th width="40%">Ação</th>
          </tr>
        </thead>
        <tbody>
          @foreach($usu_pend as $u1)
            <tr>
              <td>{{ $u1->login }}</td>
              <td>{{ $u1->name }}</td>
              <td>
                <a role='button' class='btn btn-success' name='btn-aprova_{{$u1->id}}' data-valor='1'>Aprovar</a>
                <a role='button' class='btn btn-danger' name='btn-aprova_{{$u1->id}}' data-valor='0'>Reprovar</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Lista de usuários</h3>
  </div>
  <div class="panel-body">
    @if(empty($usu_total))
      <center>
        <div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span> Não há usuários cadastrados</div>
      </center>
    @else
      <table class="table table-striped table-responsive">
        <thead>
          <tr>
            <th width="15%">Apelido</th>
            <th width="28%">Nome</th>
            <th width="12%">Celular</th>
            <th width="25%">E-mail</th>
            <th width="20%">Ação</th>
          </tr>
        </thead>
        <tbody>
          @foreach($usu_total as $u2)
            <tr>
              <td>{{ $u2->login }}</td>
              <td>{{ $u2->name }}</td>
              <td>{{ $u2->cell }}</td>
              <td>{{ $u2->email }}</td>
              <td>
                @if($u2->active == 1)
                  <a role="button" class="btn-sm btn-danger" href="/usuarios/ativa/{{$u2->id}}/0">Inativar</a>
                @else
                  <a role="button" class="btn-sm btn-success" href="/usuarios/ativa/{{$u2->id}}/1">Ativar</a>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</div>

@stop
