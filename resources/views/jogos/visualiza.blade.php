@extends('layouts.principal')

@section('conteudo')

<h2>Visualizar Palpites do Jogo Atual</h2>
@if(empty($pal))
  <center>
    <div class="alert alert-danger">Não há dados a serem mostrados.<br /><a href="/">Ir para classificação</a></div>
  </center>
@else
  <center>
    <div class="alert alert-info">
      <?php echo "Jogo Atual: ".$pal[0]->mandante." x ".$pal[0]->visitante." em ".date('d/m/Y', strtotime($pal[0]->data_jogo)). " - ".$pal[0]->hora_jogo; ?>
    </div>
  </center>
  <table class="table table-nonfluid table-responsive table-striped">
    <thead>
      <tr>
        <th width="70%">Apelido</th>
        <th width="30%">Palpite</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pal as $p)
        <tr>
          <td>{{ $p->login }}</td>
          <td>{{ $p->palpite_mandante }} x {{ $p->palpite_visitante }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif
@stop
