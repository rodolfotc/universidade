<?php
require_once ("Controller/CertificadoController.php");
require_once ("Model/Certificado.php");



$certificadoController = new CertificadoController();
$certificado = new Certificado();
    
            $id = "";
            $quantidade = "";
            $data = "";

$resultado = "";
$retornoExluir = "";
$spResultadoBusca = "";

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT); 

if ($id) {
    
    //$retornoEndereco = array();
    $retorno = $certificadoController->RetornaCod($id);
     

    //Verifica se existe usuario cadastrado
    if (!empty($retorno)){

    
        if ($retorno->getId() > 0){ 
            $id = $retorno->getId();
            $quantidade = number_format($retorno->getQuantidade(),2,",","."); 
            $data =  date('d/m/Y', strtotime($retorno->getData()));  
            $foto =  $retorno->getFoto();
            

                ?>
                <script>
                    document.cookie = "excluir=2";
                </script>
                <?php
                
                if (filter_input(INPUT_POST, "btnExcluir", FILTER_SANITIZE_STRING)) {
    
                    
                    
                           $retornoExluir = $certificadoController->Excluir($id);                         
                           if ($retornoExluir){
                                    //Verifica se existe foto
                                    if (!empty($foto)) {
                                        //exclui a foto do diretorio
                                        unlink("thumb/".$foto."");
                                        unlink("thumbprincipal/".$foto."");
                                    }
                                ?>
                                <script>
                                    document.cookie = "excluir=1";
                                    document.location.href = "?pagina=excluircertificado";
                                </script>
                                <?php
                            } else {
                                ?>
                                <script>
                                    document.cookie = "excluir=4";
                                    document.location.href = "?pagina=excluircertificado";
                                </script>
                                <?php
                            }
                }

        } 
    } else {
                ?>
                <script>
                    document.cookie = "excluir=3";
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
                            <th>Id</th>
                            <th>Quantidade (h)</th> 
                            <th>Data</th>   
                        </tr>
                    </thead>
                    <tbody>
                                <tr>
                                    <td><?= $id; ?></td>
                                    <td><?= $quantidade; ?></td>    
                                    <td><?= $data; ?></td> 
                                </tr>

                    </tbody>
                </table>
                <form method="post" name="frmDeletar" id="frmDeletar">
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
                        <p>Excluido com Sucesso!</p>
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
                   document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Código não informado.</div>"; 
                  // document.cookie = "excluir=0";
           } 
            
            if (getCookie("excluir") == 3) {
                    document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Não cadastrado.</div>"; 
                   document.cookie = "excluir=0";
            } 
            
            if (getCookie("excluir") == 4) {
                    document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Falha ao excluir, esse certificado possui um pedido! Altere o campo Ativo para desativar a sua visualização.</div>"; 
                   document.cookie = "excluir=0";
            } 
            
            $("#btnSair").click(function(){
                  document.location.href = "?pagina=certificado&consulta=s";
            });
            
            
    });
</script>
