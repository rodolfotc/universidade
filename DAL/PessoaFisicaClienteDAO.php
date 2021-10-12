<?php

require_once("Banco.php");

class PessoaFisicaClienteDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Dba();
        $this->debug = true;
    }

    public function Cadastrar(PessoaFisica $pessoafisica, $cliente_id) { 
        try{
            
            if (!empty($pessoafisica->getNascimento())){
                $nascimento = $pessoafisica->getNascimento();
            } else {
                $nascimento = null;
            }
            
            
            $sql = "INSERT INTO pessoaFisica (nome, email, cpf, nascimento, sexo, cliente_id) VALUES (:nome, :email, :cpf, :nascimento, :sexo, :cliente_id)";
            $param = array(
                ":nome" => $pessoafisica->getNome(),
                ":email" => $pessoafisica->getEmail(),
                ":cpf" => $pessoafisica->getCpf(),
                ":nascimento" => $nascimento,
                ":sexo" => $pessoafisica->getSexo(),
                ":cliente_id" => $cliente_id,
            );
       
            return $this->pdo->ExecuteNonQuery($sql, $param);


        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }
    
    
    public function Editar(Cliente $cliente, PessoaFisica $pessoafisica) {
        try {


            $sql = "UPDATE pessoaFisica SET nome = :nome, sexo = :sexo, nascimento = :nascimento, email = :email, cpf = :cpf WHERE cliente_id = :cliente_id";
           
            $param = array(             
                ":nome" => $pessoafisica->getNome(),
                ":sexo" => $pessoafisica->getSexo(),
                ":nascimento" => $pessoafisica->getNascimento(),
                ":email" => $pessoafisica->getEmail(),
                ":cpf" => $pessoafisica->getCpf(), 
                ":cliente_id" => $cliente->getId(),
            );
            

            return $this->pdo->ExecuteNonQuery($sql, $param);
            
            
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }
    
    public function Excluir($cliente) {
        try {
         
            $sql = "DELETE from pessoaFisica WHERE cliente_id = :cliente_id";
           
            $param = array(             
                ":cliente_id" => $cliente,
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