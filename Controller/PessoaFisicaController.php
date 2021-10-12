<?php

if (file_exists("../DAL/PessoaFisicaDAO.php")) {
    require_once("../DAL/PessoaFisicaDAO.php");
} else {
    require_once("DAL/PessoaFisicaDAO.php");
}



class PessoaFisicaController {

    private $PessoaFisicaDAO;

    public function __construct() {
        $this->PessoaFisicaDAO = new PessoaFisicaDAO();
    }

    public function Cadastrar(PessoaFisica $pessoafisica, $id) {
        if (strlen($pessoafisica->getNome()) >= 3 &&  strpos($pessoafisica->getEmail(), "@") && strpos($pessoafisica->getEmail(), ".") &&
            strlen($pessoafisica->getCpf()) == 14 && $pessoafisica->getSexo() != "" ) {
        
            return $this->PessoaFisicaDAO->Cadastrar($pessoafisica, $id);

        } else {
            return false;
        }
    }
    
    public function Editar(Usuario $usuario, PessoaFisica $pessoafisica) {
        if (strlen($pessoafisica->getNome()) >= 3 &&  strpos($pessoafisica->getEmail(), "@") && strpos($pessoafisica->getEmail(), ".") &&
            strlen($pessoafisica->getCpf()) == 14 && $pessoafisica->getSexo() != "" ) {
                 return $this->PessoaFisicaDAO->Editar($usuario, $pessoafisica);
            
           
        } else {
            return false;
        }
    }
    
        public function Excluir($usuario) {

        if($usuario > 0){


          return $this->PessoaFisicaDAO->Excluir($usuario);
            
           
        } else {
            return false;
        }
    }

}
