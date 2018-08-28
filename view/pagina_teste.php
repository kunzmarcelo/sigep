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

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>



        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages': ['gauge']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Mes', 80],
                    ['Agosto',70],
                    
                ]);

                var options = {
                    width: 400, height: 120,
                    redFrom: 90, redTo: 100,
                    yellowFrom: 75, yellowTo: 90,
                    minorTicks: 5
                };

                var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

                chart.draw(data, options);

                
            }
        </script>
    </head>
</head>
<body>

    <div id="wrapper">
        <?php require_once './actionfonteMenu.php'; ?>
        <div id="page-wrapper">

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



            <div class="row">
                <div id='chart_div' style='width: 800px; height: 60px;'></div>
            </div>
        </div>
    </div>


    <script>
        $('#characterLeft').text('250 caracteres aceitos');
        $('#descricao').keyup(function () {
            var max = 250;
            var len = $(this).val().length;
            if (len >= max) {
                $('#characterLeft').text(' Você atingiu o limite de caracteres');
            } else {
                var ch = max - len;
                $('#characterLeft').text(ch + ' caracteres');
            }
        });
    </script>


    <script src="../ajax/detalhe_celula_produto/deletar_detalhe_celula_produto.js"></script>


    <?php require_once "./actionRodape.php"; ?>




</body>
</html>