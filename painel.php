<?php
session_start();

if (isset($_SESSION["logado"])) {
    if (!$_SESSION["logado"]) {
        header("Location: management.php?msg=1");
    }
} else {
    header("Location: management.php?msg=1");
}
?>
<!doctype html>
<html lang="pt-br">
    <head>
        <title>Gerenciador Universidade</title>
        <meta charset="utf-8" />
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="bootstrap/css/dashboard.css" rel="stylesheet" type="text/css"/>
        <link href="css/painel.css" rel="stylesheet" type="text/css"/>
        <link href="css/select2.css" rel="stylesheet" type="text/css"/>     
        <link href="css/bootstrap-datepicker.css" rel="stylesheet">
        <script src="js/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-datepicker.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <script src="js/script.js" type="text/javascript"></script>
        <script src="js/select2.full.js" type="text/javascript"></script>
    </head>
 <body>

    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="?pagina=home">Gerenciador Universidade</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">

              
              
              


       
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Certificado(s) <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="?pagina=certificado">Gerenciar</a></li>
          </ul>
        </li>
  <?php if ($_SESSION["permissao"] == 'ADM'){   ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cadastro <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="?pagina=usuario">Usuário</a></li>

          </ul>
        </li>
                  <?php }  ?>  
            <li><a href="logout.php">Sair</a></li>
            

          </ul>
        </div>
      </div>
    </nav>
   
    <div class="container-fluid">
      <div class="row">

         <div class="col-sm-12">
          <?php        
                require_once("Util/RequestPage.php");
           ?>
          
          </div>
        </div>
      </div>

    </div>


  </body>
</html>


         
