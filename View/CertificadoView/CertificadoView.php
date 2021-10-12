<?php
require_once ("Controller/CertificadoController.php");
require_once ("Model/Certificado.php");

require_once ("Controller/PessoaFisicaController.php");
require_once ("Model/PessoaFisica.php");


require_once ("Model/Usuario.php");


$certificadoController = new CertificadoController();
$certificado = new Certificado();


$pessoaFisicaController = new PessoaFisicaController();
$pessoaFisica = new PessoaFisica();

$termoAll = "";
$tipoAll = 0;
$listaBuscaPermissao = "";
$titularCertificado = "";
$tipoEnvio = "";


$cliente = 0;
$totalHoras = 0;

$tipo = 0;
$fotoView = "";

$id = "";
$valor = "";
$dataBusca = "";
$dataHoje =  date('d/m/Y');
$ativo = 'B';

$quantidade = "";
$tipo = "";
$nome = "";
$descricao = "";
$fotoEditar = "";


$resultado = "";
$spResultadoBusca = "";
$listaUsuariosBusca = [];
$opcao = "";
$retornoBanco = "";


    
if (filter_input(INPUT_POST, "btnGravar", FILTER_SANITIZE_STRING)) {
    
    //Data de Entrada do Certificado   
    $data = str_replace("/", "-", filter_input(INPUT_POST, "txtData",FILTER_SANITIZE_STRING));
    $dataFormatada =  date('Y-m-d', strtotime($data));
    $certificado->setData($dataFormatada);
    
    //Quantidade
    $quantidadeFormatado = str_replace(".", "", filter_input(INPUT_POST, "txtQuantidade", FILTER_SANITIZE_STRING));
    $quantidadeFormatadoFinal = str_replace(",", ".", $quantidadeFormatado);
    $certificado->setQuantidade($quantidadeFormatadoFinal);
    
    $certificado->setNome(filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING));
    $certificado->setTipo(filter_input(INPUT_POST, "txtTipo", FILTER_SANITIZE_STRING));
    
    $certificado->setUsuario($_SESSION["cod"]);

    
    //Foto para editar
    $fotoEditar = filter_input(INPUT_POST, "txtFoto", FILTER_SANITIZE_STRING);
    
    $certificado->setAtivo(filter_input(INPUT_POST, "slAtivo", FILTER_SANITIZE_STRING));
    
    
        $foto = $_FILES["foto"];
                	// Se a foto estiver sido selecionada
	if (!empty($foto["name"])) {
                
                // Largura máxima em pixels
		$largura = 7000;
		// Altura máxima em pixels
		$altura = 7000;
		// Tamanho máximo do arquivo em bytes
		$tamanho = 30000000;

		$error = array();

    	// Verifica se o arquivo é uma imagem
    	//if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"])){
                if(!preg_match("/^image\/(pjpeg|jpeg|png|x-png)$/", $foto["type"])){
                            $error[1] = "Formatado Inválido! Aceito jpeg, jpg, png e x-png";
   	 	} 
                  
                
		// Pega as dimensões da imagem
		$dimensoes = getimagesize($foto["tmp_name"]);
	
		// Verifica se a largura da imagem é maior que a largura permitida
		if($dimensoes[0] > $largura) {
			$error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
		}

		// Verifica se a altura da imagem é maior que a altura permitida
		if($dimensoes[1] > $altura) {
			$error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
		}
		
		// Verifica se o tamanho da imagem é maior que o tamanho permitido
		if($foto["size"] > $tamanho) {
   		 	$error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
		}

		// Se não houver nenhum erro
		if (count($error) == 0) {
         //------------------FOTO REDUZIDA------------------------------------------------------------------

    		// Pega extensão da imagem
	        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

                // Gera um nome único para a imagem
                $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
                
                   $certificado->setFoto($nome_imagem);

    
        $altura = "80";
	$largura = "80";
	//echo "Altura pretendida: $altura - largura pretendida: $largura <br>";
	
	switch($_FILES['foto']['type']):
		case 'image/jpeg';
		case 'image/pjpeg';
			$imagem_temporaria = imagecreatefromjpeg($_FILES['foto']['tmp_name']);
			
			$largura_original = imagesx($imagem_temporaria);
			
			$altura_original = imagesy($imagem_temporaria);
			
			//echo "largura original: $largura_original - Altura original: $altura_original <br>";
			
			$nova_largura = $largura ? $largura : floor (($largura_original / $altura_original) * $altura);
			
			$nova_altura = $altura ? $altura : floor (($altura_original / $largura_original) * $largura);
			
			$imagem_redimensionada = imagecreatetruecolor($nova_largura, $nova_altura);
			imagecopyresampled($imagem_redimensionada, $imagem_temporaria, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);
			
			//imagejpeg($imagem_redimensionada, 'thumb/' . $_FILES['foto']['name']);
                        
                        imagejpeg($imagem_redimensionada, 'thumb/' . $nome_imagem);
			
			//echo "<img src='thumb/".$_FILES['foto']['name']."'>";
			
			
		break;
		
		//Caso a imagem seja extensão PNG cai nesse CASE
		case 'image/png':
		case 'image/x-png';
			$imagem_temporaria = imagecreatefrompng($_FILES['foto']['tmp_name']);
			
			$largura_original = imagesx($imagem_temporaria);
			$altura_original = imagesy($imagem_temporaria);
			//echo "Largura original: $largura_original - Altura original: $altura_original <br> ";
			
			/* Configura a nova largura */
			$nova_largura = $largura ? $largura : floor(( $largura_original / $altura_original ) * $altura);

			/* Configura a nova altura */
			$nova_altura = $altura ? $altura : floor(( $altura_original / $largura_original ) * $largura);
			
			/* Retorna a nova imagem criada */
			$imagem_redimensionada = imagecreatetruecolor($nova_largura, $nova_altura);
			
			/* Copia a nova imagem da imagem antiga com o tamanho correto */
			//imagealphablending($imagem_redimensionada, false);
			//imagesavealpha($imagem_redimensionada, true);

			imagecopyresampled($imagem_redimensionada, $imagem_temporaria, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);
			
			//função imagejpeg que envia para o browser a imagem armazenada no parâmetro passado
			//imagepng($imagem_redimensionada, 'thumb/' . $_FILES['foto']['name']);
			imagejpeg($imagem_redimensionada, 'thumb/' . $nome_imagem);
                        
			//echo "<img src='thumb/" .$_FILES['foto']['name']. "'>";
		break;
	endswitch;
//------------------THUMB PRINCIPAL------------------------------------------------------------------
    
            $alturaThumbPrincipal = '420';
            $larguraThumbPrincipal = '420';
            $pasta = 'thumbprincipal/';
        
            //Metodo para gerar thumb principal
            $certificadoController->gerarThumb($foto, $alturaThumbPrincipal, $larguraThumbPrincipal, $pasta, $nome_imagem);
            


		}// Se não houver nenhum erro
                
                // Se houver mensagens de erro, exibe-as
		if (count($error) != 0) {
			foreach ($error as $erro) {
				echo $erro . "<br />";
			}
                        die(); 
		}
                
	} else { //Foto nao selecionada, mostra erro
                
        }	

        //die();
    

    $error = 0;
   
    if (!filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT)) {
            //Cadastrar
            //Atribui o nome da imagem
         
            if ($error == 0) {
                    if ($certificadoController->Cadastrar($certificado)) {              
                        ?>
                        <script>
                            document.cookie = "msg=1";
                            document.location.href = "?pagina=certificado";
                        </script>
                        <?php
                    } else {
                        $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar cadastrar.</div>";
                    }
            
            } else {
                $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar cadastrar a imagem.</div>";
            }
            
    } else {
            //Editar
            
            
            //remover foto para inserir a que foi atualizada
            
            // Se a foto foi selecionada
            $certificado->setId(filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING));

            $retorno = $certificadoController->RetornaCod($certificado->getId());

            $error = 0;
            if ($retorno->getId() > 0){ 
                $fotobanco =  $retorno->getFoto();
            } else {
                $error = 1;
            }
            
            	// Se a foto foi selecionada
            if (!empty($foto["name"])) {
            
                   if ($error == 0) {
                         // Removendo imagem das pastas para inserir a nova
                       
                        unlink("thumb/".$fotobanco."");
                        unlink("thumbprincipal/".$fotobanco."");


                   } else {
                        //Atribui o nome da imagem
                        $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar cadastrar a imagem.</div>";
                    }
                
                $certificado->setFoto($nome_imagem);
            } else {
                $certificado->setFoto($fotobanco);
            }
            
            if ($certificadoController->Editar($certificado)) {
               $paramScript =  "<script>document.cookie = 'msg=2';document.location.href = '?pagina=certificado&form=s&id=".$certificado->getId()."';</script>";

               ?>
               <?= $paramScript; ?>         
                <?php
            } else {
                $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar alterar.</div>";
            }
    }

        


    }

    //Buscar Certificado
    $data = "";

