<?php
if (file_exists("../DAL/ClienteDAO.php")) {
    require_once("../DAL/ClienteDAO.php");
} else {
    require_once("DAL/ClienteDAO.php");
}

require_once("PessoaFisicaClienteController.php");
require_once("EnderecoController.php");
require_once("TelefoneController.php");

class ClienteController {

    private $clienteDAO;
    private $banco;
    private $pessoafisica;
    private $retornoCliente;
    private $retornoPessoaFisica;
    private $endereco;
    private $telefone;
    private $lastId;
    private $lastIdPessoaFisica;
    private $retornoEdtCliente;
    private $retornoEditarPessoaFisica;
    private $retornoExcluirCliente;
    private $retornoExcluirPessoaFisica;




    public function __construct() {
        $this->clienteDAO = new ClienteDAO();
        $this->pessoafisica = new PessoaFisicaClienteController();
        $this->endereco = new EnderecoController();
        $this->telefone = new TelefoneController();
        $this->banco = new Dba();
        $this->lastId = 0;
        $this->lastIdPessoaFisica = 0;
      
    }
    
    function getLastId() {
        return $this->lastId;
    }

    function getLastIdPessoaFisica() {
        return $this->lastIdPessoaFisica;
    }

    function setLastId($lastId) {
        $this->lastId = $lastId;
    }

    function setLastIdPessoaFisica($lastIdPessoaFisica) {
        $this->lastIdPessoaFisica = $lastIdPessoaFisica;
    }

    
    public function Cadastrar(Cliente $cliente, PessoaFisica $pessoafisica) {
       if (strlen($pessoafisica->getNome()) >= 3) {
       // if (strlen($pessoafisica->getNome()) >= 3){
          // print_r($pessoafisica->getNascimento()."/");

            $this->retornoCliente = $this->clienteDAO->Cadastrar($cliente);

            //Depois de cadastrar usuario, checa o id e cdastra pessoa fisica
                $this->lastId = $this->banco->GetLastID();               
                if ($this->lastId > 0){                  

                     $this->retornoPessoaFisica = $this->pessoafisica->Cadastrar($pessoafisica, $this->lastId);

                      $this->lastIdPessoaFisica =  $this->banco->GetLastID();
                 }
                 //Validacao    
                 
                 if ($this->retornoPessoaFisica == 1 && $this->retornoCliente ==1){
                     return true;
                 } else {
                     return false;
                 }

             //Validacao reprovada     
        } else {
            return false;
        }
    }

    public function Editar(Cliente $cliente, PessoaFisica $pessoafisica) {
       
   // if (strlen($pessoafisica->getNome()) >= 3 && strlen($usuario->getLogin()) >= 3 && strpos($pessoafisica->getEmail(), "@") && strpos($pessoafisica->getEmail(), ".") &&
     //       strlen($pessoafisica->getCpf()) == 14 && $pessoafisica->getSexo() != "" && $usuario->getPermissao() == "ADM" || $usuario->getPermissao() == "USER" && $usuario->getStatus() == "A" || $usuario->getStatus() == "B") {
   
         if (strlen($pessoafisica->getNome()) >= 3 && strlen($pessoafisica->getCpf()) == 14) {
        
        //if (strlen($pessoafisica->getNome()) >= 3){
            $this->retornoEdtCliente = $this->clienteDAO->Editar($cliente);
        
            if ($this->retornoEdtCliente){

                   $this->retornoEditarPessoaFisica = $this->pessoafisica->Editar($cliente,$pessoafisica);
    
            }
            
            if ($this->retornoEdtCliente == 1 && $this->retornoEditarPessoaFisica == 1){
                return true;
            } else {
                return false;
            }
           
        } else {
            return false;
        }
    }

    public function Excluir($idCli) { 
     if($idCli > 0){
         //excluir endereço
         $retornoExluirEndereco = $this->endereco->ExcluirAll($idCli); 
                 //excluir telefone     
            $retornoExcluirTelefone = $this->telefone->ExcluirAll($idCli);
            

                    //excluir pessoa fisica e cliente
                     $this->retornoExcluirPessoaFisica = $this->pessoafisica->Excluir($idCli);  
                     if ($this->retornoExcluirPessoaFisica){

                        $this->retornoExcluirCliente = $this->clienteDAO->Excluir($idCli);    

                     }             
            
 
         
         

            if ($this->retornoExcluirCliente == 1 && $this->retornoExcluirPessoaFisica == 1){
                return true;
            } else {
                return false;
            }
           
        } else {
            return false;
        }
    }
    
    public function RetornarClientes(string $termo, int $tipo) {
            return $this->clienteDAO->RetornarClientes($termo, $tipo);

    }

    public function RetornaCod(int $clienteId) {
        if ($clienteId > 0) {
            return $this->clienteDAO->RetornaCod($clienteId);
        } else {
            return null;
        }
    }


    public function VerificaEmailExiste(string $email) {
        if (strpos($email, "@") > 0 && strpos($email, ".") > 0) {
            return $this->usuarioDAO->VerificaEmailExiste($email);
        } else {
            -10;
        }
    }

    public function VerificaCPFExiste(string $cpf) {
        if (strlen($cpf) == 14) {
            return $this->usuarioDAO->VerificaCPFExiste($cpf);
        } else {
            -10;
        }
    }

}
