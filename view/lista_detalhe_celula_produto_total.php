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

                <div class="row">
                    <div class="col-lg-12">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group">                                
                                        <div class="col-xs-6">
                                            <label for="data">Data inicial:</label>
                                            <input type="date" name="data" value="data" class="form-control" required="required" >
                                        </div>
                                    </div>                                   
                                    <div class="form-group">                                
                                        <div class="col-xs-6">
                                            <button name="enviar" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Enviar</button>
                                            <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">  
                                    <div class="form-group">
                                        <form action="gerarPDF.php" class="form-horizontal">
                                            <div class="form-group">
                                                <div class="col-xs-6">
                                                    <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                            ob_start(); // Ativa o buffer de saida do PHP

                            function CriaCodigo() { //Gera numero aleatorio
                                for ($i = 0; $i < 40; $i++) {
                                    $tempid = strtoupper(uniqid(rand(), true));
                                    $finalid = substr($tempid, -12);
                                    return $finalid;
                                }
                            }
                            ?>
                            <div class="panel panel-default">            
                                <div class="panel-heading" style="text-align: center;">
                                    Listagem
                                </div>
                                <!--</div>-->
                                <div class="table-responsive">                    
                                    <table class="table table-hover table-striped" id="tblEditavel">
                                        <thead>
                                            <tr>                                   
                                                <th>Mês</th>
                                                <th>Estimativa</th>
                                                <th>Produzidas</th>
                                                <th>Faltaram</th>
                                                <th>Conclusão</th>
                                            </tr>
                                        </thead>
                                        <tbody>                       

                                            <?php
                                            if (isset($_POST['enviar'])) {
                                                $data_ini = $_POST['data'];
//                                    $data_fim = $_POST['data1'];

                                                $data1 = explode("-", $data_ini);
                                                $data2 = explode("-", '2018-12-31');
                                                include_once "../modell/DetalheCelulaProduto.class.php";
                                                $lote = new DetalheCelulaProduto();

                                                $diferenca = 0;
                                                $nr = $data1[1];
                                                $ano = $data1[0];
                                                while ($nr <= $data2[1]) {
                                                    $matriz = $lote->somaMensal($nr, $ano);
                                                    while ($dados = $matriz->fetchObject()) {
                                                        if (!empty($dados->NPECAS)) {
                                                            if ($nr == '01')
                                                                $mes = 'Janeiro';
                                                            if ($nr == '02')
                                                                $mes = 'Fevereiro';
                                                            if ($nr == '03')
                                                                $mes = 'Março';
                                                            if ($nr == '04')
                                                                $mes = 'Abril';
                                                            if ($nr == '05')
                                                                $mes = 'Maio';
                                                            if ($nr == '06')
                                                                $mes = 'Junho';
                                                            if ($nr == '07')
                                                                $mes = 'Julho';
                                                            if ($nr == '08')
                                                                $mes = 'Agosto';
                                                            if ($nr == '09')
                                                                $mes = 'Setembro';
                                                            if ($nr == '10')
                                                                $mes = 'Outubro';
                                                            if ($nr == '11')
                                                                $mes = 'Novembro';
                                                            if ($nr == '12')
                                                                $mes = 'Dezembro';


                                                            $diferenca = ((( $dados->NFEITAS * 100)) / $dados->NPECAS); //porcentagem
                                                            $faltam = $dados->NPECAS - $dados->NFEITAS;
                                                            if (number_format($diferenca, 2, '.', '.') <= '79.99') {
                                                                $diferenca2 = "<span class='label label-danger'>" . number_format($diferenca, 2, '.', '.') . " %</span>";
                                                            } else {
                                                                $diferenca2 = "<span class='label label-info'>" . number_format($diferenca, 2, '.', '.') . " %</span>";
                                                            }
                                                            echo "<tr>                                                   
                                                                    <td title='data' >" . $mes . "</b></td>                                                   
                                                                    <td title='numero'>" . $dados->NPECAS . " peças</td>                                                   
                                                                    <td title='numero'>" . $dados->NFEITAS . " peças</td>
                                                                    <td title='numero'>" . $faltam . " peças</td>
                                                                    <td>" . $diferenca2 . "</td>                                                
                                                                    <td></td>                                                
                                                                  </tr>";
                                                        }
                                                    }
                                                    $nr++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id='chart_div' style="width: 400px; height: 120px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

            <script type="text/javascript">
                google.charts.load('current', {'packages': ['gauge']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {

                    var data = google.visualization.arrayToDataTable([
                        ['Label', 'Value'],
    <?php
    $data_ini = $_POST['data'];
//                                    $data_fim = $_POST['data1'];

    $data1 = explode("-", $data_ini);
    $data2 = explode("-", '2018-12-31');
    include_once "../modell/DetalheCelulaProduto.class.php";
    $lote = new DetalheCelulaProduto();

    $diferenca = 0;
    $nr = $data1[1];
    $ano = $data1[0];
    while ($nr <= $data2[1]) {
        $matriz = $lote->somaMensal($nr, $ano);
        while ($dados = $matriz->fetchObject()) {
            if (!empty($dados->NPECAS)) {
                if ($nr == '01')
                    $mes = 'Janeiro';
                if ($nr == '02')
                    $mes = 'Fevereiro';
                if ($nr == '03')
                    $mes = 'Março';
                if ($nr == '04')
                    $mes = 'Abril';
                if ($nr == '05')
                    $mes = 'Maio';
                if ($nr == '06')
                    $mes = 'Junho';
                if ($nr == '07')
                    $mes = 'Julho';
                if ($nr == '08')
                    $mes = 'Agosto';
                if ($nr == '09')
                    $mes = 'Setembro';
                if ($nr == '10')
                    $mes = 'Outubro';
                if ($nr == '11')
                    $mes = 'Novembro';
                if ($nr == '12')
                    $mes = 'Dezembro';


                $diferenca = ((( $dados->NFEITAS * 100)) / $dados->NPECAS); //porcentagem
                $faltam = $dados->NPECAS - $dados->NFEITAS;

                $diferenca2 = number_format($diferenca, 2, '.', '.');

                echo "['$mes',$diferenca2],";
            }
        }
        $nr++;
    }
    ?>




                    ]);

                    var options = {
                        width: 400, height: 420,
                        redFrom: 90, redTo: 100,
                        yellowFrom: 75, yellowTo: 90,
                        minorTicks: 5
                    };

                    var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

                    chart.draw(data, options);


                }
            </script>
            <?php
        }
        /* Captação de dados */
        $buffer = ob_get_contents(); // Obtém os dados do buffer interno
        $filename = "code.html"; // Nome do arquivo HTML
        file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
        ?>  
        <?php require_once "./actionRodape.php"; ?>

    </body>
</html>
