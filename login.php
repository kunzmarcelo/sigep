<?php session_start(); ?>
<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<?php

if (isset($_POST['valida'])) {
    include_once "./modell/BancoDadosPDO.class.php";

    $con = new BancoDadosPDO();
    $login = $_POST['login'];
    $senha = md5($_POST['senha']);
    //$permissao = $_POST['permissao'];

    $resultado = $con->listarUm("usuario", "login = '$login'");
    $dados = $resultado->fetchObject();

    if (!empty($dados)) {
        //if (($dados->login == $login) && ($dados->senha == $senha) && ($dados->nivel_usu == $nivel_usu)) {
        if (($dados->login == $login) && ($dados->senha == $senha)) {
            if ($dados->status == true) {
                if ($dados->permissao == 1) {
                    $_SESSION['ativo'] = true;
                    $_SESSION['login'] = $login;
                    $_SESSION['permissao'] = $dados->permissao;
                    $data_login = date('Y-m-d H:i:s');
                    $con->alterar("usuario", "data_login='$data_login'", "login='$login'");
                    // echo "<script>alert('Admin')</script>";
                    header('location: index.php');
                }
                if ($dados->permissao == 2) {
                    $_SESSION['ativo'] = true;
                    $_SESSION['login'] = $login;
                    $_SESSION['permissao'] = $dados->permissao;
                    $data_login = date('Y-m-d H:i:s');
                    $con->alterar("usuario", "data_login='$data_login'", "login='$login'");
                    header('location: index.php');
                }
            }else{
                 echo "<script>alert('Erro ao realizar login. Entre e contato com o administrador');window.location.href='login.html';</script>";
            }

            //echo "<script>alert('Passou aqui')</script>";
            //header('location: principal.php');
        }
    }
    //login incorreto
    echo "<script>alert('Usuário ou senha incorretos');window.location.href='login.html';</script>";
    //header('location:principal.php');
}
?>