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
        <link rel="shortcut icon" href="img/icones/iconeAnalytic.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <script src="js/script.js" type="text/javascript"></script>
        <script src="js/select2.full.js" type="text/javascript"></script>
    </head>
 <body>

    
   
    <div class="container-fluid">
      <div class="row">

         <div class="col-sm-12">

<?php
require_once ("Controller/UsuarioController.php");
require_once ("Model/Usuario.php");
require_once ("Model/PessoaFisica.php");

$usuarioController = new UsuarioController();
$usuario = new Usuario();
$pessoafisica = new PessoaFisica();
    
$id = 0;
$nome = "";
$email = "";
$login = "";
$cpf = "";
$senha = "";
$dtNascimento = "";
$sexo = "m";
$permissao = "";
$status = "";

$resultado = "";
$spResultadoBusca = "";
$listaUsuariosBusca = "";
$opcao = "";

    
if (filter_input(INPUT_POST, "btnGravar", FILTER_SANITIZE_STRING)) {
    
    $pessoafisica->setNome(filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING));
    $pessoafisica->setEmail(filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_STRING));
    $pessoafisica->setCpf(filter_input(INPUT_POST, "txtCpf", FILTER_SANITIZE_STRING));
    $pessoafisica->setNascimento(filter_input(INPUT_POST, "txtData", FILTER_SANITIZE_STRING));
    $pessoafisica->setSexo(filter_input(INPUT_POST, "slSexo", FILTER_SANITIZE_STRING));
    
    $usuario->setLogin(filter_input(INPUT_POST, "txtLogin", FILTER_SANITIZE_STRING));
    $usuario->setPassword(filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING));  
    $usuario->setStatus(filter_input(INPUT_POST, "slStatus", FILTER_SANITIZE_STRING));
    $usuario->setPermissao(filter_input(INPUT_POST, "slPermissao", FILTER_SANITIZE_STRING));
    
    if (!filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT)) {
        //Cadastrar

        
        if ($usuarioController->Cadastrar($usuario, $pessoafisica)) {             
            if ($usuarioController->getLastId() > 0 && $usuarioController->getLastIdPessoaFisica() > 0){ 
                    $lastIdPessoaFisica = $usuarioController->getLastIdPessoaFisica();
                    $lastIdUsuario = $usuarioController->getLastId();
            }      
            ?>
            <script>
                document.cookie = "msg=1";
                document.location.href = "?pagina=usuario&form=s";
            </script>
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar cadastrar o usuário.</div>";
        }
    } else {
        //Editar
        $usuario->setId(filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING));
         
        if ($usuarioController->Editar($usuario,$pessoafisica)) {
           $paramScript =  "<script>document.cookie = 'msg=2';document.location.href = '?pagina=usuario&form=s&id=".$usuario->getId()."';</script>";
           
           ?>
           <?= $paramScript; ?>         
            <?php
        } else {
            $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar alterar o usuário.</div>";
        }
    }
}







?>

<div id="dvUsuarioView">
    <h1>Cadastrar Usuário</h1>
    <br />
    <br />
    <!--DIV CADASTRO -->

        <div class="panel panel-default maxPanelWidth">
            <div class="panel-heading"><?= $opcao; ?></div>
            <div class="panel-body">
                <form method="post" id="frmGerenciarUsuario" name="frmGerenciarUsuario" novalidate>
                    <div class="row">
                        <div class="col-lg-12">
                            <p id="pResultado"><?= $resultado; ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <input type="hidden" id="txtIdUsuario" value="<?= $id; ?>" />
                                <label for="txtNome">Nome completo</label>
                                <input type="text" class="form-control" id="txtNome" name="txtNome" placeholder="Nome completo" value="<?= $nome; ?>">
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtLogin">Login</label>
                                <input type="text" class="form-control" id="txtLogin" name="txtLogin" placeholder="login"  value="<?= $login; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtEmail">E-mail</label>
                                <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="email@dominio.com"  value="<?= $email; ?>">
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtCpf">CPF</label>
                                <input type="text" class="form-control" id="txtCpf" name="txtCpf" placeholder=""  value="<?= $cpf; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtSenha">Senha <span class="vlSenha"></span></label>
                                <input type="password" class="form-control" id="txtSenha" name="txtSenha" placeholder="*******" <?= ($senha) == "" ? "" : "disabled='disabled'"; ?> />
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtSenha2">Confirmar senha <span class="vlSenha"></span></label>
                                <input type="password" class="form-control" id="txtSenha2" name="txtSenha2" placeholder="*******" <?= ($senha) == "" ? "" : "disabled='disabled'"; ?> />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtData">Data nascimento</label>
                                <input type="text" class="form-control" id="txtData" name="txtData" placeholder="  /  /" value="<?= $dtNascimento; ?>"/>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="slSexo">Sexo</label>
                                <select class="form-control" id="slSexo" name="slSexo">
                                    <option value="m" <?= ($sexo == "M" ? "selected='selected'" : "") ?>>Masculino</option>
                                    <option value="f" <?= ($sexo == "F" ? "selected='selected'" : "") ?>>Feminino</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="slStatus">Status</label>
                                <select class="form-control" id="slStatus" name="slStatus">
                                    <option value="A" selected="selected">Ativo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="slPermissao">Permissão</label>
                                <select class="form-control" id="slPermissao" name="slPermissao">
                                    <option value="USER" selected="selected">Comum</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input class="btn btn-success" type="submit" name="btnGravar" value="Salvar">
                    <a href="http://localhost/universidade/management.php" class="btn btn-danger">Cancelar</a>

                    <br />
                    <br />
                    <div class="row">
                        <div class="col-lg-12">
                            <ul id="ulErros"></ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <br />
        

