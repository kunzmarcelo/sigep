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
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>



        <!-- Custom CSS -->


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

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
                        echo $resultado->nome
                        ?>
                        <!--Listagem de peças parâmetros-->

                    </h1>
                </div>
                <!-- /.col-lg-12 -->

                <div class="row">
                    <!--                    <div class="col-lg-12">-->
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="form-group">
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
                                                    $n_lote = $dados->funcionarios;
                                                    echo "<option value=" . $cod . ">" . $n_lote . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>                                    
                                <div class="form-group">                                
                                    <div class="col-xs-6">
                                        <label for="data">Data inicial:</label>
                                        <input type="date" name="data" value="<?php echo date('Y-m-01') ?>" class="form-control" required="required" >
                                    </div>									
                                </div>									
                                <div class="form-group">                                
                                    <div class="col-xs-6">
                                        <label for="data2">Data Final:</label>
                                        <input type="date" name="data2" value="<?php echo date('Y-m-t') ?>" class="form-control" required="required" >
                                    </div>									
                                </div>									
                                <div class="form-group">                                
                                    <div class="col-xs-6">                               
                                        <button name="enviar" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Enviar</button>
                                        <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!--</div>-->
                </div>
                <div class="row">
                    <div class="col-lg-12">  
                        <div class="form-group">
                            <form action="gerarPDF.php" class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group"> 
                            <button type="button" value="Imprimir" class="btn btn-default" id="btn">Imprimir</button>
                            <!--<input type="submit" value="Salvar em PDF" class="btn btn-default">-->

                        </div>
                    </div>
                </div>
                <?php
                ob_start(); // Ativa o buffer de saida do PHP

                function CriaCodigo() { //Gera numero aleatorio
                    for ($i = 0; $i < 40; $i++) {
                        $tempid = strtoupper(uniqid(rand(), true));
                        $finalid = substr($tempid, -12);
                        return $finalid;
                    }
                }
                ?>
                <div id="impressao" >
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading" style="text-align: center">
                                    Listagem
                                </div>
                                <div class="panel-body">
                                    <table width="100%" class="table table-striped table-hover table-condensed" id="tblEditavel">
                                        <thead>
                                            <tr>                                            
                                                <th>Data</th>                                           
                                                <th>Qtd. Det.</th>                                           
                                                <th>Qtd. Prod</th>                                            
                                                <th>Velocidade</th>
                                            </tr>
                                        </thead>
                                        <tbody>                       

                                            <?php
                                            if (isset($_POST['enviar'])) {
                                                $data_ini = $_POST['data'];
                                                $data_fim = $_POST['data2'];
                                                $id_celula = $_POST['id_celula'];

                                                $data1 = explode("-", $data_ini);
                                                include_once "../modell/DetalheCelulaProduto.class.php";
                                                $lote = new DetalheCelulaProduto();
                                                $matriz = $lote->listaCelulaProdutoPecasVelocidade($data_ini, $data_fim, $id_celula);
                                                $diferenca = 0;
                                                $funcionarios = 0;

                                                while ($dados = $matriz->fetchObject()) {
                                                    $data1 = explode("-", $dados->DATACELULA);
                                                    $diferenca = ((( $dados->pecas_finalizadas * 100)) / $dados->pecas_determinadas); //porcentagem
                                                    if (number_format($diferenca, 2, '.', '.') <= '79.99') {
                                                        $diferenca2 = "<span class='label label-danger'>" . number_format($diferenca, 2, '.', '.') . " %</span>";
                                                    } else {
                                                        $diferenca2 = "<span class='label label-info'>" . number_format($diferenca, 2, '.', '.') . " %</span>";
                                                    }
                                                    echo "<tr>                                                        
                                                        <td>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>                                                                                                          
                                                        <td title='$dados->pecas_determinadas peças' class=''>" . $dados->pecas_determinadas . " peças</td>                                                 
                                                        <td title='$dados->pecas_finalizadas peças' class=''>" . $dados->pecas_finalizadas . " peças </td>
                                                        <td title='' class=''>" . $diferenca2 . "</td>
                                                  </tr>";
                                                    $funcionarios += $dados->funcionarios;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="chart_div" style="width: 100%; height:500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                google.charts.load('current', {'packages': ['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Dia', 'Estimado', 'Performance'],
    <?php
    $data_ini = $_POST['data'];
    $data_fim = $_POST['data2'];
    $id_celula = $_POST['id_celula'];

    $data1 = explode("-", $data_ini);
    include_once "../modell/DetalheCelulaProduto.class.php";
    $lote = new DetalheCelulaProduto();
    $matriz = $lote->listaCelulaProdutoPecasVelocidade($data_ini, $data_fim, $id_celula);
    $diferenca = 0;
    $funcionarios = 0;
    while ($dados = $matriz->fetchObject()) {
        $funcionarios = $dados->funcionarios;
        $data1 = explode("-", $dados->DATACELULA);
        $diferenca = ((( $dados->pecas_finalizadas * 100)) / $dados->pecas_determinadas); //porcentagem
        //$dataCompleta = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
        $dataCompleta = $data1[2];

        $diferenca2 = number_format($diferenca, 2, '.', '.');

        echo "['$dataCompleta',79.99,  $diferenca2],";
    }
    ?>
                    ]);

                    var options = {
                        title: "Performance de trabalho <?= $funcionarios ?>",
                        hAxis: {title: 'Dia', titleTextStyle: {color: '#333'}},
                        vAxis: {minValue: 0, maxValue: 100}
                    };

                    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }
            </script>
            <?php
        }

        /* Captação de dados */
        $buffer = ob_get_contents(); // Obtém os dados do buffer interno
        $filename = "code.html"; // Nome do arquivo HTML
        file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
        ?>  
<?php require_once "./actionRodape.php"; ?>
    </body>
</html>
