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
        <link href="../sigep/startbootstrap/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../sigep/startbootstrap/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="../sigep/startbootstrap/dist/css/sb-admin-2.css" rel="stylesheet">
        <link href="../sigep/startbootstrap/vendor/morrisjs/morris.css" rel="stylesheet">
        <link href="../sigep/startbootstrap/vendor/font-awesome/css/font-awesome.min.css"  rel="stylesheet">

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
                    <a class="navbar-brand" href="indexComum2.php">SIGEP</a>
                </div>
                <!-- /.navbar-header -->



                <?php
                require_once "../sigep/modell/BancoDadosPDO.class.php";

                $menu_paiBD = new BancoDadosPDO();
                $matriz2 = $menu_paiBD->listarTodos("menu_pai WHERE ordem_pai = '1000' ORDER BY ordem_pai ASC");

                echo "<ul class='nav navbar-top-links navbar-right'>";
                foreach ($matriz2->fetchAll() as $res2) {
                    if ($res2['status_pai'] == TRUE) {
                        $id_pai = $res2['id_menu'];

                        echo "<li class='dropdown'>";
                        echo "";
                        echo"<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'><i class='fa fa-user fa-fw'> </i> " . $dados->nome . ' ' . $dados->sobrenome . "<span class='caret'></span></a>";
                        echo "<ul class='dropdown-menu'>";
                        $matriz = $menu_paiBD->listarTodos("menu_filho WHERE menu_filho.id_menu='$id_pai' AND menu_filho.permissao = '$dados->permissao'");
                        foreach ($matriz->fetchall() as $res) {
                            if ($res['status'] == TRUE) {
                                $icone = $res["icone"];
                                echo"<li>";
                                //echo"<a href="  . $res['link'] . "><span class= '$icone'  > </span> " . $res['nome'] . "</a>";

                                echo"<a href=" . "view/" . $res['link'] . "><span class= '$icone'  > </span> " . $res['nome'] . "</a>";
                                echo"</li>";
                            }
                        }
                        echo "<li role='separator' class='divider'></li>";
                        echo "<li>
                                        <a href='view/logout.php'><span class='glyphicon glyphicon-log-out'></span> Logout</a>
                                    </li>";
                        echo "</ul>";
                        echo '</li>';
                    }
                }
                echo"</ul>";
                ?>



                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <?php
                        require_once "../sigep/modell/BancoDadosPDO.class.php";

                        $menu_paiBD = new BancoDadosPDO();
                        $matriz2 = $menu_paiBD->listarTodos("menu_pai WHERE ordem_pai != '1000' ORDER BY ordem_pai ASC");

                        echo "<ul class='nav' id='side-menu'>";
                        foreach ($matriz2->fetchAll() as $res2) {
                            if ($res2['status_pai'] == TRUE) {
                                $id_pai = $res2['id_menu'];

                                echo "<li>";
                                echo"<a href='#'><i class=''></i>" . $res2['nome_pai'] . "<span class='fa arrow'></span></a>";
                                echo "<ul class='nav nav-second-level'>";
                                $matriz = $menu_paiBD->listarTodos("menu_filho WHERE menu_filho.id_menu='$id_pai'");
                                foreach ($matriz->fetchall() as $res) {
                                    if ($res['status'] == TRUE) {
                                        $icone = $res["icone"];
                                        echo"<li>";
                                        echo"<a href=" . "view/" . $res['link'] . "><span class= '$icone'  > </span> " . $res['nome'] . "</a>";
                                        echo"</li>";
                                    }
                                }
                                echo "</ul>";
                                echo '</li>';
                            }
                        }
                        echo"</ul>";
                        ?>

                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Dashboard</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-bar-chart-o fa-fw"></i> Area Chart Example
                                
                            </div>
                            
                            
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            fdlsfjkodsjfko<br>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <!-- /.col-lg-8 -->
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->


        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="../sigep/startbootstrap/vendor/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../sigep/startbootstrap/vendor/bootstrap/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../sigep/startbootstrap/vendor/metisMenu/metisMenu.min.js"></script>

        <!-- Morris Charts JavaScript -->
        <script src="../sigep/startbootstrap/vendor/raphael/raphael.min.js"></script>
        <script src="../sigep/startbootstrap/vendor/morrisjs/morris.min.js"></script>
        <script src="../sigep/startbootstrap/data/morris-data.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="../sigep/startbootstrap/dist/js/sb-admin-2.js"></script>
    </body>
</html>