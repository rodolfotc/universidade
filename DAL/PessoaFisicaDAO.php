<?php

require_once("Banco.php");

class PessoaFisicaDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Dba();
        $this->debug = true;
    }

    public function Cadastrar(PessoaFisica $pessoafisica, $usuario_id) { 
        try{
            
            $sql = "INSERT INTO pessoaFisica (nome, email, cpf, nascimento, sexo, usuario_id) VALUES (:nome, :email, :cpf, :nascimento, :sexo, :usuario_id)";
            $param = array(
                ":nome" => $pessoafisica->getNome(),
                ":email" => $pessoafisica->getEmail(),
                ":cpf" => $pessoafisica->getCpf(),
                ":nascimento" => $pessoafisica->getNascimento(),
                ":sexo" => $pessoafisica->getSexo(),
                ":usuario_id" => $usuario_id,
            );
       
            return $this->pdo->ExecuteNonQuery($sql, $param);


        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }
    
    
    public function Editar(Usuario $usuario, PessoaFisica $pessoafisica) {
        try {


            $sql = "UPDATE pessoaFisica SET nome = :nome, sexo = :sexo, nascimento = :nascimento, email = :email, cpf = :cpf WHERE usuario_id = :usuario_id";
           
            $param = array(             
                ":nome" => $pessoafisica->getNome(),
                ":sexo" => $pessoafisica->getSexo(),
                ":nascimento" => $pessoafisica->getNascimento(),
                ":email" => $pessoafisica->getEmail(),
                ":cpf" => $pessoafisica->getCpf(), 
                ":usuario_id" => $usuario->getId(),
            );
            
            //print_r($sql);
            //print_r($param);
            //die;

            return $this->pdo->ExecuteNonQuery($sql, $param);
            
            
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }
    
    public function Excluir($usuario) {
        try {
         
            $sql = "DELETE from pessoaFisica WHERE usuario_id = :usuario_id";
           
            $param = array(             
                ":usuario_id" => $usuario,
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);

            
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }


}

?>