if (filter_input(INPUT_POST, "btnBuscar", FILTER_SANITIZE_STRING)) {

    
        $tipo = filter_input(INPUT_POST, "slTipoBusca", FILTER_SANITIZE_NUMBER_INT);
        
        

            $termo = filter_input(INPUT_POST, "txtTermo", FILTER_SANITIZE_STRING);
        


        $listaBusca = array();
        $listaBusca = $certificadoController->RetornarAll($termo, $tipo);

        if ($listaBusca != null) {
            $spResultadoBusca = "Exibindo dados";
            $titularCertificado = "CÓDIGO USUÁRIO: ".$listaBusca[0]->getUsuario()->getUsuario()->getId()." - CPF: ".$listaBusca[0]->getUsuario()->getCpf()." - NOME: ".$listaBusca[0]->getUsuario()->getNome();

        } else {
            $spResultadoBusca = "Dados não encontrado";
        }
        
} else {
            //carrega array com todos   
            $termoAll = "";
            $tipoAll = 0;
          
            $listaBusca = array();
            //$listaBusca = $certificadoController->RetornarAll($termoAll, $tipoAll, $data);
            $tipoEnvio = 1;
            $termo = $_SESSION["cod"];
            
            if ($_SESSION["permissao"] == 'USER'){
                
                $listaBusca = $certificadoController->RetornarAll($termo, $tipoEnvio);

            } else {
                $listaBusca = null;
                $listaBuscaPermissao = "Usuário logado é um administrador, pesquise por código ou cpf do Aluno...";
            }
                if ($listaBusca != null) {
                    $spResultadoBusca = "Exibindo dados... ";
                    $titularCertificado = "CÓDIGO USUÁRIO: ".$listaBusca[0]->getUsuario()->getUsuario()->getId()." - CPF: ".$listaBusca[0]->getUsuario()->getCpf()." - NOME: ".$listaBusca[0]->getUsuario()->getNome();
                } else {
                    $spResultadoBusca = "Dados não encontrado. ";
                }
}




