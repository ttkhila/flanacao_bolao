@extends('layouts.principal')

@section('conteudo')
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Cadastro de Times</h3>
  </div>
  <div class="panel-body">
    <form action="/times/upload" method="post" enctype="multipart/form-data">
      <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
      <div class="form-group">
        <label>Nome</label>
        <input class="form-control" type="text" name="nome_time" required>
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
      
      <input type="submit" class="btn btn-primary">
    </form>
    @if (Session::has("message"))
      <center><span class="alert alert-info">{{ Session::get("message") }}</span></center>
    @endif
    @if (Session::has("erro"))
      <center><span class="alert alert-danger">{{ Session::get("erro") }}</span></center>
    @endif
  </div>
</div>
@stop