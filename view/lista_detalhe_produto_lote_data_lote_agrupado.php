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
                        if ($data2[1] == '01')
                            $mes = 'Janeiro';
                        if ($data2[1] == '02')
                            $mes = 'Fevereiro';
                        if ($data2[1] == '03')
                            $mes = 'Março';
                        if ($data2[1] == '04')
                            $mes = 'Abril';
                        if ($data2[1] == '05')
                            $mes = 'Maio';
                        if ($data2[1] == '06')
                            $mes = 'Junho';
                        if ($data2[1] == '07')
                            $mes = 'Julho';
                        if ($data2[1] == '08')
                            $mes = 'Agosto';
                        if ($data2[1] == '09')
                            $mes = 'Setembro';
                        if ($data2[1] == '10')
                            $mes = 'Outubro';
                        if ($data2[1] == '11')
                            $mes = 'Novembro';
                        if ($data2[1] == '12')
                            $mes = 'Dezembro';

                        $data_ini1 = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
                        $data_fim1 = $data2[2] . '/' . $data2[1] . '/' . $data2[0];
                        ?>


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="text-align: center;">
                                        Relação de lotes agrupados no período de <?= $data1[2] . ' de ' . $mes . ' de ' . $data1[0] ?> a <?= $data2[2] . ' de ' . $mes . ' de ' . $data2[0]; ?>
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">                   
                                        <table class="table table-condensed table-hover" id="dataTables-example">

                                            <thead>
                                                <tr>                                        
                                                    <th>Lote</th> 
                                                    <th>Descrição</th>
                                                    <th>Quantidade</th>                                       
                                                    <th>Total</th>
                                                </tr>
                                            </thead>                                
                                            <tbody>                       
                                                <?php
                                                include_once "../modell/DetalheLoteProduto.class.php";
                                                $lote = new DetalheLoteProduto();
                                                $matriz = $lote->listaLoteProdutoCondicaoLoteDataLoteAgrupado($data_ini, $data_fim, $lote1, $lote2);
                                                $numero = 0;
                                                $q_peca = 0;
                                                $custo_total = 0;
                                                $subtotal = 0;

                                                while ($dados = $matriz->fetchObject()) {

                                                    echo "<tr>
                                                            <td>" . $dados->numero . "</td>
                                                            <td>" . $dados->DESCRICAOLOTE . "</td>                                                                                                                 
                                                            <td title='n_peca' class='editavel'><b>" . number_format($dados->NUMEROPECA, 0, '.', '.') . "</b></td>                                                                                                                                                                              
                                                            <td><b>" . 'R$ ' . number_format($dados->RESULTADO, 2, ',', '.') . "</b></td>
                                                                                                                              
                                                      </tr>";
                                                    $subtotal += $dados->RESULTADO;
                                                    $q_peca += $dados->NUMEROPECA;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <table class="table table-hover">
                                            <thead style="text-align: center">
                                                <tr>
                                                    <th>Lotes</th>
                                                    <th>Total de Peças</th>
                                                    <th>SubTotal</th>
                                                </tr>
                                            </thead>
                                            <tbody style="text-align: left">
                                                <tr>
                                                    <th> <?= $lote1 ?> à <?= $lote2 ?> </th>
                                                    <th> <?= number_format($q_peca, 0, '.', '.') ?> </th>                                                
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

        <?php require_once "./actionRodape.php"; ?>

    </body>
</html>
