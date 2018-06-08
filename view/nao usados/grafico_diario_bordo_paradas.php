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
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Listagem Diário de Bordo</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="panel-heading">
                    <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                    <a href="cadastra_diario_bordo_producao.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    <!--<a href="calculo_diario_bordo_oee_agrupado.php" class="btn btn-info" title="Calulo do OEE"><span class='glyphicon glyphicon-plus'></span> Calcular OEE</a>-->
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post">                            
                        <div class="col-lg-6">                                                                           
                            <div class="form-group"> 
                                <label for="data">Data inicial:</label>
                                <input type="date" name="data" value="data" class="form-control" required="required" >
                            </div>
                            <div class="form-group">

                                <label for="data2">Data final:</label>
                                <input type="date" name="data2" value="data2" class="form-control" required="required" >
                            </div>                            
                            <div class="form-group">
                                <button name="enviar" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Enviar</button>
                                <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="col-lg-6">  
                        <div class="form-group">
                            <form action="gerarPDF.php" class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-xs-6 col-sm-4">
                                        <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
            </div>
            <div id="piechart" style="width: 1080px; height: 860px;"></div>
        </div>


        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([

                    ['Motivo', 'Tempo/min'],
<?php
if (isset($_POST['enviar'])) {
    $data_ini = $_POST['data'];
    $data_fim = $_POST['data2'];
    
    include_once '../modell/DiarioBordo.class.php';
    $fun = new DiarioBordo();
    $matriz = $fun->graficoParadas($data_ini, $data_fim);
    
    $data1 = explode("-", $data_ini);
    $data_ini = $data1[2] . '/' . $data1[1];
    $data2 = explode("-", $data_fim);
    $data_fim = $data2[2] . '/' . $data2[1].'/'.$data1[0];
    while ($dados = $matriz->fetchObject()) {
        $data2 = explode("-", $dados->data);
        $dia = $data2[2] . '/' . $data2[1];

        
        $funcionario = explode(" ", $dados->nome);
        $maquina = $dados->numero;
        
        
        $TEMPO = explode(":", $dados->TEMPO);
        $minutos = (($TEMPO[0]*60) + $TEMPO[1] + $TEMPO[2]/60);
        $motivo = $dia. '-'.$funcionario[0] .'/'.$maquina.' - '. $dados->motivo.'min = '.$minutos;
        
        
        echo "['$motivo',$minutos],";
    }
}
?>

                ]);

                var options = {
                    title: "Gráfico de paradas não progrmadas entres <?= $data_ini; ?> - <?= $data_fim ?>",
                    is3D: true,
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                chart.draw(data, options);
            }
        </script>

    </body>
</html>