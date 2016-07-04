@extends('layouts.principal')

@section('conteudo')
<h2>Cadastramento de Times</h2>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Formulário</h3>
  </div>
  <div class="panel-body">
    <form action="/times/upload" method="post" enctype="multipart/form-data">
      <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
      <div class="form-group">
        <label>Nome</label>
        <input class="form-control" type="text" name="nome_time" maxlength="60" required>
      </div>

      <div class="form-group">
        <label>Nacionalidade</label>
        <select class="form-control" name="nacionalidade">
          <option value="BRA" selected="selected">Brasil</option>
          <option value="ARG">Argetina</option>
        </select>
      </div>

      <div class="form-group">
        <label>Escudo (Imagem de tamanho máximo 100x100 pixels e de preferência em formato quadrado, ou seja, mesma altura e largura)</label>
        <input class="form-control btn" type="file" name="file">
      </div>
      
      <input type="submit" class="btn btn-primary" value="Cadastrar">
    </form>
    @if (Session::has("message"))
      <center><span class="alert alert-info">{{ Session::get("message") }}</span></center>
    @endif
    @if (Session::has("erro"))
      <center><span class="alert alert-danger">{{ Session::get("erro") }}</span></center>
    @endif
  </div>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title">Lista de Times Cadastrados</h3>
  </div>
  <div class="panel-body">
    @if(empty($times))
      <center><div class="alert alert-danger">Não há clubes cadastrados!</div></center>
    @else
      <h4>
        <span class="label label-success">Times ativos.</span>
        <span class="label label-danger">Times inativos.</span>
      </h4>
      <table class="table table-bordered table-responsive">
        <thead>
          <tr>
            <th width="30%">Nome</th>
            <th width="10%">Nacionalidade</th>
            <th width="60%">Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach($times as $t)
          <tr class="{{($t->ativo == 0) ? 'danger' : 'success'}}">
            <td>{{ $t->nome }}</td>
            <td>{{ $t->nacionalidade }}</td>
            <td>
              @if($t->ativo == 0)
                <button class="btn btn-sm btn-success" name="btn-ativar_{{$t->id}}" data-valor="1">Ativar</button>
              @else
                <button class="btn btn-sm btn-danger" name="btn-ativar_{{$t->id}}" data-valor="0">Desativar</button>
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