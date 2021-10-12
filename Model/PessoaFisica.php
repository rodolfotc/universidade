<?php

class PessoaFisica {

    private $id;
    private $nome;
    private $sexo;
    private $nascimento;
    private $email;
    private $cpf;
    private $cliente;
    private $usuario;
    
    function getCliente() {
        return $this->cliente;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

        
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getCpf() {
        return $this->cpf;
    }

    function getNascimento() {
        return $this->nascimento;
    }

    function getSexo() {
        return $this->sexo;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    function setNascimento($nascimento) {
        $date = str_replace('/', '-', $nascimento);
        $this->nascimento = date('Y-m-d', strtotime($date));
    }

    function setSexo($sexo) {
        $this->sexo = $sexo;
    }

}

?>