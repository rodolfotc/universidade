<?php

if (file_exists("../DAL/PessoaFisicaClienteDAO.php")) {
    require_once("../DAL/PessoaFisicaClienteDAO.php");
} else {
    require_once("DAL/PessoaFisicaClienteDAO.php");
}



class PessoaFisicaClienteController {

    private $PessoaFisicaClienteDAO;

    public function __construct() {
        $this->PessoaFisicaClienteDAO = new PessoaFisicaClienteDAO();
    }

    public function Cadastrar(PessoaFisica $pessoafisica, $id) {
        if (strlen($pessoafisica->getNome()) >= 3) {
        
            return $this->PessoaFisicaClienteDAO->Cadastrar($pessoafisica, $id);

        } else {
            return false;
        }
    }
    
    public function Editar(Cliente $cliente, PessoaFisica $pessoafisica) {
        if (strlen($pessoafisica->getNome()) >= 3 && strlen($pessoafisica->getCpf()) == 14 ) {
                 return $this->PessoaFisicaClienteDAO->Editar($cliente, $pessoafisica);
            
           
        } else {
            return false;
        }
    }
    
        public function Excluir($usuario) {

        if($usuario > 0){


          return $this->PessoaFisicaClienteDAO->Excluir($usuario);
            
           
        } else {
            return false;
        }
    }

}