</div>

<script src="js/mask.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {

        
        if (getCookie("msg") == 1) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Usuário cadastrado com sucesso.</div>";
            document.cookie = "msg=0";
        } else if (getCookie("msg") == 2) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Usuário alterado com sucesso.</div>";
            document.cookie = "msg=0";
        }

        $('#txtCpf').mask('000.000.000-00');
        $('#txtData').mask('00/00/0000');

        $("#frmGerenciarUsuario").submit(function (e) {
            if (!ValidarFormulario()) {
                e.preventDefault();
            }
        });

        var vlSenhas = document.getElementsByClassName("vlSenha");

        $("#txtSenha").keyup(function () {

            if (ValidarSenha()) {
                for (var i = 0; i < vlSenhas.length; i++) {
                    vlSenhas[i].style.color = "green";
                    vlSenhas[i].innerHTML = "válido";
                }
            } else {
                for (var i = 0; i < vlSenhas.length; i++) {
                    vlSenhas[i].style.color = "red";
                    vlSenhas[i].innerHTML = "inválido";
                }
            }
        });

        $("#txtSenha2").keyup(function () {

            if (ValidarSenha()) {
                for (var i = 0; i < vlSenhas.length; i++) {
                    vlSenhas[i].style.color = "green";
                    vlSenhas[i].innerHTML = "válido";
                }
            } else {
                for (var i = 0; i < vlSenhas.length; i++) {
                    vlSenhas[i].style.color = "red";
                    vlSenhas[i].innerHTML = "inválido";
                }
            }
        });
        

    });

    function ValidarSenha() {
        var senha1 = $("#txtSenha").val();
        var senha2 = $("#txtSenha2").val();

        if (senha1.length >= 4 && senha2.length >= 4) {
            if (senha1 == senha2) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ValidarFormulario() {
        var erros = 0;
        var ulErros = document.getElementById("ulErros");
        ulErros.style.color = "red";
        ulErros.innerHTML = "";


        //Javascript nativo
        if (document.getElementById("txtNome").value.length < 5) {
            var li = document.createElement("li");
            li.innerHTML = "- Informe um nome válido";
            ulErros.appendChild(li);
            erros++;
        }

        if (document.getElementById("txtLogin").value.length < 4) {
            var li = document.createElement("li");
            li.innerHTML = "- Informe um login de usuário com mais de 3 caracteres";
            ulErros.appendChild(li);
            erros++;
        }

        if (document.getElementById("txtEmail").value.indexOf("@") < 0 || document.getElementById("txtEmail").value.indexOf(".") < 0) {
            var li = document.createElement("li");
            li.innerHTML = "- Informe um e-mail válido";
            ulErros.appendChild(li);
            erros++;
        }

        //JQuery
        if ($("#txtCpf").val().length < 14) {
            var li = document.createElement("li");
            li.innerHTML = "- Informe um CPF válido";
            $("#ulErros").append(li);
            erros++;
        }

        if (!ValidarSenha() && $("#txtIdUsuario").val() == "0") {
            var li = document.createElement("li");
            li.innerHTML = "- Senhas inválidas";
            $("#ulErros").append(li);
            erros++;
        }


        var sexo = document.getElementById("slSexo").value;
        if (sexo != "m" && sexo != "f") {
            var li = document.createElement("li");
            li.innerHTML = "- Sexo inválido";
            ulErros.appendChild(li);
            erros++;
        }

        var permissao = document.getElementById("slPermissao").value;
        if (permissao != "ADM" && permissao != "USER") {
            var li = document.createElement("li");
            li.innerHTML = "- Permissão inválida";
            ulErros.appendChild(li);
            erros++;
        }

        if (erros === 0) {
            return true;
        } else {
            return false;
        }
    }
</script>

 </div>
        </div>
      </div>

    </div>


  </body>
</html>

