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
$retornoExluir = "";
$spResultadoBusca = "";


if (filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT)) {
    
    $retornoUsuario = array();
    $retornoUsuario = $usuarioController->RetornaCod(filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT));
     
    //Verifica se existe usuario cadastrado
    if (!empty($retornoUsuario)){

    
        if ($retornoUsuario->getId() > 0 && $retornoUsuario->getId() > 0){     
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

                ?>
                <script>
                    document.cookie = "excluir=2";
                   // document.location.href = "?pagina=excluirusuario";
                </script>
                <?php
                    if (filter_input(INPUT_POST, "btnExcluir", FILTER_SANITIZE_STRING)) {
                        $retornoExluir = $usuarioController->Excluir($id);
                            if ($retornoExluir){
                                ?>
                                <script>
                                    document.cookie = "excluir=1";
                                    document.location.href = "?pagina=excluirusuario";
                                </script>
                                <?php
                            } else {
                                ?>
                                <script>
                                    document.cookie = "excluir=4";
                                    document.location.href = "?pagina=excluirusuario";
                                </script>
                                <?php
                            }
                    }

        } 
    } else {
                ?>
                <script>
                    document.cookie = "excluir=3";
                   // document.location.href = "?pagina=excluirusuario";
                </script>
                <?php
    }
    


    
} 






?>
  <style>
  .modal-header, h4, .close {
      background-color: #761c19;
      color:white !important;
      text-align: center;
      font-size: 30px;
  }
  .modal-footer {
      background-color: #f9f9f9;
  }
  </style>
<?php if ($_SESSION["permissao"] == 'ADM'){   ?>  
<div class="panel panel-default maxPanelWidth">
            <div class="panel-heading">Confirmar Excluir</div>
            <div class="panel-body">

                <table class="table table-responsive table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Cpf</th>
                            <th>Login</th>
                        </tr>
                    </thead>
                    <tbody>
                                <tr>
                                    <td><?= $nome; ?></td>
                                    <td><?= $cpf; ?></td>
                                    <td><?= $login; ?></td>
                                </tr>

                    </tbody>
                </table>
                <form method="post" name="frmDeletarUsuario" id="frmDeletarUsuario">
                    <div class="row">
                        <div class="col-lg-12">
                            <p id="pResultado"><?= $resultado; ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <input class="btn btn-danger btn-group-justified" type="submit" name="btnExcluir" value="Excluir"> 
                            <span><?= $spResultadoBusca; ?></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Confirmado</h4>
                    </div>
                    <div class="modal-body">
                        <p>Usuário excluido com Sucesso!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btnSair" data-dismiss="modal">Sair</button>
                    </div>
                </div>
            </div>
        </div>



<?php } ?>
<script>
    $(document).ready(function(){
            if (getCookie("excluir") == 1) {
                   $("#myModal").modal();
                   document.cookie = "excluir=0";
            } 
            
            if (getCookie("excluir") != 1 & getCookie("excluir") != 2 ){
                   document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Código Usuário não informado.</div>"; 
                  // document.cookie = "excluir=0";
           } 
            
            if (getCookie("excluir") == 3) {
                    document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Usuário não cadastrado.</div>"; 
                   document.cookie = "excluir=0";
            } 
            
            if (getCookie("excluir") == 4) {
                   document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Falha ao excluir Usuário.</div>"; 
                   document.cookie = "excluir=0";
            } 
            
            $("#btnSair").click(function(){
                  document.location.href = "?pagina=usuario&consulta=s";
            });
            
            
    });
</script>
