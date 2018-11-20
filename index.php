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
include_once "../sigep2.0/modell/BancoDadosPDO.class.php";

$con = new BancoDadosPDO();
$login = $_SESSION['login'];
$resultado = $con->listarUm("usuario", "login = '$login'");
$dados = $resultado->fetchObject();
$_SESSION['permissao'] = $dados->permissao;
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Kunz, Marcelo 2016">

        <title>Bem Vindos</title>

        <!-- Bootstrap Core CSS -->
        <link href="../sigep2.0/startbootstrap/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../sigep2.0/startbootstrap/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="../sigep2.0/startbootstrap/dist/css/sb-admin-2.css" rel="stylesheet">
        <link href="../sigep2.0/startbootstrap/vendor/morrisjs/morris.css" rel="stylesheet">
        <link href="../sigep2.0/startbootstrap/vendor/font-awesome/css/font-awesome.min.css"  rel="stylesheet">
        <link rel="icon" href="bibliotecas/imagens/icone_logo.ico" type="image/x-icon">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    </head>
    <body>
        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!--<span class="chat-img pull-left">-->

                    <!--</span>-->
                    <a class="navbar-brand" href="index.php" style="font-family: Franklin Gothic Heavy">
                        <!--<h1 style="font-family: Franklin Gothic Heavy;">-->
                        <span style="color: #034ea1;">SIGE</span><span style="color: #0088cf;">P</span>
                        <!--</h1>-->
                    </a>
                </div>
                <!-- /.navbar-header -->



                <ul class='nav navbar-top-links navbar-right'>                    
                    <li class="dropdown">
                        <!--                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                    <i class="fa fa-bell fa-fw"></i> Avisos <i class="fa fa-caret-down"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-alerts">
                                                    <li>
                                                        <a href="#">
                                                            <div>
                        <?php
                        require_once "../sigep2.0/modell/BancoDadosPDO.class.php";

                        $lote = new BancoDadosPDO();
                        $matriz = $lote->listarTodos("config_celula");
                        ?>
                        
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="divider"></li>                            
                                                </ul>-->
                        <!-- /.dropdown-alerts -->
                        <?php
                        require_once "../sigep2.0/modell/BancoDadosPDO.class.php";

                        $menu_paiBD = new BancoDadosPDO();
                        $matriz2 = $menu_paiBD->listarTodos("menu_pai WHERE ordem_pai = '1000' ORDER BY ordem_pai ASC");

                        foreach ($matriz2->fetchAll() as $res2) {
                            if ($res2['status_pai'] == TRUE) {
                                $id_pai = $res2['id_menu'];
                                $icone_pai = $res2["icone_pai"];
                                ?>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
                                    <i class='<?= $icone_pai ?>'  aria-hidden='true'></i> 
                                    <?= $dados->nome . ' ' . $dados->sobrenome ?> <span class='caret'></span>
                                </a>
                                <ul class='dropdown-menu'>
                                    <?php
                                    $matriz = $menu_paiBD->listarTodos("menu_filho WHERE menu_filho.id_menu='$id_pai' AND menu_filho.permissao = '$dados->permissao'");
                                    foreach ($matriz->fetchall() as $res) {
                                        if ($res['status'] == TRUE) {
                                            $icone = $res["icone"];
                                            ?>

                                            <li>
                                                <a href="view/<?= $res['link'] ?>">
                                                    <i class="<?= $icone ?>"  aria-hidden='true'></i>
                                                    <?= $res['nome'] ?>
                                                </a>
                                            </li>
                                            <!--<li role='separator' class='divider'></li>-->
                                            <?php
                                        }
                                    }
                                    ?>
                                    <li role='separator' class='divider'></li>
                                    <li>
                                        <a href='view/logout.php'><span class='glyphicon glyphicon-log-out'></span>
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>


                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class='nav' id='side-menu'>
                            <?php
                            require_once "../sigep2.0/modell/BancoDadosPDO.class.php";

                            $menu_paiBD = new BancoDadosPDO();
                            $matriz2 = $menu_paiBD->listarTodos("menu_pai WHERE ordem_pai != '1000' ORDER BY ordem_pai ASC");

                            foreach ($matriz2->fetchAll() as $res2) {
                                if ($res2['status_pai'] == TRUE) {
                                    $icone_pai = $res2["icone_pai"];
                                    $id_pai = $res2['id_menu'];
                                    ?>
                                    <li>
                                        <a href='#'>
                                            <i class='<?= $icone_pai ?>' aria-hidden='true'></i>&nbsp;
                                            <?= $res2['nome_pai'] ?>
                                            <span class='fa arrow'></span>
                                        </a>
                                        <ul class='nav nav-second-level'>
                                            <?php
                                            $matriz = $menu_paiBD->listarTodos("menu_filho WHERE menu_filho.id_menu='$id_pai'");
                                            foreach ($matriz->fetchall() as $res) {
                                                if ($res['status'] == TRUE) {
                                                    $icone = $res["icone"];
                                                    ?>
                                                    <li>
                                                        <a href="view/<?= $res['link'] ?>"><span class= '<?= $icone ?>'  > </span>
                                                            <?= $res['nome'] ?>
                                                        </a>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 style="font-family: Franklin Gothic Heavy;"><span style="color: #034ea1;">SIGE</span><span style="color: #0088cf;">P</span>

                            <small>(Sistema de Gerenciamento de Produção)</small>
                        </h1>

                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">                            
                            <div class='col-lg-12'>
                                <div class="row">
<!--                                    <h4 class='page-header'>Bem vindo  <?= $dados->nome . ' ' . $dados->sobrenome ?> </h4>
                                    <h5 class='page-header'>Usuário:  <?= $dados->obs ?> </h5>
                                    <h5 class='page-header'>Versão Sistema SIGEP V.2.0 </h5>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <?php
                        include_once './modell/BancoDadosPDO.class.php';
                        $con = new BancoDadosPDO();
                        $data = date('Y-m-d');
                        $hora = date("H:i:s");
                        $matriz = $con->listarTodos("detalhe_celula_produto WHERE data = '$data' LIMIT 1");
//echo $data;
//echo $hora;
                        $hora_definida = '14:00:00';
//echo $hora_definida;
//if ($hora >= "16:00:00") {
                        if (empty($matriz->fetchObject()) && $hora >= $hora_definida) {
                            $resposta = "A produção do dia de hoje não foi informada no sistema. Deseja fazer isso agora?<br> Clique Aqui";
                            ?>

                            <div class="panel panel-yellow">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-warning fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge">Aviso</div>
                                            <!--<div>Aviso!</div>-->
                                        </div>
                                    </div>
                                </div>

                                <a href="view/cadastra_detalhe_celula_produto.php">
                                    <div class="panel-footer">
                                        <span class="pull-left"><?= $resposta; ?></span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                            <?php
                        }
//}
                        ?>

                    </div>
                </div>

                <!--                <div class="row">
                                    <div class="col-lg-12">
                <?php
                $data_ini = date('Y-m-d');
                if (empty($data_ini)) {
                    echo" <div class='alert alert-warning' role='alert'>
                            <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Selecione os campos acima.</h4>
                        </div>";
                } else {                    
                    include_once './scripts_graficos_coletivo.php';
                }
                ?>
                                    </div>
                                </div>-->

                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        $data_ini = date('Y-m-d');
                        include_once './modell/BancoDadosPDO.class.php';
                        $con = new BancoDadosPDO();                       
                        $matriz3 = $con->listarTodos("celula_trabalho WHERE status_celula=true");

//                        while ($dados = $matriz3->fetchObject()) {
//                            if ($dados->status_celula == true) {
//                                echo $dados->funcionarios;
//                            }
//                        }

                        $id_celula = '4';
                        if (empty($data_ini)) {
                            echo" <div class='alert alert-warning' role='alert'>
                                                    <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Algo deu errado, tente novamente mais tarde.</h4>
                                                </div>";
                        } else {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="text-align: center">
                                    Gráfico de produção diária por célula
                                </div>
                                <div id="pecas_produzidas_celulas4" style="width: 100%; height: 100%"></div>
                            </div>
                            <?php
                            include_once './scripts_graficos_3.php';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        $data_ini = date('Y-m-d');
                      
                        $id_celula = '10';
                        if (empty($data_ini)) {
                            echo" <div class='alert alert-warning' role='alert'>
                                                    <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Algo deu errado, tente novamente mais tarde.</h4>
                                                </div>";
                        } else {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="text-align: center">
                                    Gráfico de produção diária por célula
                                </div>
                                <div id="pecas_produzidas_celulas2" style="width: 100%; height: 100%"></div>
                            </div>
                            <?php
                            include_once './scripts_graficos_2.php';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        $data_ini = date('Y-m-d');
                      
                        $id_celula = '13';
                        if (empty($data_ini)) {
                            echo" <div class='alert alert-warning' role='alert'>
                                                    <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Algo deu errado, tente novamente mais tarde.</h4>
                                                </div>";
                        } else {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="text-align: center">
                                    Gráfico de produção diária por célula
                                </div>
                                <div id="pecas_produzidas_celulas13" style="width: 100%; height: 100%"></div>
                            </div>
                            <?php
                            include_once './scripts_graficos_13.php';
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- /.panel-body -->



        <!-- jQuery -->
        <script src="../sigep2.0/startbootstrap/vendor/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../sigep2.0/startbootstrap/vendor/bootstrap/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../sigep2.0/startbootstrap/vendor/metisMenu/metisMenu.min.js"></script>

        <!-- Morris Charts JavaScript -->
        <script src="../sigep2.0/startbootstrap/vendor/raphael/raphael.min.js"></script>
        <!--<script src="../sigep2.0/startbootstrap/vendor/morrisjs/morris.min.js"></script>-->
        <!--<script src="../sigep2.0/startbootstrap/data/morris-data.js"></script>-->

        <!-- Custom Theme JavaScript -->
        <script src="../sigep2.0/startbootstrap/dist/js/sb-admin-2.js"></script>
    </body>
</html>