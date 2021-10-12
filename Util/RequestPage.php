<?php

$pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_STRING);

$arrayPaginas = array(
    "home" => "View/home.php", //Página inicial
    "dashboard" => "View/home.php", //Página inicial  
    "usuario" => "View/UsuarioView/UsuarioView.php",
    "usuarioCadastro" => "View/UsuarioView/UsuarioCadastroView.php",   
    "alterarsenha" => "View/UsuarioView/AlterarSenhaView.php",
    "excluirusuario" => "View/UsuarioView/ExcluirView.php",
    "visualizarusuario" => "View/UsuarioView/VisualizarView.php",
    "certificado" => "View/CertificadoView/CertificadoView.php",
    "excluircertificado" => "View/CertificadoView/ExcluirCertificadoView.php",    
);

if ($pagina) {
    $encontrou = false;

    foreach ($arrayPaginas as $page => $key) {
        if ($pagina == $page) {
            $encontrou = true;
            require_once($key);
        }
    }

    if (!$encontrou) {
        require_once("View/site.php");
    }
} else {
    require_once("View/site.php");
}
?>