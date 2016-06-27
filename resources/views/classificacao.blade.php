@extends('layouts.principal')

@section('conteudo')
<h2>Classificação</h2>
<table class="table table-striped table-responsive">
  <thead>
    <tr>
      <th width="10%">Ordem</th>
      <th width="60%">Jogador</th>
      <th width="30%">Pontuação</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 1; ?>
    @foreach($clas as $c)
      <tr>
        <td><?php echo $cont; ?></td>
        <td>{{$c->name}}</td>
        <td>{{$c->pontuacao}}</td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
@stop
