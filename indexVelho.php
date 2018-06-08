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
 include_once "./modell/BancoDadosPDO.class.php";

        $con = new BancoDadosPDO();
        $login = $_SESSION['login'];
        $resultado = $con->listarUm("usuario", "login = '$login'");
        $dados = $resultado->fetchObject();


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
       

//        if ($dados->permissao == 1) {
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
                            <img src="bibliotecas/img/if_interface-61_809291.png"> Operações
                        </a>
                        <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                            <li>
                                <a href="view/cadastra_operacao.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                            </li>
                            <li>
                                <a href="view/lista_operacao_todos.php" title="listar todos"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                            </li>
                            <li>
                                <a href="view/lista_operacao.php" title="listar todos produtos"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <img src="bibliotecas/img/if_.svg_2093655.png"> Metas
                        </a>
                        <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                            <!--                                <li>
                                                                <a href="view/cadastra_meta_diaria.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                                                            </li>
                            -->                                
                            <li>
                                <a href="view/cadastra_detalhe_celula_produto.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                            </li>
                            <li>
                                <a href="view/lista_detalhe_celula_produto.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                            </li>
                            <li>
                                <a href="view/lista_detalhe_celula_produto_celula.php" title="listagem de pessoas por célula e por data"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                            </li>
                            <li>
                                <a href="view/lista_detalhe_celula_produto_pecas_faltam.php" title="Listagem de produção por status"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar Status</a>
                            </li>
                            <li>
                                <a href="view/lista_detalhe_celula_produto_todos.php" title="Débito de produção"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar faltam</a>
                            </li>
                            <li>
                                <a href="view/lista_anotacoes.php" title="Anotações Cadastradas"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar Anotações</a>
                            </li>
                            <li>
                                <a href="view/lista_detalhe_celula_produto_total.php" title="Somatório de peças mensal"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Somatório</a>
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
                            <li>
                                <a href="view/lista_detalhe_produto_lote_data.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                            </li>
                            <li>
                                <a href="view/lista_detalhe_produto_lote_lote.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                            </li>
                            <li>
                                <a href="view/lista_detalhe_produto_lote_data_lote.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                            </li>                           
                            <li>
                                <a href="view/lista_detalhe_produto_lote_data_lote_agrupado.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png" title="Agrupado"> Listar</a>
                            </li>                           
                        </ul>
                    </li>

                    <!--                        <li>
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
                                                        <a href="view/lista_diario_bordo_agrupado.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                                    </li>
                                                    <li>
                                                        <a href="view/lista_diario_bordo_maquina.php" title="lista diario por maquina"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                                    </li>
                                                    <li>
                                                        <a href="view/lista_diario_bordo_funcionario_maquina.php" title="lista diario por maquina"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                                    </li>
                                                    <li>
                                                        <a href="view/lista_diario_bordo_salario.php" title="lista diario por maquina"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Calcular</a>
                                                    </li>
                                                </ul>
                                            </li>-->
                    <!--                        <li>
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
                                            </li>-->
                    <li>
                        <a href="#">
                            <img src="bibliotecas/img/1487111642_group.png"> Funcionário
                        </a>
                        <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                            <li>
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
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <img src="bibliotecas/img/1487111098_property-agent-010.png"> Despesas
                        </a>
                        <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                            <li>
                                <a href="view/cadastra_mao_obra.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                            </li>
                            <li>
                                <a href="view/lista_mao_obra.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar Despesas</a>
                            </li>
                            <li>
                                <a href="view/cadastra_salario.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                            </li>
                            <li>
                                <a href="view/lista_salario.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar Salários</a>
                            </li>
                        </ul>
                    </li>


                    <!--                        <li>
                                                <a href="#">
                                                    <img src="bibliotecas/img/if_money_bag_309025.png">Faturamento
                                                </a>
                                                <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                                                    <li>
                                                        <a href="view/cadastra_producao_fabrica.php"><img src="bibliotecas/img/1483730431_icon-81-document-add.png"> Adicionar</a>
                                                    </li>
                                                    <li>
                                                        <a href="view/lista_producao_fabrica.php"><img src="bibliotecas/img/1483730604_icon-53-notebook-list.png"> Listar</a>
                                                    </li>
                                                </ul>
                                            </li>-->
                    <li>
                        <a href="#">
                            <img src="bibliotecas/img/if_pie-chart_1608436.png"> Gráficos
                        </a>
                        <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                            <!--                                <li>
                                                                <a href="view/grafico_producao_fabrica.php" title="Faturamento da fábrica"><img src="bibliotecas/img/if_line-chart_1608449.png"> Faturamento</a>
                                                            </li>-->
                            <!--                                <li>
                                                                <a href="view/grafico_fabrica_faturamento.php" title="Faturamento da fábrica em tempo real"><img src="bibliotecas/img/if_line-chart_1608449.png"> Faturamento</a>
                                                            </li>-->
                            <li>
                                <a href="view/grafico_fabrica_producao_celula.php" title="Produção da fabrica em tempo real"><img src="bibliotecas/img/if_line-chart_1608449.png"> Produção</a>
                            </li>
                            <li>
                                <a href="view/grafico_diario_bordo_paradas.php" title="Paradas não programadas"><img src="bibliotecas/img/if_line-chart_1608449.png"> PNP</a>
                            </li>
                            <!--                                <li>
                                                                <a href="view/grafico_diario_bordo_individual.php" title="Grafico produtividade individual "><img src="bibliotecas/img/if_line-chart_1608449.png"> Produtividade</a>
                                                            </li>-->
                            <li>
                                <a href="view/grafico_org_produto.php" title="Funções por produtos"><img src="bibliotecas/img/if_line-chart_1608449.png"> Com. Produtos</a>
                            </li>
                            <li>
                                <a href="view/grafico_meta_diaria.php" title="Funções por produtos"><img src="bibliotecas/img/if_line-chart_1608449.png"> Produção Diária</a>
                            </li>
                            <li>
                                <a href="view/grafico_meta_diaria_celula.php" title="Funções por produtos"><img src="bibliotecas/img/if_line-chart_1608449.png"> Produção Célula</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <img src="bibliotecas/img/1487270025_calculator.png"> Cálculos
                        </a>
                        <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                            <li>
                                <a href="view/calculoProducao.php"><img src="bibliotecas/img/if_calculator_1214650.png"> Produção dia</a>
                            </li>
                            <li>
                                <a href="view/calculoNPecas.php"><img src="bibliotecas/img/if_calculator_1214650.png"> Tempo Produção</a>
                            </li>
                            <li>
                                <a href="view/calculoNPecasTeste.php"><img src="bibliotecas/img/if_calculator_1214650.png"> Estimativa Tempo</a>
                            </li>
                            <li>
                                <a href="view/calculoCustoPecaProduto.php"><img src="bibliotecas/img/if_calculator_1214650.png"> Custo unitário</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#">
                            <img src="bibliotecas/img/1487184653_user_male.png"> <?= $dados->nome ?> <?= $dados->sobrenome ?>
                        </a>
                        <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                            <!--                                <li>
                                                                <a href="view/cadastra_setor.php"><img src="bibliotecas/img/if_editor-folder-add-outline-stroke_763223.png"> Setores</a>
                                                            </li>-->
                            <li>
                                <a href="view/cadastra_celula.php"><img src="bibliotecas/img/if_editor-folder-add-outline-stroke_763223.png"> Células</a>
                            </li>
                            <li>
                                <a href="view/cadastra_falta.php"><img src="bibliotecas/img/if_editor-folder-add-outline-stroke_763223.png"> Faltas</a>
                            </li>
                            <li>
                                <a href="view/cadastra_config.php"><img src="bibliotecas/img/if_editor-folder-add-outline-stroke_763223.png"> Config metas</a>
                            </li>
                            <li>
                                <a href="view/cadastra_config_celula.php"><img src="bibliotecas/img/if_editor-folder-add-outline-stroke_763223.png"> Config celula</a>
                            </li>
                            <!--                                <li>
                                                                <a href="view/cadastra_detalhe_menu.php"><img src="bibliotecas/img/if_editor-folder-add-outline-stroke_763223.png"> Menu</a>
                                                            </li>-->
                            <li>
                                <a href="http://localhost/phpmyadmin/" target="_blank" ><img src="bibliotecas/img/if_database_add_103467.png"> phpMyAdmin</a>
                            </li>
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
                        <div class='col-lg-12'>
                            <h1 class='page-header'>SIGEP <small>(Sistema de Gerenciamento de Produção)</small></h1>
                            <h4 class='page-header'>Bem vindo  <?= $dados->nome . $dados->sobrenome ?> </h4>
                            <h5 class='page-header'>Usuário:  <?= $dados->obs ?> </h5>
                            <h5 class='page-header'>Versão Sistema SIGEP V.1.0 </h5>
                        </div>
                        <?php
                        include_once './modell/BancoDadosPDO.class.php';
                        $con = new BancoDadosPDO();
                        $data = date('Y-m-d');
                        $hora = date("H:i:s");
                        $matriz = $con->listarTodos("detalhe_celula_produto WHERE data = '$data' LIMIT 1");
                        //echo $data;

                        if ($hora >= "16:00:00") {
                            if (empty($matriz->fetchObject())) {
                                echo "<div class='alert alert-warning' role='alert'>A produção do dia de hoje não foi informada no sistema. Deseja fazer isso agora? "
                                . "<a class='alert-link' href='view/cadastra_detalhe_celula_produto.php'>Clique aqui</a>"
                                . "</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!--</div>-->

        </div><!-- /#wrapper -->
        <!-- jQuery -->



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="bootstrap/assets/js/sidebar_menu.js"></script>

    </body>
</html>
