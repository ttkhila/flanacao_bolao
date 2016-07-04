@extends('layouts.principal')

@section('conteudo')
<h2>Cadastramento de Campeonatos</h2>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Formulário</h3>
  </div>
  <div class="panel-body">
    <form id="frmCadastroCampeonato" method="POST" action="/campeonatos/novo">
      <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
      <div class="form-group">
        <label>Nome</label>
        <input class="form-control" type="text" name="nome" required maxlength="60" value="{{old('nome')}}">
      </div>
      <button class="btn btn-primary" type="submit">Cadastrar</button>
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
    <h3 class="panel-title">Lista de Campeonatos Cadastrados</h3>
  </div>
  <div class="panel-body">
    @if(empty($campeonatos))
      <center><div class="alert alert-danger">Não há campeonatos cadastrados!</div></center>
    @else
      <h4>
        <span class="label label-success">Campeonatos ativos.</span>
        <span class="label label-danger">Campeonatos inativos.</span>
      </h4>
      <table class="table table-bordered table-responsive">
        <thead>
          <tr>
            <th width="30%">Nome</th>
            <th width="60%">Ativar / Desativar</th>
          </tr>
        </thead>
        <tbody>
          @foreach($campeonatos as $c)
            <tr class="{{($c->ativo == 0) ? 'danger' : 'success'}}">
              <td>{{ $c->nome }}</td>
              <td>
                @if($c->ativo == 0)
                  <button class="btn btn-sm btn-success" name="btn-ativar-camp_{{$c->id}}" data-valor="1">Ativar</button>
                @else
                  <button class="btn btn-sm btn-danger" name="btn-ativar-camp_{{$c->id}}" data-valor="0">Desativar</button>
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