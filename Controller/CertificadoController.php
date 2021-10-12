<?php
if (file_exists("../DAL/CertificadoDAO.php")) {
    require_once("../DAL/CertificadoDAO.php");
} else {
    require_once("DAL/CertificadoDAO.php");
}


class CertificadoController{

    private $DAO;
    private $operacao;
    private $retornoExcluir;
    private $retorno;


    public function __construct() {

        $this->DAO = new CertificadoDAO();
        $this->retorno = "";
    }
    

    
    public function Cadastrar(Certificado $Certificado) {
        
            $this->retorno = $this->DAO->Cadastrar($Certificado);

            return  $this->retorno;
             //Validacao reprovada     

    }

    public function Editar(Certificado $Certificado) {
       
        
             $this->retorno = $this->DAO->Editar($Certificado);

            return  $this->retorno;
   

    }
    
    public function EditarQuantidade(Certificado $Certificado) {
       
        if ($Certificado->getQuantidade() >= 0) {
        
             $this->retorno = $this->DAO->EditarQuantidade($Certificado);

            return  $this->retorno;
   
        } else {
            return false;
        }
    }

    public function Excluir($id) {
      
       
     if($id > 0){
           $this->retorno = $this->DAO->Excluir($id); 
           
                return $this->retorno;
           
        } else {
            return false;
        }
    }
    
    public function RetornarAll(string $termo, int $tipo) {
            return $this->DAO->RetornarAll($termo, $tipo);
    }
    
    public function RetornarAllSite(string $termo, int $tipo) {
            return $this->DAO->RetornarAllSite($termo, $tipo);
    }
    
    public function RetornarAllRelatorioCertificado(string $termo, int $tipo, $dtInicio, $dtTermino) {
            return $this->DAO->RetornarAll($termo, $tipo, $dtInicio, $dtTermino);
    }
    
    public function RetornarAllSelect(string $termo, int $tipo) {
            return $this->DAO->RetornarAllSelect($termo, $tipo);
    }

    public function RetornaCod(int $id) {
        if ($id > 0) {
            return $this->DAO->RetornaCod($id);
        } else {
            return null;
        }
    }
    
    public function gerarThumb($foto, $altura, $largura, $pasta, $nome_imagem) {
        // Se a foto estiver sido selecionada
	if (!empty($foto)) {
    		// Pega extensão da imagem
	        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

                // Gera um nome único para a imagem
                //$nome_imagem = md5(uniqid(time())) . "." . $ext[1];

    
        //$altura = "80";
	//$largura = "80";
	//echo "Altura pretendida: $altura - largura pretendida: $largura <br>";
	
	switch($foto['type']):
		case 'image/jpeg';
		case 'image/pjpeg';
			$imagem_temporaria = imagecreatefromjpeg($foto['tmp_name']);
			
			$largura_original = imagesx($imagem_temporaria);
			
			$altura_original = imagesy($imagem_temporaria);
			
			//echo "largura original: $largura_original - Altura original: $altura_original <br>";
			
			$nova_largura = $largura ? $largura : floor (($largura_original / $altura_original) * $altura);
			
			$nova_altura = $altura ? $altura : floor (($altura_original / $largura_original) * $largura);
			
			$imagem_redimensionada = imagecreatetruecolor($nova_largura, $nova_altura);
			imagecopyresampled($imagem_redimensionada, $imagem_temporaria, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);
			
			//imagejpeg($imagem_redimensionada, 'thumb/' . $_FILES['foto']['name']);
                        
                        //imagejpeg($imagem_redimensionada, 'thumbprincipal/' . $nome_imagem);
                        imagejpeg($imagem_redimensionada, $pasta . $nome_imagem);
			
			//echo "<img src='thumb/".$_FILES['foto']['name']."'>";
			
			
		break;
		
		//Caso a imagem seja extensão PNG cai nesse CASE
		case 'image/png':
		case 'image/x-png';
			$imagem_temporaria = imagecreatefrompng($foto['tmp_name']);
			
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
			//imagejpeg($imagem_redimensionada, 'thumbprincipal/' . $nome_imagem);
                        imagejpeg($imagem_redimensionada, $pasta . $nome_imagem);
                        
			//echo "<img src='thumb/" .$_FILES['foto']['name']. "'>";
		break;
	endswitch;
        }
        
        

    }
    
    public function gerarThumbListaBanco($foto, $altura, $largura, $pasta, $nome_imagem, $caminho) {
        // Se a foto estiver sido selecionada
	if (!empty($foto)) {
    		// Pega extensão da imagem
	        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

                // Gera um nome único para a imagem
                //$nome_imagem = md5(uniqid(time())) . "." . $ext[1];

    
        //$altura = "80";
	//$largura = "80";
	//echo "Altura pretendida: $altura - largura pretendida: $largura <br>";
	
	switch($foto['type']):
		case 'image/jpeg';
		case 'image/pjpeg';
			$imagem_temporaria = imagecreatefromjpeg($caminho.$nome_imagem);
			
			$largura_original = imagesx($imagem_temporaria);
			
			$altura_original = imagesy($imagem_temporaria);
			
			//echo "largura original: $largura_original - Altura original: $altura_original <br>";
			
			$nova_largura = $largura ? $largura : floor (($largura_original / $altura_original) * $altura);
			
			$nova_altura = $altura ? $altura : floor (($altura_original / $largura_original) * $largura);
			
			$imagem_redimensionada = imagecreatetruecolor($nova_largura, $nova_altura);
			imagecopyresampled($imagem_redimensionada, $imagem_temporaria, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);
			
			//imagejpeg($imagem_redimensionada, 'thumb/' . $_FILES['foto']['name']);
                        
                        //imagejpeg($imagem_redimensionada, 'thumbprincipal/' . $nome_imagem);
                        imagejpeg($imagem_redimensionada, $pasta . $nome_imagem);
			
			//echo "<img src='thumb/".$_FILES['foto']['name']."'>";
			
			
		break;
		
		//Caso a imagem seja extensão PNG cai nesse CASE
		case 'image/png':
		case 'image/x-png';
			$imagem_temporaria = imagecreatefrompng($caminho.$nome_imagem);
			
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
			//imagejpeg($imagem_redimensionada, 'thumbprincipal/' . $nome_imagem);
                        imagejpeg($imagem_redimensionada, $pasta . $nome_imagem);
                        
			//echo "<img src='thumb/" .$_FILES['foto']['name']. "'>";
		break;
	endswitch;
        }
        
        

    }







}
