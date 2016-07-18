$(function(){
  var _IDtr;

  // ********** Contador palpites - início **********
  date = new Date($('#dia_hora').val());
  console.log("BD: "+date);
  //Contador de palpites
   $('#clock').countdown({
    until: new Date($('#dia_hora').val()),
    serverSync: serverTime,
    layout: '{dn} {dl} : {hn} {hl} : {mn} {ml} : {sn} {sl}',
    //alwaysExpire: true,
    //onExpiry: finishTime
   });

  function serverTime() { 
    var time = null; 
    $.ajax({
      url: '/jogos/sync', 
      async: false, 
      dataType: 'text', 
      success: function(text) { 
        time = new Date(text); 
      }, 
      error: function(http, message, exc) {  
        time = new Date(); 
      }
    }); 
    console.log("Atual: "+time);
    return time; 
  }

  /*
  function finishTime() {
    $('#clock').html('Palpites bloqueados!')
    .parent().addClass('disabled');
    $.ajax({
      type: 'GET',
      url: '/jogos/mudaBloq/0',
      success: function(){
        location.reload();
      } 
    }); 
  }
  */
 // ********** Contador palpites - fim **********

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
    $.ajax({
        type: 'GET',
        url: '/jogos/mudaLiberado',
        data: pars,
        success: function(data){
          if (f == 1) {
            btn.prop('class', 'btn btn-xs btn-success glyphicon glyphicon-folder-open').data('liberado', 0).text(' Liberar');
            btn.parent().parent('tr').prop('class', 'danger');
          } else {
            btn.prop('class', 'btn btn-xs btn-warning glyphicon glyphicon-lock').data('liberado', 1).text(' Bloquear');
            btn.parent().parent('tr').prop('class', 'success');
          }
        }
    });
  });

  // Excluir Jogo
  $("button[name^='btnExclui-jogo_']").on("click", function(){
    if (!confirm('Tem certeza que deseja excluir  jogo?')) return false
    var btn = $(this); //botão
    var j = btn.attr('name').split("_")[1];
    pars = { jogo: j };
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
    var user = parseInt($("input[name='hid-user']").val()); // Pegar valor da sessão
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
            alert('Jogo Salvo!');
          } else if (data == "0") {
            alert('Palpites bloqueados.')
            window.location = '/';
          } else 
            alert(data);
        }
    });
  })

  // Ver palpites de outros jogadores
  $("button[name='verPalpites']").click(function(){
    $("tr[name='tr-outros-palpites']").slideToggle('slow');
    //$("tr[name='tr-outros-palpites']").css('display', 'table-row');
  });
  

  //Aprovar cadastro
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

  //Ativar time
  $("button[name^='btn-ativar_']").click(function(){
    btn = $(this);
    valor = btn.data('valor');
    id = btn.attr('name').split('_')[1];

    var pars = { v: valor, i: id };

    $.ajax({
        type: 'GET',
        url: '/times/ativacao',
        data: pars,
        success: function(data){
          if (data == 1){
            btn.prop('class', 'btn btn-sm btn-danger').data('valor', 0).text('Desativar');
            btn.parent().parent('tr').prop('class', 'success');
          } else {
            btn.prop('class', 'btn btn-sm btn-success').data('valor', 1).text('Ativar');
            btn.parent().parent('tr').prop('class', 'danger');
          }
        }
    });
  });

  //Lançar pontuação extra
  $("button[name^='btn-pontos_']").click(function(){
    btn = $(this);
    id = btn.attr('name').split('_')[1];

    $("tr[name='tr-pontos']").css('display', 'none');
    $("#tr-pontos_"+id).css('display', 'table-row');
  });

  // somar 1 a pontuação extra
  $("button[name='btn-mais']").click(function(){
    input = $(this).parent().siblings("input[type='number']");
    valor = parseInt(input.val());
    valor += 1;
    input.val(valor);
  });

  // subtrair 1 a pontuação extra
  $("button[name='btn-menos']").click(function(){
    input = $(this).parent().siblings("input[type='number']");
    valor = parseInt(input.val());
    valor -= 1;
    input.val(valor);
  });

  //soma/subtrai os pontos no cadastro do usuario
  $("button[name='btn-ok']").click(function(){
    usuId = $(this).data('id');
    inputNumber = $(this).siblings("input[type='number']");
    inputText = $(this).siblings("input[type='text']");
    valor = parseInt(inputNumber.val());
    motivo = inputText.val();

    if (valor == 0) {
      alert("Insira um valor diferente de 0 (zero).");
      return false;
    }
    if (motivo == "") {
      alert("Descreva o motivo da pontuação extra.");
      return false;
    }

    if (!confirm("Confirma o lançamento dos pontos a esse jogador?")) return false;

    var pars = { id: usuId, v: valor, m: motivo };
    $.ajax({
        type: 'GET',
        url: '/usuarios/pontos',
        data: pars,
        success: function(data){
          alert("Pontuação lançada com sucesso!");
          $("tr[name='tr-pontos']").css('display', 'none');
          $("input[name='txtValor']").val("0");
          $("input[name='txtMotivo']").val("");
        }
    });
    
  });

  // Detalhamento de palpites
  $("tr[name='tr-detalha-palpites']").click(function(){
    id = $(this).data('id');
    $(this).siblings('tr#'+id).slideToggle('slow');
    if (_IDtr) 
      $(this).siblings('tr#'+_IDtr).slideUp('fast');
    _IDtr = id;
  });

  //Ativar campeonato
  $("button[name^='btn-ativar-camp_']").click(function(){
    btn = $(this);
    valor = btn.data('valor');
    id = btn.attr('name').split('_')[1];

    var pars = { v: valor, i: id };

    $.ajax({
        type: 'GET',
        url: '/campeonatos/ativacao',
        data: pars,
        success: function(data){
          if (data == 1){
            btn.prop('class', 'btn btn-sm btn-danger').data('valor', 0).text('Desativar');
            btn.parent().parent('tr').prop('class', 'success');
          } else {
            btn.prop('class', 'btn btn-sm btn-success').data('valor', 1).text('Ativar');
            btn.parent().parent('tr').prop('class', 'danger');
          }
        }
    });
  });

  // Bloqueia palpites da rodada
  $("li>a[name='a-bloquear-palpites']").click(function(){
    if (confirm('Confirma o bloqueio de TODOS os palpites?')){
      $.ajax({
        type: 'GET',
        url: '/jogos/bloqueio',
        success: function(data){
          $('location').href = "/jogos/resultados";
        }
      });
    }
  });







});
