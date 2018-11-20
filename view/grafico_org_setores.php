<?php
session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
    include_once "../view/funcoes.php"; {
    include_once "../view/funcoes.php";
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
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <?php include_once "./actionCabecalho.php"; ?>
    </head>
    <body>
        <div id="wrapper">
            <?php require_once './actionfonteMenu.php'; ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <?php
                            $url = $_SERVER['REQUEST_URI'];
                            $part = explode("/", $url);
                            $part[3];

                            include_once '../modell/Produto.class.php';
                            $con = new BancoDadosPDO();
                            $titulo = $con->listarUm("menu_filho", "link like '$part[3]'");
                            $resultado = $titulo->fetchObject();
                            ?>

                            <?= $resultado->nome ?>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->




                <div class="panel panel-default"style="width: 100%; height: 100%;">
                    <div class="panel-heading" style="text-align: center">
                        Funcionários por setores 
                    </div>
                    <div id="chart_div" ></div>
                </div>
            </div>
        </div>


        <?php
        include_once "../modell/Funcionario.class.php";
        $lote = new Funcionario();
        $matriz = $lote->listaFuncionario();
        while ($dados = $matriz->fetchObject()) {
            $descricao = $dados->nome;
        }
        include_once "../modell/Setores.class.php";
        $prod2 = new Setores();
        $matriz2 = $prod2->listaSetor();
        while ($dados2 = $matriz2->fetchObject()) {
          
        }
        ?>


        <script type="text/javascript">
            google.charts.load('current', {packages: ["orgchart"]});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('string', 'Manager');
                data.addColumn('string', 'ToolTip');
                // For each orgchart box, provide the name, manager, and tooltip to show.

                data.addRows([
                    <?php

                        include_once "../modell/Funcionario.class.php";
                        $lote = new Funcionario();
                        $matriz = $lote->listaFuncionario();

                        while ($dados = $matriz->fetchObject()) {
                            if ($dados->ativo == true) {
                                $funcionarios = explode(' ',$dados->nome);
                                echo ("['$funcionarios[0]','$dados->descricao_setor','Funcionários'],");
                            }                           
                        }
                    ?>
                ]);
                // Create the chart.
                var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
                google.charts.load('current', {packages: ['orgchart']});

                // Draw the chart, setting the allowHtml option to true for the tooltips.
                chart.draw(data, {allowHtml: true});
            }
        </script>



        <?php require_once "./actionRodape.php"; ?>

    </body>
</html>


