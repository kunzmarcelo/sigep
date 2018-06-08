<?php
include_once "../modell/ProducaoDiaria.class.php";
$lote = new ProducaoDiaria();
$matriz = $lote->listaProducao();
$count = 0;
$produto1 = array();
while ($dados = $matriz->fetchObject()) {
    $data1 = explode("-", $dados->data_producao);
    $data = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
    $hora_ini1 = explode(":", $dados->tempo_producao);
    $hora_fim1 = explode(":", $dados->tempo_meta);
    $tempo1 = (($hora_ini1[0] * 60) + ($hora_ini1[1]) + ($hora_ini1[2] / 60));
    $tempo2 = (($hora_fim1[0] * 60) + ($hora_fim1[1]) + ($hora_fim1[2] / 60));
    $resultado_tempo = (($tempo2 / $tempo1) - 1) * 100;
    $teste_procentagem = (($dados->meta_pecas / $dados->pecas_fabricadas) - 1) * 100;
    $produto = $dados->descricao;

    
    
    
    
    echo $count = $dados->descricao;
    
//
//
//
//    $produto1[$dados->descricao] = array(
//        'data_producao' => $dados->data_producao,
//        'tempo_producao' => $dados->tempo_producao,
//        'tempo_meta' => $dados->tempo_meta,
//        'pecas_fabricadas' => $dados->pecas_fabricadas,
//        'meta_pecas' => $dados->meta_pecas
//    );
//
//
//    $fp = fopen('meta.json', 'w');
//    fwrite($fp, json_encode($produto1));
//    fclose($fp);
////    var_dump(json_decode($fp));
//    //print_r($o_json = json_encode($produto1));
//    // $count ++;
}
?>


<html>
    <head>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
    google.charts.load('current', {'packages': ['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Data', 'Blazer de sarja', 'Blazer masculino', 'Blusinhas', 'Calças', 'Calças legging'],
            ['2014', 1000, 400, 200, 0, 0],
            ['2015', 1170, 460, 250, 0, 0],
            ['2016', 660, 1120, 300, 0, 0],
            ['2017', 1030, 540, 350, 0, 0]
        ]);
        var options = {
            chart: {
                title: 'Company Performance',
                subtitle: 'Sales, Expenses, and Profit: 2014-2017',
            }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
        </script>
    </head>
    <body>
        <div id="columnchart_material" style="width: 800px; height: 500px;"></div>
    </body>
</html>