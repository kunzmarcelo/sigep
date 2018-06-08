<?php
include_once '../modell/Config.class.php';
$config = new Config();

$resultado = $config->listaConfig();
while ($dados = $resultado->fetchObject()) {
    $data_ini = $dados->data_ini;
    $mes_ini1 = explode("-", $data_ini);
    $ano_ini = $mes_ini1[0];
    $data_atual = date('Y');
    
    if ($dados->status == TRUE && $ano_ini == $data_atual) {
        //$meta_fatu = $dados->meta_faturamento;
        //$meta_prod = $dados->meta_producao;
        $data_ini = $dados->data_ini;
        $data_fim = $dados->data_fim;

        $mes_ini1 = explode("-", $data_ini);
        $mes_fim1 = explode("-", $data_fim);
        $mes_ini = $mes_ini1[1];
        $mes_fim = $mes_fim1[1];
        $ano_ini = $mes_ini1[0];
        $ano_fim = $mes_fim1[0];
    }
}
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
                // window.location.reload(); use this if you do not remove cache
            }
        </script>
    </head>    
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Gráfico de metas de produção mensal aproximado</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="panel-heading">
                    <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                    <!--<a href="grafico_fabrica_faturamento.php" class="btn btn-info"><span class="glyphicon glyphicon-usd"></span> Faturamento</a>-->
                </div>
            </div>



            <div id="pecas_produzidas" style="width: 900px; height: 500px"></div>


        </div>


        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <script type="text/javascript">
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Mês', 'Quantidade Produzida', 'Quantidade Determinada'],
<?php
include_once "../modell/DetalheCelulaProduto.class.php";
$lote = new DetalheCelulaProduto();


//while ($mes_ini <= $mes_fim && $ano_ini<=$ano_fim) {
while ($mes_ini <= $mes_fim) {
    $matriz = $lote->graficoFaturamentoMes($mes_ini, $ano_ini);
    while ($dados = $matriz->fetchObject()) {
        if (!empty($dados->NPECAS)) {
            if ($mes_ini == '01')
                $mes = 'Janeiro';
            if ($mes_ini == '02')
                $mes = 'Fevereiro';
            if ($mes_ini == '03')
                $mes = 'Março';
            if ($mes_ini == '04')
                $mes = 'Abril';
            if ($mes_ini == '05')
                $mes = 'Maio';
            if ($mes_ini == '06')
                $mes = 'Junho';
            if ($mes_ini == '07')
                $mes = 'Julho';
            if ($mes_ini == '08')
                $mes = 'Agosto';
            if ($mes_ini == '09')
                $mes = 'Setembro';
            if ($mes_ini == '10')
                $mes = 'Outubro';
            if ($mes_ini == '11')
                $mes = 'Novembro';
            if ($mes_ini == '12')
                $mes = 'Dezembro';

            $pecas_determinadas = $dados->NPECAS / 3;
            $pecas_produzidas = $dados->NFEITAS / 3;
            //echo $SOMAVALOR ;           
            $resultado = "['$mes/$ano_ini',$pecas_produzidas,$pecas_determinadas],";
            echo $resultado;
        }
    }
    //echo $mes_ini;
    $mes_ini++;
}
?>
                ]);
                var options = {
                    title: 'Produção',
                    curveType: 'function',
                    legend: {position: 'bottom'}
                };
                var chart = new google.visualization.LineChart(document.getElementById('pecas_produzidas'));
                chart.draw(data, options);
            }
        </script>
    </body>
</html>