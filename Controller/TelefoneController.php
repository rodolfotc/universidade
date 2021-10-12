<?php
if (file_exists("../DAL/TelefoneDAO.php")) {
    require_once("../DAL/TelefoneDAO.php");
} else {
    require_once("DAL/TelefoneDAO.php");
}

class TelefoneController {

    private $telefoneDAO;

    public function __construct() {
        $this->telefoneDAO = new TelefoneDAO();
    }

    public function Cadastrar(Telefone $telefone) {
        if (strlen($telefone->getNumero()) > 5 ){
            return $this->telefoneDAO->Cadastrar($telefone);
        } else {
            return false;
        }
    }

    public function Excluir(int $cod) {
        if ($cod > 0) {
            return $this->telefoneDAO->Excluir($cod);
        } else {
            return false;
        }
    }
    
    public function ExcluirAll(int $cod) {
        if ($cod > 0) {
            return $this->telefoneDAO->ExcluirAll($cod);
        } else {
            return false;
        }
    }

    public function Alterar(Telefone $telefone) {
        if (strlen($telefone->getNumero()) > 5 ){
            return $this->telefoneDAO->Alterar($telefone);
        } else {
            return false;
        }
    }

    public function RetornarTelefones(int $usuarioCod) {
        if ($usuarioCod > 0) {
            return $this->telefoneDAO->RetornarTelefones($usuarioCod);
        } else {
            return null;
        }
    }

    public function RetornarCod(int $telefoneCod) {
        if ($telefoneCod > 0) {
            return $this->telefoneDAO->RetornarCod($telefoneCod);
        } else {
            return null;
        }
    }

}
