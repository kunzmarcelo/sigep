<?php
session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
    include_once "../view/funcoes.php";
{
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
                <form class="form-horizontal" method="post">                    
                    <div class="form-group"> 
                        <div class="col-xs-6">
                            <label for="id_celula" class="tamanho-fonte">Pessoas por célula de trabalho:</label><small> (Campo Obrigatório)</small>
                            <select name="id_celula" class="form-control" required="required" onchange="this.form.submit()">                                       
                                <?php
                                echo "<option value=''>Selecione ...</option>";
                                include_once '../modell/CelulaTrabalho.class.php';
                                $lote = new CelulaTrabalho();
                                $matriz = $lote->listaCelula();

                                while ($dados = $matriz->fetchObject()) {
                                    if ($dados->status_celula == TRUE) {
                                        $cod = $dados->id_celula;
                                        $n_lote = $dados->pessoas_celula;
                                        $obs = $dados->funcionarios;
                                        echo "<option value='" . $cod . "'>" . $n_lote . ' - ' . $obs . "</option>";
                                    }
                                    //}
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </form>


                <?php
                //if (isset($_POST['enviar'])) {
                //if (isset($_POST['enviar'])) {
//                $data_ini = \filter_input(INPUT_POST, 'data');
                $id_celula = \filter_input(INPUT_POST, 'id_celula');
                if (empty($id_celula)) {
                    echo" <div class='alert alert-warning' role='alert'>
                            <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Selecione os campos acima.</h4>
                         </div>";
                } else {
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" style="text-align: center">
                            Gráfico de faturamento mensal por célula
                        </div>
                        <div id="pecas_produzidas" style="width: 100%; height: 600px"></div>
                    </div>
                </div>
            </div>

            <script type="text/javascript" src="../graficos/loader.js"></script>

            <script type="text/javascript">
                                google.charts.load('current', {'packages': ['corechart']});
                                google.charts.setOnLoadCallback(drawChart);

                                function drawChart() {
                                    var data = google.visualization.arrayToDataTable([
                                        ['Mês', 'Faturamento Estimado', 'Faturamento Realizado', 'Não Faturado'],
    <?php
    include_once "../modell/DetalheCelulaProduto.class.php";
    $lote = new DetalheCelulaProduto();

    $mes_inicio = '01';
    $mes_final = '12';
    $ano = date('Y');


    $TOTALNPECAS = 0;
    $TOTALNFEITAS = 0;
    $diferenca = 0;
    while ($mes_inicio <= $mes_final) {
        $matriz = $lote->graficoMensalProdutoCelula($mes_inicio, $ano, $id_celula);
        while ($dados = $matriz->fetchObject()) {

            $TOTALNPECAS = $dados->TOTALNPECAS;
            $TOTALNFEITAS = $dados->TOTALNFEITAS;
            $diferenca = $TOTALNPECAS - $TOTALNFEITAS;
            
            $data2 = explode("-", $dados->data);
            $DATA = $data2[1];
            if ($data2[1] == '01')
                $mes = 'Janeiro';
            if ($data2[1] == '02')
                $mes = 'Fevereiro';
            if ($data2[1] == '03')
                $mes = 'Março';
            if ($data2[1] == '04')
                $mes = 'Abril';
            if ($data2[1] == '05')
                $mes = 'Maio';
            if ($data2[1] == '06')
                $mes = 'Junho';
            if ($data2[1] == '07')
                $mes = 'Julho';
            if ($data2[1] == '08')
                $mes = 'Agosto';
            if ($data2[1] == '09')
                $mes = 'Setembro';
            if ($data2[1] == '10')
                $mes = 'Outubro';
            if ($data2[1] == '11')
                $mes = 'Novembro';
            if ($data2[1] == '12')
                $mes = 'Dezembro';

            $resultado = "['$mes',$TOTALNPECAS,$TOTALNFEITAS,$diferenca],";
            echo $resultado;
        }
        $mes_inicio++;
    }
   
    include_once '../modell/CelulaTrabalho.class.php';
    $celula = new CelulaTrabalho();
    
    $resultado = $celula->listarUm("celula_trabalho", "id_celula = '$id_celula'");
    $dados = $resultado->fetchObject();
    
    //echo $dados->funcionarios;
                
                ?>
                
                
                
                                    ]);
                                    var options = {
                                        title: "Acompanhamento mensal de faturamento da célula <?=$dados->funcionarios?>",
                                        curveType: 'function',
                                        legend: {position: 'bottom'}
                                    };
                                    var chart = new google.visualization.LineChart(document.getElementById('pecas_produzidas'));
                                    chart.draw(data, options);
                                }
    <?php
// }
}
?>
        </script>
<?php require_once "./actionRodape.php"; ?>

    </body>
</html>



