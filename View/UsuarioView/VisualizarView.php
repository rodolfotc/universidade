<?php
require_once ("Controller/UsuarioController.php");
require_once ("Model/Usuario.php");
require_once ("Model/PessoaFisica.php");

$usuarioController = new UsuarioController();
$usuario = new Usuario();
$pessoafisica = new PessoaFisica();

    $id = "";
    $nome = "";
    $email = "";
    $login = "";
    $cpf = "";
    $dtNascimento = "";

    $sexo = "";
    $permissao = "";
    $status = "";
    
    //Endereco

if (filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT)) {

    $retornoUsuario = array();
    $retornoUsuario = $usuarioController->RetornaCod(filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT));
    //Verifica se existe usuario cadastrado
    if (!empty($retornoUsuario)){
    
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
        
    }
    
   
}

?>
<?php if ($_SESSION["permissao"] == 'ADM'){   ?>
<div id="dvVisualizarView">
    <h1>Visualizar usuário</h1>
    <br />
    <!--DIV CADASTRO -->
    <div class="panel panel-default maxPanelWidth">
        <div class="panel-heading"><h3>Dados pessoais</h3></div>
        <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="detalheExibir">
                                <h4 class="h4detalhe">Nome</h4>                               
                                <span class="spanDetalhe"><?= $nome ?></span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="detalheExibir">
                                <h4 class="h4detalhe">Login</h4>                               
                                <span class="spanDetalhe bold"><?= $login ?></span>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="detalheExibir">
                                <h4 class="h4detalhe">Cpf</h4>                               
                                <span class="spanDetalhe bold"><?= $cpf ?></span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="detalheExibir">
                                <h4 class="h4detalhe">E-mail</h4>                               
                                <span class="spanDetalhe bold"><?= $email ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="detalheExibir">
                                <h4 class="h4detalhe">Data de Nascimento</h4>                               
                                <span class="spanDetalhe bold"><?= $dtNascimento ?></span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="detalheExibir">
                                <h4 class="h4detalhe">Sexo</h4>                               
                                <span class="spanDetalhe bold"><?= ($sexo == "M" ? "Masculino" : "Feminino"); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="detalheExibir">
                                <h4 class="h4detalhe">Permissão</h4>                               
                                <span class="spanDetalhe bold"><?= ($permissao == "ADM" ? "Administrador" : "Usuário"); ?></span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="detalheExibir">
                                <h4 class="h4detalhe">Status</h4>                               
                                <span class="spanDetalhe bold"> <?= ($status == "A" ? "Ativo" : "Bloqueado"); ?> </span>
                            </div>
                        </div>
                    </div>
                     <div class="row">

                        <div class="col-lg-6 col-xs-12">
                            <div>
                                 <a href="?pagina=usuario" class="btn btn-danger aExit" id="btnExcluir">Sair</a>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
    <br />

</div>
<?php } ?>
<style>
    .detalheExibir
{
   background-color:#FFF;
   border: 1px solid #BDBDBD;

}

     .h4detalhe
     {
         color: #1b6d85;
         margin-left: 10px;
     }
     
     .spanDetalhe
     {
         margin-left: 30px;
         font-size: larger;
         font-style: initial;
         
     }
     
     .aExit
     {
         margin-top: 10px;
         margin-left: 10px;
     }
</style>
