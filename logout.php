<?php
session_start();
//session_destroy();

    unset( $_SESSION['entrar'] );
    unset( $_SESSION['cod'] );
    unset( $_SESSION['nome'] );
    unset( $_SESSION['permissao'] );
    unset( $_SESSION['logado'] );



header("Location: management.php?msg=2");
?>