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
                <?php
                if (isset($_POST['enviar'])) {
                    $data_ini = $_POST['data'];
                    $data_fim = $_POST['data2'];
//                    $id_funcionario = \filter_input(INPUT_POST, 'id_funcionario');
                    $data1 = explode("-", $data_ini);
                    $data2 = explode("-", $data_fim);
                    $data1 = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
                    $data2 = $data2[2] . '/' . $data2[1] . '/' . $data2[0];

                    //$data1 = $_POST['data1'];
                    if ($data_ini == '' || $data_ini == '') {
                        echo "<div class='alert alert-danger alert-dismissable'>
                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                Você deve selecionar os campos acima.
                                            </div>";
                    } else {
                        include_once "../modell/DiarioBordo.class.php";
                        $diario = new DiarioBordo();
                        //var_dump($data_ini, $data_fim, $id_funcionario);
                        $matriz = $diario->graficoPecasFuncionario($data_ini, $data_fim);



                        $pessoa = array();
                        while ($dados = $matriz->fetchObject()) {
                            $pessoa[$dados->nome] = array(
                                'pecas_boas' => $dados->pecas_boas,
                            );
                        }

                        $fp = fopen('grafico.json', 'w');
                        fwrite($fp, json_encode($pessoa));
                        fclose($fp);
                        //echo $o_json = json_encode($pessoa);
                    }
                }
                ?>

            </div>
            <div id="chart_div"></div>
        </div>


        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">

            // Load the Visualization API and the piechart package.
            google.charts.load('current', {'packages': ['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var jsonData = $.ajax({
                    url: "grafico",
                    dataType: "json",
                    async: false
                }).responseText;

                // Create our data table out of JSON data loaded from server.
                var data = new google.visualization.DataTable(jsonData);

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                chart.draw(data, {width: 400, height: 240});
            }

        </script>


    </body>
</html>