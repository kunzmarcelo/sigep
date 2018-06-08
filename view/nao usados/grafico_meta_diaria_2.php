
<?php
session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
    include_once "../view/funcoes.php";
{
    include_once '../view/funcoes.php';
}
controlaAcessoUrl($url, $pagina);
//expiraSessao();
/*
 * 
 */
?>

<html>
    <head>      
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Kunz, Marcelo 2014">
        <!--<meta http-equiv="refresh" content="10">-->
        <title>Bem Vindos</title>

        <!-- Bootstrap Core CSS -->
        <link href="../bootstrap/assets/css/bootstrap.css" rel="stylesheet">
        <link href="../bootstrap/assets/css/picture.css" rel="stylesheet">


        <link rel="stylesheet" type="text/css" href="../bootstrap/assets/css/default.css">
        <link rel="stylesheet" type="text/css" href="../bootstrap/assets/css/component.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="../bootstrap/assets/js/modernizr.js"></script>
        <script>
            $(document).ready(function () {
                setInterval(function () {
                    cache_clear()
                }, 60000); // tempo em milessegundos
            });

            function cache_clear() {
                window.location.reload(true);
            }
        </script>
    </head>    
    <body>
        <div class="container">
            <?php require_once './actionfonteMenu.php'; ?>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Gráfico de produção diária.</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="panel-heading">
                    <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                    <a href="lista_detalhe_celula_produto.php" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-th-list"></span> Listar</a>
                    <a href="grafico_meta_diaria.php" class="btn btn-primary"><span class='glyphicon glyphicon-globe'></span> Ver Gráfico 1</a>


                </div>
            </div>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-xs-6 col-sm-4">Filtro.</label>
                </div>
                <div class="form-group">                                
                    <div class="col-xs-6 col-sm-4">
                        <label for="data">Data inicial:</label>
                        <input type="date" name="data" value="data" class="form-control" required="required" onchange="this.form.submit()">
                    </div>
                </div>
<!--                <div class="form-group">                                
                    <div class="col-xs-6 col-sm-4">
                        <button name="enviar" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Enviar</button>
                        <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>
                    </div>
                </div>-->
            </form>


            <?php
            $data_ini = \filter_input(INPUT_POST, 'data');

            if (empty($data_ini)) {
                echo" <div class='alert alert-warning' role='alert'>
                        <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Selecione uma data.</h4>
                    </div>";
            } else {
                    $data1 = explode("-", $data_ini);
                    $data_fim = $data1[0] . '-' . $data1[1];
                    ?>

                    <div id="chart_div" style="width: 1280px; height: 600px"></div>
                </div>


                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

                <script type="text/javascript">
                    google.charts.load('current', {packages: ['corechart', 'line']});
                    google.charts.setOnLoadCallback(drawCurveTypes);

                    function drawCurveTypes() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('number', 'X');
                        data.addColumn('number', 'Peças Produzidas');
                        data.addColumn('number', 'Meta Determinada');

                        data.addRows([
        <?php
        include_once "../modell/DetalheCelulaProduto.class.php";
        $lote = new DetalheCelulaProduto();

        $data_ini = $data_ini;
        $data_fim = $data_fim . '-31';
        $count = 0;
        while ($data_ini <= $data_fim) {
            $matriz = $lote->graficoPecasDia($data_ini);
            while ($dados = $matriz->fetchObject()) {
                if ($dados->data == '') {
                    
                } else {
                    $data2 = explode("-", $dados->data);

                    $DATA = $data2[2];
                    $NPECAS = $dados->NPECAS;
                    $NFEITAS = $dados->NFEITAS;
                    //$media = $SOMAVALOR / 20;
                    $resultado = "[$DATA,$NFEITAS,$NPECAS],";
                    echo $resultado;
                }
            }
            $data_ini++;
            $count ++;
        }
        ?>
                        ]);

                        var options = {
                            hAxis: {
                                title: 'Dias'
                            },
                            vAxis: {
                                title: 'Produção'
                            },
                            series: {
                                1: {curveType: 'function'}
                            }
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                        chart.draw(data, options);
                    }
        <?php
    }
?>
        </script>
    </body>
</html>