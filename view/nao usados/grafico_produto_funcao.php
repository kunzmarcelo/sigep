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

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Kunz, Marcelo 2014">

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
            <?php require_once './actionfonteMenu.php'; ?>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Gráfico demostrativo</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel-heading">
                        <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                        <a href="cadastra_operacao.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post">                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="id_produto" class="tamanho-fonte">Produto:</label><small> (Campo Obrigatório)</small>
                                    <select name="id_produto" id="id_produto" class="form-control" required="required" onchange="teste">                                       
                                        <?php
                                        echo "<option value=''>Selecione ...</option>";
                                        include_once '../modell/Produto.class.php';
                                        $fun = new Produto();
                                        $matriz = $fun->listaProduto();

                                        while ($dados = $matriz->fetchObject()) {
                                            if ($dados->status == true) {
                                                $cod = $dados->id_produto;
                                                $nome = $dados->descricao;
                                                echo "<option value=" . $cod . ">" . $nome . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>                                
                                <div class="form-group">
                                    <button type="submit" name="enviar" id="enviar" class="btn btn-success">Enviar</button>
                                    <button type="reset" name="cancelar" id="cancelar" class="btn btn-default">Cancelar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group">
                        <form action="gerarPDF2.php" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-xs-6 col-sm-4">
                                    <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="chart_div"></div>

                    <?php
                    $id_produto = \filter_input(INPUT_POST, 'id_produto');
                    $enviar = \filter_input(INPUT_POST, 'enviar');

                    if (isset($enviar)) {
                        include_once "../modell/FuncaoNovo.class.php";
                        $lote = new FuncaoNovo();
                        $matriz = $lote->listaFuncaoProduto($id_produto);
                        ?>
                        <?php
                        $teste_conta = count($matriz->fetchObject()); //echo $teste_conta;
                        while ($dados = $matriz->fetchObject()) {
                            $descricao = $dados->descricao;
                            $funcao = $dados->funcao;
                            $tempo = $dados->tempo;
                            $custo_funcao = number_format($dados->custo_funcao, 2, ',', '');
                        }
                        ?>


                        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                        <script type="text/javascript">
                                            google.charts.load('current', {packages: ["orgchart"]});
                                            google.charts.setOnLoadCallback(drawChart);

                                            function drawChart() {
                                                var data = new google.visualization.DataTable();
                                                data.addColumn('string', 'Name');
                                                data.addColumn('string', 'Manager');
                                                data.addColumn('string', 'ToolTip');

                                                // For each orgchart box, provide the name, manager, and tooltip to show.
                                                data.addRows([
                                                    [{v:<?= "'$descricao'" ?>, f:<?= "'$descricao'" ?>}, '', ''],
    <?php
    include_once "../modell/FuncaoNovo.class.php";
    $lote = new FuncaoNovo();
    $matriz = $lote->listaFuncaoProduto($id_produto);
    while ($dados = $matriz->fetchObject()) {
        $descricao = $dados->descricao;
        $funcao = $dados->funcao;
        $tempo = $dados->tempo;
        $custo_funcao = number_format($dados->custo_funcao, 2, ',', '');

        echo "['$funcao','$descricao',''],";
    }
    ?>
                                                ]);

                                                // Create the chart.
                                                var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
                                                // Draw the chart, setting the allowHtml option to true for the tooltips.
                                                chart.draw(data, {allowHtml: true});
                                            }
                        </script>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>


