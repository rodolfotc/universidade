<?php
require_once("Banco.php");

class CertificadoDAO {

    private $pdo;
    private $debug;
    private $filterOff;

    public function __construct() {
        $this->pdo = new Dba();
        $this->debug = true;
        $this->filterOff = false;
    }

    public function Cadastrar(Certificado $certificado) {
        try {

            $sql = "INSERT INTO certificado (data, nome, quantidade, tipo, foto, arquivo, ativo, usuario_id) VALUES (:data, :nome, :quantidade, :tipo, :foto, :arquivo, :ativo, :usuario_id)";
            $param = array(
                ":data" => $certificado->getData(),
                ":nome" => $certificado->getNome(),
                ":quantidade" => $certificado->getQuantidade(),
                ":tipo" => $certificado->getTipo(),
                ":foto" => $certificado->getFoto(),
                ":arquivo" => $certificado->getArquivo(),
                ":ativo" => $certificado->getAtivo(),
                ":usuario_id" => $certificado->getUsuario(),
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
            
            
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }
    
        public function Editar(Certificado $certificado) {
        try {
            
            //echo '<pre>'.print_r($certificado, true).'</pre>';

            $sql = "UPDATE certificado SET data = :data,nome = :nome,quantidade = :quantidade,tipo = :tipo,foto = :foto, arquivo = :arquivo, ativo = :ativo  where id = :id";
            $param = array(      
                ":id" => $certificado->getId(),
                ":data" => $certificado->getData(),
                ":nome" => $certificado->getNome(),
                ":quantidade" => $certificado->getQuantidade(),
                ":tipo" => $certificado->getTipo(),
                ":foto" => $certificado->getFoto(),
                ":arquivo" => $certificado->getArquivo(),
                ":ativo" => $certificado->getAtivo(),
                //":usuario_id" => $certificado->getUsuario(),
                
            );
            return $this->pdo->ExecuteNonQuery($sql, $param);
            
            
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }
    
     public function EditarQuantidade(Certificado $certificado) {
        try {

            $sql = "UPDATE produto SET quantidade = :quantidade where id = :id";
           
            $param = array(      
                ":id" => $certificado->getId(),
                ":quantidade" => $certificado->getQuantidade(),
                
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
            
            
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }
    
       public function Excluir($id) {
        try {
         
            $sql = "DELETE from certificado WHERE id = :id";
           
            $param = array(             
                ":id" => $id,
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
            
            
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarAll(string $termo, int $tipo) {
        try {
            $sql = "";
            
            switch ($tipo) {
                //Todos
                case 0:
                    //$sql = "SELECT id, data, nome, quantidade, tipo, foto, arquivo, ativo, usuario_id FROM certificado ";
                    $sql = "select usuario.id,pessoaFisica.nome, pessoaFisica.cpf, usuario.login, usuario.status, usuario.permissao, certificado.id as idCertificado, certificado.data, certificado.nome as nomeCertidicado, certificado.quantidade, certificado.tipo, certificado.foto, certificado.arquivo, certificado.ativo from usuario inner join pessoaFisica on usuario.id = pessoaFisica.usuario_id inner join certificado on certificado.usuario_id = usuario.id order by certificado.id desc";
                    $this->filterOff = 1;
                    break;
                //Data
                case 1:
                    //$sql = "SELECT id, ativo, pessoajuridica_id, data, nome, valorCusto, valorVenda, quantidade, descricao, foto FROM produto WHERE data LIKE :termo  order by id DESC";
                    $sql = "select usuario.id,pessoaFisica.nome, pessoaFisica.cpf, usuario.login, usuario.status, usuario.permissao, certificado.id as idCertificado, certificado.data, certificado.nome as nomeCertificado, certificado.quantidade, certificado.tipo, certificado.foto, certificado.arquivo, certificado.ativo from usuario inner join pessoaFisica on usuario.id = pessoaFisica.usuario_id inner join certificado on certificado.usuario_id = usuario.id where usuario.id = :termo  order by usuario.id desc";
                    break;
                case 2:
                    //$sql = "SELECT id, ativo, pessoajuridica_id, data, nome, valorCusto, valorVenda, quantidade, descricao, foto FROM produto WHERE quantidade LIKE :termo  order by id DESC";
                     $sql = "select usuario.id,pessoaFisica.nome, pessoaFisica.cpf, usuario.login, usuario.status, usuario.permissao, certificado.id as idCertificado, certificado.data, certificado.nome as nomeCertificado, certificado.quantidade, certificado.tipo, certificado.foto, certificado.arquivo, certificado.ativo from usuario inner join pessoaFisica on usuario.id = pessoaFisica.usuario_id inner join certificado on certificado.usuario_id = usuario.id where pessoaFisica.cpf = :termo  order by usuario.id desc";
                    break;
            }
            
            
            if ($this->filterOff == 0){
                        $param = array(
                           ":termo" => $termo,
                        );
                        
                        
                    $dataTable = $this->pdo->ExecuteQuery($sql, $param);

                    
                } else {
                    $dataTable = $this->pdo->ExecuteQuery($sql);   
            }
            
            $lista = [];

            foreach ($dataTable as $resultado) {
                $certificado = new Certificado();
                
                $usuario = new Usuario();
                $pessoaFisica = new PessoaFisica();
                
                $usuario->setLogin($resultado["login"]);
                $usuario->setStatus($resultado["status"]);
                $usuario->setPermissao($resultado["permissao"]);
                $usuario->setId($resultado["id"]);
                $pessoaFisica->setNome($resultado["nome"]);
                $pessoaFisica->setCpf($resultado["cpf"]);
                $pessoaFisica->setUsuario($usuario);

                
                $certificado->setId($resultado["idCertificado"]);
                $certificado->setData($resultado["data"]);
                $certificado->setNome($resultado["nomeCertificado"]);
                $certificado->setQuantidade($resultado["quantidade"]); 
                $certificado->setTipo($resultado["tipo"]); 
                $certificado->setFoto($resultado["foto"]);
                $certificado->setArquivo($resultado["arquivo"]); 
                $certificado->setAtivo($resultado["ativo"]);
                $certificado->setUsuario($pessoaFisica);
                
                

                $lista[] = $certificado;

               
            }

            
            return ($lista);

        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }
    
    public function RetornarAllSite(string $termo, int $tipo) {
        try {
            $sql = "";
            

            
            switch ($tipo) {
                //Todos
                case 0:
                    $sql = "SELECT id, data, nome, valorCusto, valorVenda, quantidade, descricao, foto FROM produto WHERE ativo = 'A' order by quantidade DESC LIMIT 60 ";
                    $this->filterOff = 1;
                    break;
                case 1:
                    $sql = "SELECT id, data, nome, valorCusto, valorVenda, quantidade, descricao, foto FROM produto WHERE ativo = 'A' and id = :termo  order by id DESC";  
                    break;
                case 2:
                    $sql = "SELECT id, data, nome, valorCusto, valorVenda, quantidade, descricao, foto FROM produto WHERE ativo = 'A' and nome LIKE :termo  order by id DESC";
                    break;               
                case 3:
                    $sql = "SELECT id, data, nome, valorCusto, valorVenda, quantidade, descricao, foto FROM produto WHERE ativo = 'A' and descricao LIKE :termo  order by id DESC";  
                    break;
                case 4:

                //Valor 
                    $sql = "SELECT id, data, nome, valorCusto, valorVenda, quantidade, descricao, foto FROM produto WHERE ativo = 'A' and valorVenda <= :termo  order by id DESC";  

                    break;
            }
            
            if ($this->filterOff == 0){
                    if ($tipo == 1 or $tipo == 4 ){
                        $param = array(
                           ":termo" => "{$termo}",
                        );
                    } else {
                        $param = array(
                           ":termo" => "%{$termo}%",
                        );         
                    }
                        
                        

                    $dataTable = $this->pdo->ExecuteQuery($sql, $param);


                    
                } else {
                    $dataTable = $this->pdo->ExecuteQuery($sql);   
            }
            
            $lista = [];

            foreach ($dataTable as $resultado) {
                $certificado = new Certificado();

                
                $certificado->setId($resultado["id"]);
                $certificado->setData($resultado["data"]);
                $certificado->setQuantidade($resultado["quantidade"]); 
                $certificado->setNome($resultado["nome"]);
                $certificado->setDescricao($resultado["descricao"]); 
                $certificado->setValorCusto($resultado["valorCusto"]); 
                $certificado->setValorVenda($resultado["valorVenda"]); 
                $certificado->setFoto($resultado["foto"]); 

                $lista[] = $certificado;

               
            }

            
            return ($lista);

        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }
    
        public function RetornarAllSelect(string $termo, int $tipo) {
        try {
            $sql = "";
            
            switch ($tipo) {
                case 0:
                    $sql = "SELECT id, data, nome, valorCusto, valorVenda, quantidade, descricao, foto FROM produto order by id DESC";
                    $this->filterOff = 1;
                    break;
                case 1:
                    $sql = "select p.id, p.valor, p.dataVencimento, p.tipoDespesa_id, p.tipoDespesa_categoriaDespesa_id, t.descricao as tipo, c.descricao as categoria from produto p inner join tipoDespesa t on p.tipoDespesa_id = t.id inner join categoriaDespesa c on p.tipoDespesa_categoriaDespesa_id = c.id WHERE t.descricao LIKE :termo  order by p.dataVencimento desc";
                    break;
                case 2:
                    $sql = "select p.id, p.valor, p.dataVencimento, p.tipoDespesa_id, p.tipoDespesa_categoriaDespesa_id, t.descricao as tipo, c.descricao as categoria from produto p inner join tipoDespesa t on p.tipoDespesa_id = t.id inner join categoriaDespesa c on p.tipoDespesa_categoriaDespesa_id = c.id WHERE c.descricao LIKE :termo  order by p.dataVencimento desc";
                    break;
                case 3:
                    $sql = "select p.id, p.valor, p.dataVencimento, p.tipoDespesa_id, p.tipoDespesa_categoriaDespesa_id, t.descricao as tipo, c.descricao as categoria from produto p inner join tipoDespesa t on p.tipoDespesa_id = t.id inner join categoriaDespesa c on p.tipoDespesa_categoriaDespesa_id = c.id WHERE p.dataVencimento LIKE :termo  order by p.dataVencimento desc";
                    break;
            }
            if ($this->filterOff == 0){
                    $param = array(
                        ":termo" => "%{$termo}%"
                    );
                        
                    $dataTable = $this->pdo->ExecuteQuery($sql, $param);
                } else {
                    $dataTable = $this->pdo->ExecuteQuery($sql);   
            }
            
            $lista = [];

            foreach ($dataTable as $resultado) {
                $certificado = new Certificado();

                
                $certificado->setId($resultado["id"]);
                $certificado->setData($resultado["data"]);
                $certificado->setQuantidade($resultado["quantidade"]); 
                $certificado->setNome($resultado["nome"]);
                $certificado->setDescricao($resultado["descricao"]); 
                $certificado->setValorCusto($resultado["valorCusto"]); 
                $certificado->setValorVenda($resultado["valorVenda"]); 
                

                $lista[] = $certificado;

               
            }

            
            return ($lista);

        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornaCod(int $id) {
        try {
            $sql = "SELECT id, data, nome, quantidade, tipo, foto, arquivo, ativo, usuario_id FROM certificado where id = :id";
            $param = array(
                ":id" => $id
            );

            $resultado = $this->pdo->ExecuteQueryOneRow($sql, $param);

            if ($resultado != null) {
                
                $certificado = new Certificado();

                
                $certificado->setId($resultado["id"]);
                $certificado->setData($resultado["data"]);
                $certificado->setNome($resultado["nome"]);
                $certificado->setQuantidade($resultado["quantidade"]); 
                $certificado->setTipo($resultado["tipo"]); 
                $certificado->setFoto($resultado["foto"]);
                $certificado->setArquivo($resultado["arquivo"]); 
                $certificado->setAtivo($resultado["ativo"]);
                $certificado->setUsuario($resultado["usuario_id"]);



                return $certificado;
            } else {
                return null;
            }
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }






}

?>