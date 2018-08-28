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
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
                            <label for="id_produto" class="tamanho-fonte">Produto:</label><small> (Campo Obrigatório)</small>
                            <select name="id_produto" id="id_produto" class="form-control" required="required" onchange="this.form.submit()">                                       
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
                    </div>
                </form>

                <?php
                $id_produto = \filter_input(INPUT_POST, 'id_produto');


                if (empty($id_produto)) {
                    echo" <div class='alert alert-warning' role='alert'>
                        <h4> <span class='glyphicon glyphicon-warning-sign'></span> 
                        Oops! Selecione um Produto.</h4>
                    </div>";
                } else {
                    ?>

                    <div class="panel panel-default"style="width: 100%; height: 100%;">
                        <div class="panel-heading" style="text-align: center">
                            Organograma de Produtos 
                        </div>
                        <div id="chart_div" ></div>
                    </div>
                </div>
            </div>


            <?php
            include_once "../modell/Operacao.class.php";
            $lote = new Operacao();
            $matriz = $lote->listaOperacaoProduto($id_produto);

            //$teste_conta = count($matriz->fetchObject()); //echo $teste_conta;
            while ($dados = $matriz->fetchObject()) {
                $descricao = $dados->descricao;
            }
            ?>


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
                            [{v: <?= "'$descricao'" ?>, f: '<?= "$descricao" ?><div style="color:green; font-style:italic">Produto</div>'}, '', 'The Product'],
                                <?php
                                    include_once "../modell/Setores.class.php";
                                    $prod2 = new Setores();
                                    $matriz2 = $prod2->listaSetor();
                                    while ($dados2 = $matriz2->fetchObject()) {
                                            echo ("[{v:'$dados2->descricao_setor', f:'$dados2->descricao_setor'},'$descricao','Setor'],");           
                                    }
                                    
                                    include_once "../modell/Operacao.class.php";
            $lote = new Operacao();
            $matriz = $lote->listaOperacaoProduto($id_produto);
        
                                    while ($dados = $matriz->fetchObject()) {

                                        echo ("['$dados->operacao','$dados->descricao_setor',''],");
                                        //   ['Alice', 'Mike', ''],
                                    }
                                    ?>


                                ]);

                        // Create the chart.
                        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
                        google.charts.load('current', {packages: ['orgchart']});
                        
                        // Draw the chart, setting the allowHtml option to true for the tooltips.
                        chart.draw(data, {allowHtml: true});
                    }
                </script>
        <?php
    }
    ?>


    <?php require_once "./actionRodape.php"; ?>

    </body>
</html>


