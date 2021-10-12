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
        //Buscar usuários

if (filter_input(INPUT_POST, "btnBuscar", FILTER_SANITIZE_STRING)) {

    $termo = filter_input(INPUT_POST, "txtTermo", FILTER_SANITIZE_STRING);
    $tipo = filter_input(INPUT_POST, "slTipoBusca", FILTER_SANITIZE_NUMBER_INT);
    
    $listaUsuariosBusca = array();
    $listaUsuariosBusca = $usuarioController->RetornarUsuarios($termo, $tipo);

        if ($listaUsuariosBusca != null) {
            $spResultadoBusca = "Exibindo dados";
        } else {
            $spResultadoBusca = "Dados não encontrado";
        }
} else {
            //consulta
            //carrega array com todos   
            $termoAll = "";
            $tipoAll = 0;
          
            $listaUsuariosBusca = array();
            $listaUsuariosBusca = $usuarioController->RetornarUsuarios($termoAll, $tipoAll);

                if ($listaUsuariosBusca != null) {
                    $spResultadoBusca = "Exibindo dados";
                } else {
                    $spResultadoBusca = "Dados não encontrado";
                }
}



if (filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT)) {
    $opcao = "Editar";
    
    $retornoUsuario = array();
    $retornoUsuario = $usuarioController->RetornaCod(filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT));

    
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
    $nome = $retornoUsuario->getNome();
    $email = $retornoUsuario->getEmail();
    $login = $retornoUsuario->getUsuario()->getLogin();
    $cpf = $retornoUsuario->getCpf();
    $senha = "sim";
    $date = str_replace('-', '/', $retornoUsuario->getNascimento());
    $dtNascimento = date('d-m-Y', strtotime($date));

    $sexo = $retornoUsuario->getSexo();
    $permissao = $retornoUsuario->getUsuario()->getPermissao();
    $status = $retornoUsuario->getUsuario()->getStatus();

} else {
    $opcao = "Cadastrar";
}


?>
<?php if ($_SESSION["permissao"] == 'ADM'){   ?>
<div id="dvUsuarioView">
    <h1>Gerenciar Usuário(s)</h1>
    <br />
    <div class="controlePaginas">
        <a href="?pagina=usuario"><img src="img/icones/buscar.png" alt=""/></a>
        <a href="?pagina=usuario&form=s"><img src="img/icones/editar.png" alt=""/></a>
    </div>

    <br />
    <!--DIV CADASTRO -->
    <?php
    if (filter_input(INPUT_GET, "form", FILTER_SANITIZE_STRING)) {
        ?>
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
                                    <option value="A" <?= ($status == "A" ? "selected='selected'" : "") ?>>Ativo</option>
                                    <option value="B" <?= ($status == "B" ? "selected='selected'" : "") ?>>Bloqueado</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="slPermissao">Permissão</label>
                                <select class="form-control" id="slPermissao" name="slPermissao">
                                    <option value="ADM" <?= ($permissao == "ADM" ? "selected='selected'" : "") ?>>Administrador</option>
                                    <option value="USER" <?= ($permissao == "USER" ? "selected='selected'" : "") ?>>Comum</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input class="btn btn-success" type="submit" name="btnGravar" value="Salvar">
                    <a href="?pagina=usuario" class="btn btn-danger">Cancelar</a>

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
        <?php
    } else {
        ?>
        <br />
        <!--DIV CONSULTA -->
        <div class="panel panel-default maxPanelWidth">
            <div class="panel-heading">Consultar</div>
            <div class="panel-body">
                <form method="post" name="frmBuscarUsuario" id="frmBuscarUsuario">
                    <div class="row">
                        <div class="col-lg-8 col-xs-12">
                            <div class="form-group">
                                <label for="txtTermo">Termo de busca</label>
                                <input type="text" class="form-control" id="txtTermo" name="txtTermo" placeholder="" />
                            </div>
                        </div>

                        <div class="col-lg-4 col-xs-12">
                            <div class="form-group">
                                <label for="slTipoBusca">Tipo</label>
                                <select class="form-control" id="slTipoBusca" name="slTipoBusca">
                                    <option value="1">Nome</option>
                                    <option value="2">E-mail</option>
                                    <option value="3">CPF </option>
                                    <option value="4">Usuário </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <input class="btn btn-info" type="submit" name="btnBuscar" value="Buscar"> 
                            <span><?= $spResultadoBusca; ?></span>
                        </div>
                    </div>
                </form>

                <hr />
                <br />

                <table class="table table-responsive table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Cpf</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($listaUsuariosBusca != null) {
                            
                            for($i = 0; $i < count($listaUsuariosBusca); ++$i) {
                                $user = $listaUsuariosBusca[$i];                              
                                
                                ?>
                                <tr>
                                    <td><?= $user->getNome(); ?></td>
                                    <td><?= $user->getCpf(); ?></td>   
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Opções <span class="caret"></span>
                                            </button>
                                            
                                             
                                            <ul class="dropdown-menu">                          
                                                <li><a href="?pagina=visualizarusuario&id=<?= $user->getUsuario()->getId(); ?>">Visualizar</a></li>
                                                 <li role="separator" class="divider"></li>
                                                <li><a href="?pagina=alterarsenha&id=<?= $user->getUsuario()->getId(); ?>">Alterar Senha</a></li>
                                                <li><a href="?pagina=usuario&form=s&id=<?= $user->getUsuario()->getId(); ?>">Editar</a></li>
                                                <li><a href="?pagina=excluirusuario&id=<?= $user->getUsuario()->getId(); ?>">Excluir</a></li> 
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<?php } ?>
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
