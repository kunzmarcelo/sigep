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
                            <select name="id_celula" class="form-control" required="required" >                                       
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
                                        echo "<option value=" . $cod . ">" . $n_lote . ' - ' . $obs . "</option>";
                                    }
                                    //}
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">                                
                        <div class="col-xs-6">
                            <label for="data">Data inicial:</label>
                            <input type="date" name="data" value="data" class="form-control" required="required" onchange="this.form.submit()" >
                        </div>
                    </div>
                    <!--                <div class="form-group">                                
                                        <div class="col-xs-6">
                                            <button name="enviar" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Enviar</button>
                                            <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>
                                        </div>
                                    </div>-->
                </form>


                <?php
                //if (isset($_POST['enviar'])) {
                //if (isset($_POST['enviar'])) {

                $data_ini = \filter_input(INPUT_POST, 'data');
                $celula = \filter_input(INPUT_POST, 'id_celula');
                if (empty($data_ini)) {
                    echo" <div class='alert alert-warning' role='alert'>
                        <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Selecione os campos acima.</h4>
                    </div>";
                } else {
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" style="text-align: center">
                            Gráfico de produção diária por célula
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
                                            ['Mês', 'Peças Produzidas', 'Produção Determinada'],
    <?php
    include_once "../modell/DetalheCelulaProduto.class.php";
    $lote = new DetalheCelulaProduto();

    $data_teste = $data_ini;
    $data_teste2 = explode("-", $data_teste);
    $data_ini = $data_teste;

    $data1 = explode("-", $data_ini);
    $data_fim = $data1[0] . '-' . $data1[1];

//echo $data_teste2[0].'-'.$data_teste2[1].'-'.'01';
//$data_teste = date("Y-m-d");
//echo $data_teste;
    $data_ini = $data_teste2[0] . '-' . $data_teste2[1] . '-' . '01';
    $data_fim2 = $data_fim . '-31';
    $mes = $data1[1] . '/' . $data1[0];

    if ($data1[1] == '01')
        $mes = 'Janeiro';
    if ($data1[1] == '02')
        $mes = 'Fevereiro';
    if ($data1[1] == '03')
        $mes = 'Março';
    if ($data1[1] == '04')
        $mes = 'Abril';
    if ($data1[1] == '05')
        $mes = 'Maio';
    if ($data1[1] == '06')
        $mes = 'Junho';
    if ($data1[1] == '07')
        $mes = 'Julho';
    if ($data1[1] == '08')
        $mes = 'Agosto';
    if ($data1[1] == '09')
        $mes = 'Setembro';
    if ($data1[1] == '10')
        $mes = 'Outubro';
    if ($data1[1] == '11')
        $mes = 'Novembro';
    if ($data1[1] == '12')
        $mes = 'Dezembro';

    while ($data_ini <= $data_fim2) {
        $matriz = $lote->graficoPecasDiaCelula($data_ini, $celula);
        while ($dados = $matriz->fetchObject()) {
            if ($dados->data == '') {
                
            } else {
                $nome = $dados->NOMES;
                $data2 = explode("-", $dados->data);

                $DATA = $data2[2];
                //$DATA = $data2[2] . '/' . $data2[1];
                $NPECAS = $dados->NPECAS;
                $NFEITAS = $dados->NFEITAS;
                //$media = $SOMAVALOR / 20;
                $resultado = "['$DATA',$NFEITAS,$NPECAS],";
                echo $resultado;
            }
        }
        $data_ini++;
    }
    ?>
                                        ]);
                                        var options = {
                                            title: "Acompanhamento diário de <?= $nome ?> em <?= $mes . ' de ' . $data1[0] ?>",
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



