<html>
  <head>
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
    <title>Controle de estoque</title>
  </head>
  <body>
    <div class="container">
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="/">
              Bolão grupo Fla-Nação (whatsApp)
            </a>
          </div>
          <ul class="nav navbar-nav navbar-right">

            <li role="presentation" class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                Jogos <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="/jogos/resultados">Lançar Resultados</a></li>
                <li><a href="/jogos/cadastro">Cadastro</a></li>
                <li><a href="/jogos/lista-palpites">Palpites</a></li>
              </ul>
            </li>

            <li><a href="/classificacao">Classificação</a></li>

            <li><a href="/usuarios/gerencia">Usuários</a></li>

          </ul>
        </div>
      </nav>
      @yield('conteudo')
      <footer class="footer">
        <p>© Grupo Fla-Nação (WhatsApp).</p>
      </footer>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="/js/bootstrap.js"></script>
    <script src="/js/functions.js"></script>
  </body>
</html>
