<?php

if (file_exists("../DAL/UsuarioDAO.php")) {
    require_once("../DAL/UsuarioDAO.php");
} else {
    require_once("DAL/UsuarioDAO.php");
}

require_once("PessoaFisicaController.php");

class UsuarioController{

    private $usuarioDAO;
    private $banco;
    private $pessoafisica;
    private $retornoUsuario;
    private $retornoPessoaFisica;
    private $lastId;
    private $lastIdPessoaFisica;
    private $retornoEditarUsuario;
    private $retornoEditarPessoaFisica;
    private $retornoExcluirUsuario;
    private $retornoExcluirPessoaFisica;




    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        $this->pessoafisica = new PessoaFisicaController();
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

    
    public function Cadastrar(Usuario $usuario, PessoaFisica $pessoafisica) {
        if (strlen($pessoafisica->getNome()) >= 3 && strlen($usuario->getLogin()) >= 3 && strlen($usuario->getPassword()) >= 4 && strpos($pessoafisica->getEmail(), "@") && strpos($pessoafisica->getEmail(), ".") &&
            strlen($pessoafisica->getCpf()) == 14 && $pessoafisica->getSexo() != "" && $usuario->getPermissao() == "ADM" || $usuario->getPermissao() == "USER" && $usuario->getStatus() == "A" || $usuario->getStatus() == "B") {
        
            $this->retornoUsuario = $this->usuarioDAO->Cadastrar($usuario);

            //Depois de cadastrar usuario, checa o id e cdastra pessoa fisica
                $this->lastId = $this->banco->GetLastID();               
                if ($this->lastId > 0){

                     $this->retornoPessoaFisica = $this->pessoafisica->Cadastrar($pessoafisica, $this->lastId);
              
                      $this->lastIdPessoaFisica =  $this->banco->GetLastID();
                 }
                 //Validacao    

                
                
                 
                 if ($this->retornoPessoaFisica == 1 && $this->retornoUsuario ==1){
                     return true;
                 } else {
                
                     return false;
        
                 }

             //Validacao reprovada     
        } else {
            return false;
        }
    }

    public function Editar(Usuario $usuario, PessoaFisica $pessoafisica) {
       
    if (strlen($pessoafisica->getNome()) >= 3 && strlen($usuario->getLogin()) >= 3 && strpos($pessoafisica->getEmail(), "@") && strpos($pessoafisica->getEmail(), ".") &&
            strlen($pessoafisica->getCpf()) == 14 && $pessoafisica->getSexo() != "" && $usuario->getPermissao() == "ADM" || $usuario->getPermissao() == "USER" && $usuario->getStatus() == "A" || $usuario->getStatus() == "B") {

            $this->retornoEditarUsuario = $this->usuarioDAO->Editar($usuario);
        
            if ($this->retornoEditarUsuario){

                   $this->retornoEditarPessoaFisica = $this->pessoafisica->Editar($usuario,$pessoafisica);
    
            }
            
            if ($this->retornoEditarUsuario == 1 && $this->retornoEditarPessoaFisica == 1){
                return true;
            } else {
                return false;
            }
           
        } else {
            return false;
        }
    }

    public function Excluir($usuario) {
      
       
     if($usuario > 0){
           $this->retornoExcluirPessoaFisica = $this->pessoafisica->Excluir($usuario);  
            if ($this->retornoExcluirPessoaFisica){

               $this->retornoExcluirUsuario = $this->usuarioDAO->Excluir($usuario);    
    
            }
            
            if ($this->retornoExcluirUsuario == 1 && $this->retornoExcluirPessoaFisica == 1){
                return true;
            } else {
                return false;
            }
           
        } else {
            return false;
        }
    }
    
    public function RetornarUsuarios(string $termo, int $tipo) {
            return $this->usuarioDAO->RetornarUsuarios($termo, $tipo);

    }

    public function RetornaCod(int $usuarioId) {
        if ($usuarioId > 0) {
            return $this->usuarioDAO->RetornaCod($usuarioId);
        } else {
            return null;
        }
    }

    public function AutenticarUsuario(string $usu, string $senha, int $permissao) {

        if (strlen($usu) >= 3 && strlen($senha) >= 4 && $permissao == 1 ) {
            return $this->usuarioDAO->AutenticarUsuario($usu, md5($senha), $permissao);
        } else {
            return null;
        }
    }

    public function AlterarSenha(string $senha, int $id) {
        if (strlen($senha) >= 4 && $id > 0) {
            return $this->usuarioDAO->AlterarSenha(md5($senha), $id);
        } else {
            return false;
        }
    }

    public function VerificaUsuarioExiste(string $user) {
        if (strlen($user) >= 3) {
            return $this->usuarioDAO->VerificaUsuarioExiste($user);
        } else {
            -10;
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
