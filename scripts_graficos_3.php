

<!--GRAFICO DE PRODUCAO DA CELULA 4-->
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Mês', 'Peças Produzidas', 'Produção Determinada'],
<?php
include_once "../sigep2.0/modell/BancoDadosPDO.class.php";
$lote = new BancoDadosPDO();
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
    $matriz = $lote->listarTodosMuitosParaMuitos("celula_trabalho.funcionarios AS NOMES, detalhe_celula_produto.data, sum(detalhe_celula_produto.pecas_determinadas) AS NPECAS, sum(detalhe_celula_produto.pecas_finalizadas) AS NFEITAS ", "detalhe_celula_produto,celula_trabalho", "detalhe_celula_produto.id_celula = celula_trabalho.id_celula  AND detalhe_celula_produto.data='$data_ini' AND detalhe_celula_produto.id_celula='$id_celula'");
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
        var chart = new google.visualization.LineChart(document.getElementById('pecas_produzidas_celulas4'));
        chart.draw(data, options);
    }

</script>