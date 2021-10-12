<?php

class Usuario {

    private $id;
    private $login;
    private $status;
    private $permissao;
    private $ip;
    private $password;
    
    function getId() {
        return $this->id;
    }

    function getLogin() {
        return $this->login;
    }

    function getStatus() {
        return $this->status;
    }

    function getPermissao() {
        return $this->permissao;
    }

    function getIp() {
        return $this->ip;
    }

    function getPassword() {
        return $this->password;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setPermissao($permissao) {
        $this->permissao = $permissao;
    }

    function setIp($ip) {
        $this->ip = $ip;
    }

    function setPassword($password) {
        $this->password = md5($password);
    }


}

?>