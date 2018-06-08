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

        <script src="../bootstrap/assets/js/modernizr.js"></script>
        <script>
//            window.location.reload(10000);
        </script>
    </head>    
    <body>
        <div class="container">
            <?php require_once './actionfonteMenu.php'; ?>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Faturamento e Produção - Fábrica</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="panel-heading">
                    <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                </div>
            </div>

            <div id="curve_chart" style="width: 900px; height: 500px"></div>

            <div id="pecas_produzidas" style="width: 900px; height: 500px"></div>

        </div>
  

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Mês', 'Faturamento', 'Meta de faturamento R$', 'Média diária R$'],

<?php
include_once "../modell/ProducaoFabrica.class.php";
$diario = new ProducaoFabrica();

$matriz = $diario->listaAgrupado();
$count = $matriz->rowCount();

while ($dados = $matriz->fetchObject()) {
    $data1 = explode("-", $dados->data_ini);
    $data2 = explode("-", $dados->data_fim);
    $ano = $data1[0];
    $data_fim = $data2[2] . '/' . $data2[1];

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
    $SOMAPECAS = $dados->SOMAPECAS;
    $meta = $dados->meta_faturamento;
    $SOMAVALOR = $dados->SOMAVALOR;
    $media = $SOMAVALOR / 20;
    $teste = "['$mes / $ano',$SOMAVALOR,$meta,Math.round($media)],";
    echo $teste;
}
?>
                ]);
                var options = {
                    title: 'Faturamento',
                    curveType: 'function',
                    legend: {position: 'bottom'}
                };

                var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                chart.draw(data, options);
            }
    </script>

    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Mês', 'Peças Produzidas', 'Meta de produção', 'Média diária'],
<?php
include_once "../modell/ProducaoFabrica.class.php";
$diario = new ProducaoFabrica();

$matriz = $diario->listaAgrupado();
while ($dados = $matriz->fetchObject()) {
    $data1 = explode("-", $dados->data_ini);
    $data2 = explode("-", $dados->data_fim);
    $ano = $data1[0];
    $data_fim = $data2[2] . '/' . $data2[1];

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



    $SOMAPECAS = $dados->SOMAPECAS;
    $SOMAVALOR = $dados->SOMAVALOR;
    $meta = $dados->meta_peca;
    $media = $SOMAPECAS / 20;
    $teste = "['$mes / $ano',$SOMAPECAS,$meta,$media],";
    echo $teste;
}
?>
            ]);

            var options = {
                title: 'Peças Produzidas',
                curveType: 'function',
                legend: {position: 'bottom'}
            };

            var chart = new google.visualization.LineChart(document.getElementById('pecas_produzidas'));

            chart.draw(data, options);
        }
    </script>
</body>
</html>