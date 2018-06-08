<?php
session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
    include_once "./view/funcoes.php"; {
    include_once './view/funcoes.php';
}
controlaAcessoUrl($url, $pagina);
//expiraSessao();
/*
 * 
 */
?>

<!DOCTYPE html>
<html lang="pt-BR">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Kunz, Marcelo 2014">

        <title>Bem Vindos - Página Inicial</title>

        <!-- Bootstrap Core CSS -->
        <link href="bootstrap/assets/css/bootstrap.css" rel="stylesheet">
        <link href="bootstrap/assets/css/picture.css" rel="stylesheet">
        <link href="bootstrap/assets/css/font-awesome.min.css" rel="stylesheet">
        <!--<link rel="stylesheet" type="text/css" href="bootstrap/assets/css/default.css">-->
        <link rel="stylesheet" type="text/css" href="bootstrap/assets/css/component.css">

        <script src="bootstrap/assets/js/modernizr.js"></script>
    </head>
    <body>
        <?php
        include_once "./modell/BancoDadosPDO.class.php";

        $con = new BancoDadosPDO();
        $login = $_SESSION['login'];
        $resultado = $con->listarUm("usuario", "login = '$login'");
        $dados = $resultado->fetchObject();
//$permissao = $_POST['permissao'];

        if ($dados->permissao == 2) {
            ?>
            <nav class="navbar navbar-default no-margin">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header fixed-brand">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"  id="menu-toggle">
                        <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>

                    </button>
                    <!--<a class="navbar-brand" href="#"><i class="fa fa-rocket fa-4"></i> SEEGATESITE</a>-->        
                </div><!-- navbar-header-->

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active" ><button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span></button></li>

                    </ul>
                </div><!-- bs-example-navbar-collapse-1 -->
            </nav>
            <!--<div class="container">--> 
            <div id="wrapper">
                <!-- Sidebar -->
                <div id="sidebar-wrapper">
                    <ul class="sidebar-nav nav-pills nav-stacked" id="menu">
                        <li>
                            <a href="#">
                                <img src="bibliotecas/img/1487111729_Cart_2.png"> Produtos
                            </a>
                            <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                                <li>
                                    <a href="view/cadastra_produto.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>                                            
                                </li>                          
                                <li>
                                    <a href="view/lista_produto.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                </li>                           
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <img src="bibliotecas/img/1488507446_new10.png"> Lote
                            </a>
                            <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                                <li>
                                    <a href="view/cadastra_lote.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>                                            
                                </li>                          
                                <li>
                                    <a href="view/cadastra_detalhe_produto_lote.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>                                            
                                </li>                          
                                <li>
                                    <a href="view/lista_detalhe_produto_lote.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                </li>                                                       
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <img src="bibliotecas/img/1483723851_function.png"> Funções
                            </a>
                            <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                                <li>
                                    <a href="view/cadastra_funcao_novo.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                                </li>
                                <li>
                                    <a href="view/lista_funcao_novo_todos.php" title="listar todos"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                </li>
                                <li>
                                    <a href="view/lista_funcao_novo.php" title="listar todos produtos"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                </li>                            
                            </ul>
                        </li>                    
                        <li>
                            <a href="#">
                                <img src="bibliotecas/img/1496194472_book.png"> Diário de Bordo
                            </a>
                            <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                                <li>
                                    <a href="view/cadastra_diario_bordo_producao.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                                </li>                            
                                <li>
                                    <a href="view/lista_diario_bordo.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                </li>                            
                                <li>
                                    <a href="view/lista_diario_bordo_maquina.php" title="lista diario por maquina"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                </li>                                                        
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <img src="bibliotecas/img/machine.png"> Máquina
                            </a>
                            <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                                <li>
                                    <a href="view/cadastra_maquina.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                                </li>
                                <li>
                                    <a href="view/lista_maquina.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <img src="bibliotecas/img/1487111642_group.png"> Funcionário
                            </a>
                            <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                                <!--                            <li>
                                                                <a href="view/cadastra_funcionario.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                                                            </li>
                                                            <li>
                                                                <a href="view/cadastro_numero_pecas.php" title="cadastrar salário de funcionários"><img src="bibliotecas/img/1483730431_icon-81-document-add.png" > Adicionar</a>
                                                            </li>
                                                            <li>
                                                                <a href="view/lista_funcionario.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                                            </li>
                                                            <li>
                                                                <a href="view/lista_numero_pecas.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                                            </li>-->
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <img src="bibliotecas/img/1487111098_property-agent-010.png"> Despesas
                            </a>
                            <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                                <!--                            <li>
                                                                <a href="view/cadastra_mao_obra.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                                                            </li>
                                                            <li>
                                                                <a href="view/lista_mao_obra.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                                            </li>
                                                            <li>
                                                                <a href="view/cadastra_salario.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                                                            </li>
                                                            <li>
                                                                <a href="view/lista_salario.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                                            </li>-->
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <img src="bibliotecas/img/1487270025_calculator.png"> Cálculos
                            </a>
                            <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                                <li>
                                    <a href="view/calculoProducao.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Produção dia</a>
                                </li>
                                <li>
                                    <a href="view/calculoNPecas.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Número de peças</a>
                                </li>
                                <li>
                                    <a href="view/calculoCustoPeca.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Custo unitário</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <img src="bibliotecas/img/1487184653_user_male.png"> <?= $_SESSION['login'] ?>
                            </a>
                            <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                                <li>
                                    <a href="view/logout.php"><img src="bibliotecas/img/1486669582_icons_exit2.png"> Sair</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="container">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="page-header">Sistema Diário de Bordo v.1.0</h1>
                                <h4 class="page-header">Bem vindo <?= $_SESSION['login'] ?></h4>



                                <h5 class="page-header"> <?= $dados->obs ?></h5>
                            </div>

                        </div>
                    </div>
                </div>
                <!--</div>-->

            </div><!-- /#wrapper -->
            <!-- jQuery -->
            <?php
        }
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="bootstrap/assets/js/sidebar_menu.js"></script>

    </body>
</html>
