<?php
session_start();

require_once("Controller/UsuarioController.php");
require_once("Model/Usuario.php");

require_once("Model/PessoaFisica.php");

$retorno = "&nbsp;";

if (isset($_SESSION["entrar"])) {
    header("Location: painel.php?pagina=dashboard");
}


if (filter_input(INPUT_GET, "msg", FILTER_SANITIZE_NUMBER_INT)) {
    if (filter_input(INPUT_GET, "msg", FILTER_SANITIZE_NUMBER_INT) == 1) {
        $retorno = "<div class=\"alert alert-danger\" role=\"alert\">Acesso negado!!!</div>";
    } else {
        $retorno = "<div class=\"alert alert-warning\" role=\"alert\">Você fez Logout.</div>";
    }
}

if (filter_input(INPUT_POST, "btnEntrar", FILTER_SANITIZE_STRING)) {

    $usuarioController = new UsuarioController();
    $user = filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING);
    $permissao = 1;
    
    $resultado = $usuarioController->AutenticarUsuario($user, $pass, $permissao);
    if ($resultado != null) {
        //se usuario esta ativo loga
        if ($resultado->getUsuario()->getStatus() == 'A'){
            if (filter_input(INPUT_POST, "ckManterLogado", FILTER_SANITIZE_STRING)) {
                $_SESSION["entrar"] = true;
            }
            $_SESSION["cod"] = $resultado->getUsuario()->getId();
            $_SESSION["nome"] = $resultado->getUsuario()->getLogin();
            $_SESSION["permissao"] = $resultado->getUsuario()->getPermissao();
            $_SESSION["nome"] = $resultado->getNome();
            $_SESSION["cpf"] = $resultado->getCpf();
            $_SESSION["logado"] = true;
            header("Location: painel.php?pagina=dashboard");
            
        } else {
            
            $retorno = "<div class=\"alert alert-danger\" role=\"alert\">Usuário Bloqueado.</div>";
        }        

    } else {
            $retorno = "<div class=\"alert alert-danger\" role=\"alert\">Usuário ou senha inválido.</div>";
    }
}
?>
<!doctype html>
<html lang="pt-br">
    <head>
        <title>Login - Gerenciador Universidade</title>
        <meta charset="utf-8" />
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <script src="js/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    </head>
    <body>
        <div id="dvLogin">
            <form method="post">
                <div class="row">
                    <div class="clear"></div>

                    <br /> 
                    <div class="borderBottom"></div>
                    <br /> 

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="txtUsuario">Usuário</label>
                            <input type="text" class="form-control" id="txtUsuario" name="txtUsuario">
                        </div>
                        <div class="form-group">
                            <label for="txtSenha" >Senha</label>
                            <input type="password" class="form-control" id="txtSenha" name="txtSenha">
                        </div>
                        <input class="btn btn-lg btn-primary btn-block" type="submit" name="btnEntrar" value="Entrar">                
                        <br />
                        <a href="http://localhost/universidade/cadastrarUsuario.php"><span/>Cadastrar Usuário</span></a>  
 
                    </div>
                    <p>&nbsp;</p>
                    <div class="col-lg-12">
                        <?= $retorno; ?>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Recuparar senha</h4>
                    </div>
                    <div class="modal-body">
                        <p>Para recuperar a sua senha, por favor, dentre em contato com o administrador.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $('#myModal').on('shown.bs.modal', function () {
                $('#myInput').focus();
            });
        </script>
    </body>
</html>
