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

                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel-heading">
                            <form class="form-horizontal" method="post">                            
                                <div class="form-group">
                                    <label for="lote1" class="tamanho-fonte">Lote:</label><small> (Campo Obrigatório)</small>
                                    <select name="lote1" class="form-control" required="required" >                                       
                                        <?php
                                        echo "<option value='Selecione'>Selecione ...</option>";
                                        include_once '../modell/Lote.class.php';
                                        $lote = new Lote();
                                        $matriz = $lote->listaLote();

                                        while ($dados = $matriz->fetchObject()) {
                                            if ($dados->status == TRUE) {
                                                $cod = $dados->id_lote;
                                                $n_lote = $dados->numero;
                                                $descricao = $dados->descricao;
                                                echo "<option value=" . $n_lote . ">" . $n_lote . ' - - ' . $descricao . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">                               
                                    <label for="lote2" class="tamanho-fonte">Lote:</label><small> (Campo Obrigatório)</small>
                                    <select name="lote2" class="form-control" required="required" >                                       
                                        <?php
                                        echo "<option value='Selecione'>Selecione ...</option>";
                                        include_once '../modell/Lote.class.php';
                                        $lote = new Lote();
                                        $matriz = $lote->listaLote();

                                        while ($dados = $matriz->fetchObject()) {
                                            if ($dados->status == TRUE) {
                                                $cod = $dados->id_lote;
                                                $n_lote = $dados->numero;
                                                $descricao = $dados->descricao;
                                                echo "<option value=" . $n_lote . ">" . $n_lote . ' - - ' . $descricao . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

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

                            </form>
                        </div>

                        <div class="panel-body">
                            <form action="gerarPDF2.php" class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <div class="col-xs-6">
                                                <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading" style="text-align: center;">
                                    Lista de produtos adicionados lote
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">                    
                                    <table class="table table-condensed table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>                                        
                                                <th>Lote</th> 
                                                <th>Descrição</th> 
                                                <th>Data</th>
                                                <th>Peças</th>
                                                <th>Custo</th>
                                                <th>Valor</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>                                        
                                                <th>Lote</th> 
                                                <th>Descrição</th> 
                                                <th>Data</th>
                                                <th>Peças</th>
                                                <th>Custo</th>
                                                <th>Valor</th>
                                                <th>Total</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>                       
                                            <?php
                                            if (isset($_POST['enviar'])) {
                                                $data_ini = $_POST['data'];
                                                $data_fim = $_POST['data2'];
                                                $lote1 = $_POST['lote1'];
                                                $lote2 = $_POST['lote2'];
                                                $data1 = explode("-", $data_ini);
                                                $data2 = explode("-", $data_fim);

                                                if ($lote1 == '' || $lote1 == '') {
                                                    echo 'Voce deve informar os campos acima';
                                                } else {

                                                    include_once "../modell/DetalheLoteProduto.class.php";
                                                    $lote = new DetalheLoteProduto();
                                                    $matriz = $lote->listaLoteProdutoCondicaoLoteDataLote($data_ini, $data_fim, $lote1, $lote2);
                                                    $numero = 0;
                                                    $q_peca = 0;
                                                    $custo_total = 0;
                                                    $subtotal = 0;

                                                    while ($dados = $matriz->fetchObject()) {
                                                        $tempo_producao = explode(":", $dados->tempo_producao);
                                                        $calculo[0] = ($tempo_producao[0] * 60) * '0.15';
//                                            //echo $calculo[0] ;
                                                        $calculo[1] = $tempo_producao[1] * '0.15';
                                                        $calculo[2] = ($tempo_producao[1] / 60 ) * '0.15';

                                                        
                                                        if (empty($dados->material) || empty($dados->custo_material) || empty($dados->material2) || empty($dados->custo_material2)){
                                                            $dados->material = 0;
                                                            $dados->custo_material = 0;
                                                            $dados->material2 = 0;
                                                            $dados->custo_material2 =0;
                                                        }else{
                                                            $dados->material = $dados->material;
                                                            $dados->custo_material = $dados->custo_material;
                                                            $dados->material2 = $dados->material2;
                                                            $dados->custo_material2 = $dados->custo_material2;
                                                        }
                                                        
                                                        $produto = (float) ($dados->material * $dados->custo_material);
                                                        $subProduto = (float) ($dados->material2 * $dados->custo_material2);

                                                        //$valor_peca = (float) ($dados->material * $dados->custo_material) + ($dados->material2 * $dados->custo_material2);
                                                        //print_r($calculo[0] + $calculo[1] + $calculo[2]);
//                                                print_r($subProduto);
                                                        //$unitario = (float) ((($produto * 1) + ($subProduto * 1)) + $calculo[0] + $calculo[1] + $calculo[2] + $mao_obra_indireta);
                                                        //print_r($total);
                                                        //$total = $unitario * $dados->n_peca;


                                                        $DATALOTE = explode("-", $dados->DATALOTE);
                                                        $DATALOTE = $DATALOTE[2] . '/' . $DATALOTE[1] . '/' . $DATALOTE[0];

                                                        //if (empty($dados->PRECOLOTE)){
                                                        //	$total = (float)  $dados->preco * $dados->n_peca;
                                                        ///	$preco = $dados->preco;
                                                        //}else {
                                                        $total = (float) $dados->PRECOLOTE * $dados->n_peca;
                                                        $preco = $dados->PRECOLOTE;
                                                        // }
                                                        $CUSTOPRODUCAO = $dados->CUSTOPRODUCAO;
                                                        echo "<tr>
                                                            <td>" . $dados->numero . "</td>
                                                            <td>" . $dados->descricao . "</td>
                                                            <td>" . $DATALOTE . "</td>                                                            
                                                            <td title='n_peca' class='editavel'><b>" . $dados->n_peca . "</b></td> 
                                                            <td title='custo' class='editavel'><b>" . 'R$ ' . number_format($CUSTOPRODUCAO, 2, ',', '.') . "</b></td>
                                                            <td title='preco' class='editavel'><b>" . 'R$ ' . number_format($preco, 2, ',', '.') . "</b></td>                                                                                                                   
                                                            <td><b>" . 'R$ ' . number_format($total, 2, ',', '.') . "</b></td>
                                                                                                                              
                                                      </tr>";
                                                        $subtotal += $total;
                                                        $custo_total += $CUSTOPRODUCAO;
                                                        $numero = $dados->numero;
                                                        $q_peca += $dados->n_peca;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>                                            
                                                        <th>Lotes</th>
                                                        <th>Total de Peças</th>
                                                        <th>Soma Custo</th>
                                                        <th>Sub Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                    <tr>
                                                        <th> <?= $lote1 ?> até <?= $lote2 ?> </th>
                                                        <th> <?= $q_peca ?> </th>
                                                        <th>R$ <?= number_format($custo_total, 2, ',', '.') ?> </th>
                                                        <th>R$ <?= number_format($subtotal, 2, ',', '.') ?> </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>


                                <!--</div>-->
                            </div>                     

                            <?php
                            /* Captação de dados */
                            $buffer = ob_get_contents(); // Obtém os dados do buffer interno
                            $filename = "code.html"; // Nome do arquivo HTML
                            file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php require_once "./actionRodape.php"; ?>

    </body>
</html>
