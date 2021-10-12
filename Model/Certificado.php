<?php

    class Certificado{
        private $id;
        private $data;
        private $nome;
        private $quantidade;
        private $tipo;
        private $foto;
        private $arquivo;
        private $ativo;
        private $usuario;
        
        
        
        function getId() {
            return $this->id;
        }

        function getData() {
            return $this->data;
        }

        function getNome() {
            return $this->nome;
        }

        function getQuantidade() {
            return $this->quantidade;
        }

        function getTipo() {
            return $this->tipo;
        }

        function getFoto() {
            return $this->foto;
        }

        function getArquivo() {
            return $this->arquivo;
        }

        function getAtivo() {
            return $this->ativo;
        }

        function getUsuario() {
            return $this->usuario;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setData($data) {
            $this->data = $data;
        }

        function setNome($nome) {
            $this->nome = $nome;
        }

        function setQuantidade($quantidade) {
            $this->quantidade = $quantidade;
        }

        function setTipo($tipo) {
            $this->tipo = $tipo;
        }

        function setFoto($foto) {
            $this->foto = $foto;
        }

        function setArquivo($arquivo) {
            $this->arquivo = $arquivo;
        }

        function setAtivo($ativo) {
            $this->ativo = $ativo;
        }

        function setUsuario($usuario) {
            $this->usuario = $usuario;
        }


        
    }
