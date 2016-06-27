$(function(){

  // Salva lançamento de um jogo real
  $("button[name^='jogo_']").click(function(){
    var jogo = $(this).attr('name').split('_')[1]; //ID do jogo
    var pMandante = $("input[name='pMandante"+jogo+"']").val(); //palpite Mandante
    var pVisitante = $("input[name='pVisitante"+jogo+"']").val(); //palpite Visitante

    if(!pMandante || !pVisitante){
      alert('Preencha o resultado!');
      return false;
    }
    var pars = { j: jogo, pm: pMandante, pv: pVisitante };

    $.ajax({
        type: 'GET',
        url: '/jogos/resultados/salvar',
        data: pars,
        success: function(data){
          if (data != "1") alert(data);
          else {
            $("#alert"+jogo)
              .html("Salvo!")
              .fadeIn('slow');

            alert('Não se esqueça de calcular a pontuação.')
            var desloc = $('#btn-calcular').offset().top;
            $('html, body').animate({ scrollTop: desloc }, 'slow');
          }
        }
    });
  })

  // Carrega os campos para edição dos jogos
  $("button[name^='btnAltera-jogo_']").click(function(){
    var jogo = $(this).attr('name').split('_')[1]; //ID do jogo
    var id1 = $(this).siblings("input[name='hidTime1']").val(); // ID mandante
    var id2 = $(this).siblings("input[name='hidTime2']").val(); // ID Visitante
    var idCamp = $(this).siblings("input[name='hidCampeonato']").val(); // ID Campeonato
    var data = $(this).siblings("input[name='hidData']").val(); // Data
    var hora = $(this).siblings("input[name='hidHora']").val().split(":"); // Hora
    //alert(hora);
    $("select[name='mandante']").val(id1); //seleciona mandante
    $("select[name='visitante']").val(id2); //seleciona visitante
    $("select[name='campeonato']").val(idCamp); //seleciona campeonato
    $("input[name='data_jogo']").val(data); //seleciona campeonato
    $("input[name='hora_jogo1']").val(hora[0]); // Hora -> horas 
    $("input[name='hora_jogo2']").val(hora[1]); // Hora -> minutos

    $("#frmCadastroJogo").prop('action', '/jogos/edita');
    $("#frmCadastroJogo button[type='submit']").text('Alterar');
    $("input[name='hidIdJogo']").val(jogo);
    $("#frmCadastroJogo a").css('display', 'block');

    $("select[name='mandante']").focus();
    $('html, body').animate({ scrollTop: 0 }, 'slow');
  });

  //Liberar/Bloquear palpites
  $("button[name^='btnLibera-jogo_']").on("click", function(){
    var btn = $(this); //botão
    var f = btn.data('liberado');
    var j = btn.attr('name').split("_")[1];

    pars = { flag: f, jogo: j };
    //alert(jogo);
    $.ajax({
        type: 'GET',
        url: '/jogos/mudaLiberado',
        data: pars,
        success: function(data){
          if (f == 1)
            btn.prop('class', 'btn btn-xs btn-success glyphicon glyphicon-folder-open').data('liberado', 0).text(' Liberar');
          else
            btn.prop('class', 'btn btn-xs btn-warning glyphicon glyphicon-lock').data('liberado', 1).text(' Bloquear');
        }
    });
  });

  // Excluir Jogo
  $("button[name^='btnExclui-jogo_']").on("click", function(){
    if (!confirm('Tem certeza que deseja excluir  jogo?')) return false
    
    var btn = $(this); //botão
    var j = btn.attr('name').split("_")[1];

    pars = { jogo: j };
    //alert(jogo);
    $.ajax({
        type: 'GET',
        url: '/jogos/exclui',
        data: pars,
        success: function(data){
          btn.parent().parent('tr').remove();
        }
    });
  });


  // Salva palpite
  $("button[name^='palpiteJogo_']").click(function(){
    var user = 1; // Pegar valor da sessão
    var jogo = $(this).attr('name').split('_')[1]; //ID do jogo
    var pMandante = $("input[name='pMandante"+jogo+"']").val(); //palpite Mandante
    var pVisitante = $("input[name='pVisitante"+jogo+"']").val(); //palpite Visitante

    if(!pMandante || !pVisitante){
      alert('Preencha o resultado!');
      return false;
    }

    var pars = { j: jogo, u: user, pm: pMandante, pv: pVisitante };

    $.ajax({
        type: 'GET',
        url: '/jogos/palpites/salvar',
        data: pars,
        success: function(data){
          if (data == "1") {
            $("#alert"+jogo)
              .html("Salvo!")
              .fadeIn('slow');
          } else if (data == "0") {
            alert('Palpites bloqueados.')
            window.location = '/';
          } else 
            alert(data);
        }
    });
  })

  $("a[name^='btn-aprova']").click(function(){
    btn = $(this);
    valor = btn.data('valor');
    id = btn.attr('name').split('_')[1];
    var pars = { v: valor, i: id };

    $.ajax({
        type: 'GET',
        url: '/usuarios/aprovacao',
        data: pars,
        success: function(data){
          alert(data);
          btn.parent().parent('tr').remove();
        }
    });
  });









});