$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT); 
 
if ($id) {
    $opcao = "Editar";
    
    $retorno = array();
    $retorno = $certificadoController->RetornaCod($id);

        $id = $retorno->getId();
        $quantidade = $retorno->getQuantidade();
        
        $data = $retorno->getData();
        $dataFormatada =  date('d/m/Y', strtotime($data)); 
        
        $quantidade =  $retorno->getQuantidade();
        $nome =  $retorno->getNome(); 
        $tipo = $retorno->getTipo(); 
        $fotoView = $retorno->getFoto();
        $ativo = $retorno->getAtivo();


} else {
    $dataFormatada = $dataHoje;
    $opcao = "Cadastrar";
}


?>

<div id="dvUsuarioView" class="container">
    <h1>Certificado</h1>
    <br />
    <div class="controlePaginas">
        <a href="?pagina=certificado"><img src="img/icones/buscar.png" alt=""/></a>
                <?php if ($_SESSION["permissao"] == 'USER'){   ?>
        <a href="?pagina=certificado&form=s"><img src="img/icones/editar.png" alt=""/></a>
                <?php }   ?>
    </div>

    <br />
    <!--DIV CADASTRO -->
    <?php
    if (filter_input(INPUT_GET, "form", FILTER_SANITIZE_STRING)) {
        ?>
        <div class="panel panel-default maxPanelWidth">
            <div class="panel-heading"><?= $opcao; ?></div>
            <div class="panel-body">
                <form method="post" id="frmGerenciar" name="frmGerenciar" enctype="multipart/form-data" novalidate>
                    <div class="row">
                        <div class="col-lg-12">
                            <p id="pResultado"><?= $resultado; ?></p>
                        </div>
                    </div>
                    
                    <div class="row">
                       <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="txtData">Data</label>
                            <input type="text" class="form-control" id="txtData" name="txtData" value="<?= $dataFormatada; ?>" />
                            <input type="hidden" name=txtFoto value="<?= $fotoView; ?>" />
                        </div>

                       </div>
                      <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="txtQuantidade">Quantidade (h)</label>
                                <input type="text" class="form-control" id="txtQuantidade" name="txtQuantidade" placeholder="Valor"  value="<?= $quantidade; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <div class="form-group">
                                <label for="txtNome">Nome</label>
                                <input type="text" class="form-control" id="txtNome" name="txtNome" placeholder=" " value="<?= $nome ?>"/>
                            </div>
                        </div>                      
                     </div>    

                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <div class="form-group">
                                <label for="txtTipo">Tipo</label>
                                <input type="text" class="form-control" id="txtTipo" name="txtTipo" placeholder=" " value="<?= $tipo ?>"/>
                            </div>
                        </div>                      
                     </div> 
                    
                    
                    <div class="row">
                       <div class="col-lg-6 col-xs-12">
                        <div class="form-group">
                            <label for="foto">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto" />
                        </div>
                       </div>
                        
                       <div class="col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="slAtivo">Status</label>
                                <select class="form-control" id="slAtivo" name="slAtivo">
                                 <?php if ($_SESSION["permissao"] == 'ADM'){   ?>   
                                    <option value="A" <?= ($ativo == "A" ? "selected='selected'" : "") ?>>Homologado</option>
                                 <?php }   ?>
                                    <option value="B" <?= ($ativo == "B" ? "selected='selected'" : "") ?>>Não Homologado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    

                    
                    <div class="row">
                       <div class="col-lg-12 col-xs-12">
                           <?php 
                           if ($fotoView){
                            echo "<img src='thumbprincipal/".$fotoView."' alt='Foto de exibição' /><br />";
                           }
                           ?>
                        </div>
                    </div>
                         <br />
                    <br />
                    
                    
                    <input class="btn btn-success" type="submit" name="btnGravar" value="Salvar">
                    <a href="?pagina=certificado" class="btn btn-danger">Cancelar</a>

                    <br />
                    <br />
                    </div>   
                    <div class="row">
                        <div class="col-lg-12">
                            <ul id="ulErros"></ul>
                        </div>
                    </div>
            </div>
        </div>
        <?php
    } else {
        ?>
        <br />
        <!--DIV CONSULTA -->
        <div class="panel panel-default maxPanelWidth">
            <div class="panel-heading"><?php echo $titularCertificado?></div>
            <div class="panel-body">
                <?php if ($_SESSION["permissao"] == 'ADM'){   ?>
                <form method="post" name="frmBuscar" id="frmBuscar">
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
                                    <option value="0" <?= ($tipo == 0 ? "selected='selected'" : "") ?>>Todos</option>
                                    <option value="1" <?= ($tipo == 1 ? "selected='selected'" : "") ?>>Código do Usuário</option> 
                                    <option value="2" <?= ($tipo == 2 ? "selected='selected'" : "") ?>>CPF</option>   
                                </select>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-xs-12">
                            <input class="btn btn-info" type="submit" name="btnBuscar" value="Buscar"> 
                            <span style="font-size: medium; font-weight: bold; color: #dc3545; text-align: right"><?= $spResultadoBusca.$listaBuscaPermissao; ?></span>
                        </div>
                    </div>
                </form>
                <?php }   ?>

                <hr />
                <br />
<div class="table-responsive">
                <table class="table table-responsive table-hover table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Imagem</th>
                            <th>Data</th>
                            <th>Nome</th>
                            <th>Quantidade(h)</th>      
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Link</th>
                            <?php if ($_SESSION["permissao"] == 'ADM'){   ?>
                            <th>Editar</th>
                            <th>Excluir</th>
                            <?php }   ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($listaBusca != null) {
                            $valorFormatado =  "";
                            
                            for($i = 0; $i < count($listaBusca); ++$i) {
                                $lista = $listaBusca[$i];
                                //Data
                                $data = $lista->getData();
                                $dataFormatada =  date('d/m/Y', strtotime($data));  
                                
                                //Valor Custo
                                
                                $quantidadeFormatada = number_format($lista->getQuantidade(),2,",","."); 
                                
         
                                
                                if ($lista->getAtivo() == 'A'){
                                    $habilitado = 'Homologado';
                                    $totalHoras = $totalHoras+$lista->getQuantidade();
                                } else {
                                    $habilitado = 'Não Homologado';
                                }
                                
                                ?>
                                <tr>
                                    <td><?= $lista->getId(); ?></td>
                                    <td><img src="thumb/<?= $lista->getFoto(); ?>" alt="IMG"></td>
                                    <td><?= $dataFormatada; ?></td>
                                    <td><a href="http://localhost/universidade/thumbprincipal/<?= $lista->getFoto(); ?>" target="_BLANK"><?= $lista->getNome(); ?></a></td>
                                    <td><?= $quantidadeFormatada;?></td>   
                                    <td><?= $lista->getTipo(); ?></td>
                                    
                                     <?php
                                     if ($lista->getAtivo() == 'A'){
                                     ?>
                                    <td style="color: white; background-color: green;"><?= $habilitado; ?></td>
                                     <?php
                                     } else {
                                     ?>
                                    <td style="color: white; background-color: #dc3545;"><?= $habilitado; ?></td>
                                     <?php
                                     } 
                                     ?>

                                    <td>
                                        <a href="http://localhost/universidade/thumbprincipal/<?= $lista->getFoto(); ?>" target="_BLANK" class="btn btn-info">Link</a>
 
                                    </td>
                                    
                                    <?php if ($_SESSION["permissao"] == 'ADM'){   ?>
                                    <td>
                                        
                                    <a href="?pagina=certificado&form=s&id=<?= $lista->getId(); ?>" class="btn btn-warning">Editar</a>
 
                                    </td>
                                    <td>
                                        <a href="?pagina=excluircertificado&id=<?= $lista->getId(); ?>" class="btn btn-danger" id="btnExcluir">Excluir</a>
                                    </td>
                                    <?php }   ?>
                                </tr>

                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
    <h2>Total de Horas: <?php echo $quantidadeHorasFormatada = number_format($totalHoras,2,",","."); ?> </h2>
</div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

    <script src="js/mask.js" type="text/javascript"></script>
    <script src="js/jquery.maskmoney.js" type="text/javascript"></script>
    
    
    
    
    
<script>
    $(document).ready(function () {
        
        $.fn.select2.defaults.set("width", "100%");
            $(".js-example-basic-single").select2({
		placeholder: "Selecione um tipo",
		  allowClear: true
	});
        
        $('#txtDataBusca').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            forceParse: true
        });
        
        $('#txtData').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            forceParse: true
        });
                


         $('#txtQuantidade').mask('000.000.000.000.000,00', {reverse: true});
         $('#txtValorVenda').mask('000.000.000.000.000,00', {reverse: true});
         

         
        
        if (getCookie("msg") == 1) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Cadastrado com sucesso.</div>";
            document.cookie = "msg=0";
        } else if (getCookie("msg") == 2) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Alterado com sucesso.</div>";
            document.cookie = "msg=0";
        } else if (getCookie("msg") == 3) {
            document.getElementById("pResultado").innerHTML = "<div class=\"alert alert-success\" role=\"alert\">Formatado Inválido! Aceito jpeg, jpg, png e x-png.</div>";
            document.cookie = "msg=0";
        }

        $("#frmGerenciar").submit(function (e) {
            if (!ValidarFormulario()) {
                e.preventDefault();
            }
        });

    });
    
        function ValidarData(data) {
        var dt = new Date();
        //21/08/1992
        var arrData = data.split("/");
        console.log((dt.getFullYear() - 80));
        if (arrData[0] > 0 && arrData[0] <= 31) {
            if (arrData[1] > 0 && arrData[1] <= 12) {
                if (arrData[2] > (dt.getFullYear() - 80) && arrData[1] <= dt.getFullYear()) {
                    return true;
                } else {
                    return false;
                }
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
        if (document.getElementById("txtQuantidade").value < 1) {
            var li = document.createElement("li");
            li.innerHTML = " Informe um valor válido";
            ulErros.appendChild(li);
            erros++;
        }
        
        
        if (!ValidarData($("#txtData").val())) {
            var li = document.createElement("li");
            li.innerText = "Data Prevista inválida";
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
