<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="/css/app.css" rel="stylesheet"> 
    <link href="/css/custom.css" rel="stylesheet"> 
    <title>Bolão do Grupo Fla-Nação</title>
  </head>
  <body>
    <div class="container">
      <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#flanacao-navbar-collapse">
              <span class="sr-only">Toggle Navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="/" style="font-size:x-large;">
              Bolão Fla-Nação
            </a>
          </div>

          <div class="collapse navbar-collapse" id="flanacao-navbar-collapse">
            <ul class="nav navbar-nav navbar-right">

              @if (Auth::guest())
                <li><a href="/classificacao">Classificação</a></li>
                <li><a href="{{ url('/login') }}">Login</a></li>
                <li><a href="{{ url('/register') }}">Registrar-se</a></li>
              @else
                @if (Auth::user()->adm == 1)
                  <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                      Jogos <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a href="/jogos/resultados">Lançar Resultados</a></li>
                      <li><a href="/jogos/cadastro">Cadastro de Jogos</a></li>
                      <li><a href="/jogos/lista-palpites">Efetuar Palpites</a></li>
                      <li><a href="/jogos/bloqueio">Bloquear/Desbloquear Palpites</a></li>
                    </ul>
                  </li>

                  <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    ADM <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a href="/times/cadastro">Times</a></li>
                      <li><a href="/usuarios/gerencia">Usuários</a></li>
                      <li><a href="/campeonatos/gerencia">Campeonatos</a></li>
                      <li><a href="/configuracoes/config">Configurações</a></li>
                    </ul>
                  </li> 
                  <li><a href="/classificacao">Classificação</a></li>
                @else
                  <li><a href="/classificacao">Classificação</a></li>
                  <li><a href="/jogos/lista-palpites">Efetuar Palpites</a></li>
                @endif
                <li style="color:#f00;"><a href="">Usuário:<br /> {{Auth::user()->login}}</a></li>
                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
              @endif
            </ul>
          </div>
        </div>
      </nav>
      @yield('conteudo')
      <footer class="footer">
        <p>© Grupo Fla-Nação (WhatsApp).</p>
      </footer>
    </div>
    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    <script src="/js/jquery.countdown.min.js"></script>
    <script src="/js/functions.js"></script>
  </body>
</html>
