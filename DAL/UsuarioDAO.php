<?php
require_once("PessoaFisicaDAO.php");
require_once("Banco.php");

class UsuarioDAO {

    private $pdo;
    private $debug;
    private $filterOff;

    public function __construct() {
        $this->pdo = new Dba();
        $this->debug = true;
        $this->filterOff = false;
    }

    public function Cadastrar(Usuario $usuario) {
        try {
            $sql = "INSERT INTO usuario (login, status, permissao, ip, password) VALUES (:login, :status, :permissao, :ip, :password)";
            $param = array(
                ":login" => $usuario->getLogin(),               
                ":status" => $usuario->getStatus(),
                ":permissao" => $usuario->getPermissao(),
                ":ip" => $_SERVER["REMOTE_ADDR"],
                ":password" => $usuario->getPassword(),
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
            
            
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }
    
        public function Editar(Usuario $usuario) {
        try {

            $sql = "UPDATE usuario SET login = :login, status = :status, permissao = :permissao, ip = :ip WHERE id = :id";
           
            $param = array(             
                ":login" => $usuario->getLogin(),               
                ":status" => $usuario->getStatus(),
                ":permissao" => $usuario->getPermissao(),
                ":ip" => $_SERVER["REMOTE_ADDR"],
                ":id" => $usuario->getId(),
            );

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
         
            $sql = "DELETE from usuario WHERE id = :id";
           
            $param = array(             
                ":id" => $usuario,
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
            
            
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarUsuarios(string $termo, int $tipo) {
        try {
            $sql = "";
            
            switch ($tipo) {
                case 0:
                    // $sql = "SELECT id, login FROM usuario ORDER BY id ASC";
                    $sql = "select usuario.id,pessoaFisica.nome, pessoaFisica.cpf, usuario.login, usuario.status, usuario.permissao from usuario inner join pessoaFisica on usuario.id = pessoaFisica.usuario_id order by usuario.id desc";
                    $this->filterOff = 1;
                    break;
                case 1:
                    $sql = "select usuario.id,pessoaFisica.nome, pessoaFisica.cpf, usuario.login, usuario.status, usuario.permissao from usuario inner join pessoaFisica on usuario.id = pessoaFisica.usuario_id  WHERE pessoaFisica.nome LIKE :termo  order by usuario.id asc";
                    break;
                case 2:
                    $sql = "select usuario.id,pessoaFisica.nome, pessoaFisica.cpf, usuario.login, usuario.status, usuario.permissao from usuario inner join pessoaFisica on usuario.id = pessoaFisica.usuario_id  WHERE pessoaFisica.email LIKE :termo  order by usuario.id asc";
                    break;
                case 3:
                    $sql = "select usuario.id,pessoaFisica.nome, pessoaFisica.cpf, usuario.login, usuario.status, usuario.permissao from usuario inner join pessoaFisica on usuario.id = pessoaFisica.usuario_id  WHERE pessoaFisica.cpf LIKE :termo  order by usuario.id asc";
                    //$sql = "SELECT cod, nome, usuario, status, permissao FROM usuario WHERE cpf LIKE :termo ORDER BY nome ASC";
                    break;
                case 4:
                    $sql = "select usuario.id,pessoaFisica.nome, pessoaFisica.cpf, usuario.login, usuario.status, usuario.permissao from usuario inner join pessoaFisica on usuario.id = pessoaFisica.usuario_id  WHERE usuario.login LIKE :termo  order by usuario.id asc";
                    //$sql = "SELECT id, login FROM usuario WHERE login LIKE :termo ORDER BY login ASC";
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
                $usuario = new Usuario();
                $pessoaFisica = new PessoaFisica();
                
                $usuario->setLogin($resultado["login"]);
                $usuario->setStatus($resultado["status"]);
                $usuario->setPermissao($resultado["permissao"]);
                $usuario->setId($resultado["id"]);
                $pessoaFisica->setNome($resultado["nome"]);
                $pessoaFisica->setCpf($resultado["cpf"]);
                $pessoaFisica->setUsuario($usuario);

                
               
                $lista[] = $pessoaFisica;

                
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
            $sql = "select u.*, p.id as idPf, p.nome, p.email, p.cpf, p.sexo, p.nascimento, p.cliente_id  from usuario u inner join pessoaFisica p on u.id = p.usuario_id WHERE u.id = :id order by u.id asc";
            $param = array(
                ":id" => $id
            );

            $resultado = $this->pdo->ExecuteQueryOneRow($sql, $param);

            if ($resultado != null) {
                $usuario = new Usuario();
                $pessoaFisica = new PessoaFisica();
                $pessoaFisica->setId($resultado["idPf"]);
                $pessoaFisica->setNome($resultado["nome"]);
                $pessoaFisica->setEmail($resultado["email"]);
                $pessoaFisica->setSexo($resultado["sexo"]); 
                $pessoaFisica->setNascimento($resultado["nascimento"]);
                $pessoaFisica->setCpf($resultado["cpf"]);
                
                $usuario->setLogin($resultado["login"]);
                $usuario->setStatus($resultado["status"]);
                $usuario->setPermissao($resultado["permissao"]);
                $usuario->setPassword($resultado["password"]);
                $usuario->setId($resultado["id"]);
                
                $pessoaFisica->setUsuario($usuario);
                


                //return $usuario;
                return $pessoaFisica;
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

    public function AutenticarUsuario(string $login, string $password, int $permissao) {
        try {

            //new login
            if ($permissao == 1) {
                //$sql = "SELECT id, login,status, permissao FROM usuario WHERE login = :login AND password = :password";
                $sql = "select usuario.id,pessoaFisica.nome, pessoaFisica.cpf, usuario.login, usuario.status, usuario.permissao from usuario inner join pessoaFisica on usuario.id = pessoaFisica.usuario_id WHERE usuario.login = :login AND usuario.password = :password ";
                //array associativo, parametro e nome obrigatoriamente igual
                $param = array(
                    ":login" => $login,
                    ":password" => $password
                );
            } else {
                $sql = "select usuario.id,pessoaFisica.nome, pessoaFisica.cpf, usuario.login, usuario.status, usuario.permissao from usuario inner join pessoaFisica on usuario.id = pessoaFisica.usuario_id WHERE usuario.login = :login AND usuario.password = :password ";

                $param = array(
                    ":login" => $login,
                    ":password" => $password
                );
            }

         
            
            $dt = $this->pdo->ExecuteQueryOneRow($sql, $param);

            if ($dt != null) {
                $usuario = new Usuario();
                $pessoaFisica = new PessoaFisica();
                
                $usuario->setId($dt["id"]);
                $usuario->setLogin($dt["login"]);
                $usuario->setStatus($dt["status"]);
                $usuario->setPermissao($dt["permissao"]);
                
                $pessoaFisica->setUsuario($usuario);
                $pessoaFisica->setNome($dt["nome"]);
                $pessoaFisica->setCpf($dt["cpf"]);
                
                return $pessoaFisica;
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

    public function AlterarSenha(string $password, int $id) {
        try {
            
            $sql = "UPDATE usuario SET password = :password WHERE id = :id";
            $param = array(
                ":password" => $password,
                ":id" => $id
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

//____________Validar dados existentes_________________________\\
    public function VerificaUsuarioExiste(string $user) {
        try {
            $sql = "SELECT usuario FROM usuario WHERE usuario = :usuario";

            $param = array(
                ":usuario" => $user
            );

            $dr = $this->pdo->ExecuteQueryOneRow($sql, $param);


            if (!empty($dr)) {
                return 1;
            } else {
                return -1;
            }
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function VerificaEmailExiste(string $email) {
        try {
            $sql = "SELECT email FROM usuario WHERE email = :email";

            $param = array(
                ":email" => $email
            );

            $dr = $this->pdo->ExecuteQueryOneRow($sql, $param);


            if (!empty($dr)) {
                return 1;
            } else {
                return -1;
            }
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function VerificaCPFExiste(string $cpf) {
        try {
            $sql = "SELECT cpf FROM usuario WHERE cpf = :cpf";

            $param = array(
                ":cpf" => $cpf
            );

            $dr = $this->pdo->ExecuteQueryOneRow($sql, $param);

            if (!empty($dr)) {
                return 1;
            } else {
                return -1;
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