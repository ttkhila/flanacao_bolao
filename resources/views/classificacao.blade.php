@extends('layouts.principal')

@section('conteudo')
<h2>Classificação</h2>
<table class="table table-responsive">
  <thead>
    <tr class="info">
      <td align="center" colspan="4">
        <strong>Clique em alguma linha para detalhar a pontuação daquele jogador</strong>
      </td>
    </tr>
    <tr>
      <th width="10%">Ordem</th>
      <th width="40%">Jogador</th>
      <th width="20%">Apelido</th>
      <th width="30%">Pontuação</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 1; ?>
    @foreach($clas as $c)
      <tr <?php if ($cont % 2 == 0){ echo "class='fla1'"; } else { echo "class='fla2'"; }  ?> name="tr-detalha-palpites" data-id="{{$c->usuario_id}}" style="cursor:pointer;">
        <td><?php echo $cont; ?></td>
        <td>{{$c->name}}</td>
        <td>{{$c->login}}</td>
        <td>{{$c->pontuacao}}</td>
      </tr>
      <tr style="display:none;border:dashed 2px #dadada;" id="{{$c->usuario_id}}" name="tr-detalhes">
        <td>&nbsp;</td>
        <td colspan="3">
          <div class="small">
            <table class="table"> 
              @foreach ($ids as $k => $v)
                @if($c->usuario_id == $k)
                  @foreach($v as $vv)
                      <tr class="fla">
                        <td align="right">{{$vv->mandante}}</td>
                        <td align="center">{{$vv->placar1}}</td>
                        <td align="center">x</td>
                        <td align="center">{{$vv->placar2}}</td>
                        <td>{{$vv->visitante}}</td>
                        <td>Palpite: {{$vv->palpite_mandante}}x{{$vv->palpite_visitante}}</td>
                        <td><strong>{{$vv->pontuacao}} ponto(s)</strong></td>
                      </tr>    
                  @endforeach
                @endif
              @endforeach

              @foreach($extras as $k => $v)
                @if($c->usuario_id == $k AND !empty($extras[$k]))
                  <tr><td colspan="7" align="center"><strong>Pontuações extras:</strong></td></tr>
                  @foreach($v as $vv)
                    <tr class="fla">
                      <td align="right">{{date('d/m/Y', strtotime($vv->data))}}</td>
                      <td colspan="5">Motivo: {{$vv->motivo}}</td>
                      <td style="{{($vv->pontuacao < 0) ? 'color:#f00' : ''}}"><strong>{{$vv->pontuacao}} ponto(s)</strong></td>
                    </tr>
                  @endforeach
                @endif
              @endforeach
            </table><br />
          </div>
        </td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
@stop
