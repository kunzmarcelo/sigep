
<!--das
<!DOCTYPE html>
<html lang="pt-BR">
   <head>
       <meta charset="utf-8">
       <meta http-equiv="X-UA-Compatible" content="IE=edge">
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <meta name="description" content="">
       <meta name="author" content="Kunz, Marcelo 2016">

       <title>Bem Vindos</title>

<!-- Bootstrap Core CSS 
<link href="../bootstrap/assets/css/bootstrap.css" rel="stylesheet">
<link href="../bootstrap/assets/css/picture.css" rel="stylesheet">


<link rel="stylesheet" type="text/css" href="../bootstrap/assets/css/default.css">
<link rel="stylesheet" type="text/css" href="../bootstrap/assets/css/component.css">

<script src="../bootstrap/assets/js/modernizr.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="../bootstrap/assets/js/jquery.maskMoney.js"></script>


<script type="text/javascript">
    function verificaSenha() {
        var campo1 = document.getElementById('senha').value;
        var campo2 = document.getElementById('conf_senha').value;
        if (campo1 === campo2) {
            document.getElementById('result').innerHTML = "&raquo As senhas são iguais."
            document.getElementById('result').style.color = "#008B45";
        } else {
            document.getElementById('result').innerHTML = "&raquo As senhas não conferem."
            document.getElementById('result').style.color = "#FF6347";
        }
    }

    function verificaCheckBox() {
        var check = document.getElementById('termos');

        if (check != true) {
            document.getElementById('result').innerHTML = "&raquo Você deve aceitar os temos de uso."
            document.getElementById('result').style.color = "#FF6347";
        }
    }

    function verificaEmail() {
        var email = document.getElementById('login');
        if (email === '') {
            document.getElementById('result').innerHTML = "&raquo O campo de e-mail é obrigatório."
            document.getElementById('result').style.color = "#FF6347";
        }
    }
</script>
</head>
<body>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Usuários</h1>
    </div>
<!--/.col-lg-12--> 
</div>

<!--/.col-lg-4-
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading centraliza">
            Cadastro de Usuários
        </div>
        <div class="panel-body">

            <form method="post" id="form_usuario" class="form-horizontal">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="nome">Nome:</label>                    
                        <input type="text" name="nome" id="nome" class="form-control" required="required" placeholder="E-mail" />                    
                    </div>
                    <div class="form-group">
                        <label for="sobrenome">Sobrenome:</label>                    
                        <input type="text" name="sobrenome" id="sobrenome" class="form-control" required="required" placeholder="E-mail" />                    
                    </div>

                    <input type="hidden" id="cod" name="cod">
                    <div class="form-group">
                        <label for="login">Login:</label>                    
                        <input type="text" name="login" id="login" class="form-control" required="required" placeholder="E-mail" />                    
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" name="senha" id="senha" class="form-control" onkeyup="verificaSenha();" required="required" placeholder="Senha" />                    
                    </div>
                    <div class="form-group" >
                        <label for="conf_senha">Confirma senha:</label>                    
                        <input type="password" name="conf_senha" id="conf_senha" class="form-control" onkeyup="verificaSenha();" required="required" placeholder="Confirma sua senha" />
                    </div>
                    <div class="form-group">
                        <label for="permissao">Selecione o nivel do Usuario:</label>
                        <select name="permissao" id="permissao" class="form-control">
                            <option value="Selecione">Selecione...</option>
                            <option value="1">Administrador</option>
                            <option value="2">Usuário Comum</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name="termos" id="termos" value="termos">
                        <label for="termos">Aceito os termos e condições de uso:</label>
                    </div>

                    <p id="result"></p>
                    <p id="result"></p>

                    <div class="form-group">
                        <button type="submit" name="cadastrar" value="Cadastrar" class="btn btn-info" onclick="verificaCheckBox();">Cadastrar</button>
                        <button type="reset" name="cadastrar" value="Cancelar" class="btn btn-default" >Cancelar</button>        
                    </div>
                </div>
            </form>
-->
<?php
include_once "../modell/Usuario.class.php";

//instancia a classe de controle
$usu = new Usuario();
$login = \filter_input(INPUT_POST, 'login');
$senha = \filter_input(INPUT_POST, 'senha');
$data_login = date('Y-m-d H:i:s');
$status = true;
$permissao = \filter_input(INPUT_POST, 'permissao');
$termos = \filter_input(INPUT_POST, 'termos');
//$obs = \filter_input(INPUT_POST,'obs');
$nome = \filter_input(INPUT_POST, 'nome');
$sobrenome = \filter_input(INPUT_POST, 'sobrenome');


//$nivel_usu = 3;
//if (isset($_POST['listar'])){
if (isset($_POST["cadastrar"])) {
    //var_dump($login, $senha, $data_login, $status, $permissao);

    if (($permissao == 'Selecione')) {
        echo "<div class='alert alert-danger alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                Algum dos campos acima não foram selecionados corretamente.
                            </div>";
    } else {

        if ($permissao == 1) {
            $obs = 'Administrador';
        } else {
            $obs = 'Normal';
        }

        $status1 = $usu->cadastraUsuario($login, $senha, $data_login, $status, $permissao, $obs, $nome, $sobrenome);
        if ($status1 == true) {
            echo "<script>alert('Registro inserido com sucesso');window.location.href='login.html';</script>";
            echo "<div class='alert alert-info alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                Registro inserido com sucesso.
                            </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                Erro ao inserir o resgistro.
                            </div>";
        }
    }
}
?>
<!--
                </div>
            </div>
        </div>
    </body>
</html>-->