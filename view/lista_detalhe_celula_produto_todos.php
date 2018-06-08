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

        <script type="text/javascript">
            $(document).ready(function () {
                $('#tblEditavel tbody tr td.editavel').dblclick(function () {
                    if ($('td > input').length > 0) {
                        return;
                    }
                    var conteudoOriginal = $(this).text();
                    var novoElemento = $('<input/>', {type: 'text', value: conteudoOriginal});
                    $(this).html(novoElemento.bind('blur keydown', function (e) {
                        var keyCode = e.which;
                        var conteudoNovo = $(this).val();
                        if (keyCode == 13 && conteudoNovo != '' && conteudoNovo != conteudoOriginal) {
                            var objeto = $(this);
                            $.ajax({
                                type: "POST",
                                url: "alterarDetalheCelulaProduto.php",
                                data: {
                                    id: $(this).parents('tr').children().first().text(),
                                    campo: $(this).parent().attr('title'),
                                    valor: conteudoNovo
                                },
                                success: function (result) {
                                    objeto.parent().html(conteudoNovo)
                                    $('body').append(result);
                                    location.reload();
                                },
                            })
                        }
                        if (e.type == "blur") {
                            $(this).parent().html(conteudoOriginal);
                        }
                    }));

                    $(this).children().select();
                })
            })
        </script>

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
                    <div class="col-lg-12">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group"> 
                                        <div class="col-xs-6 ">
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
                                        <div class="col-xs-6 ">
                                            <label for="data">Data inicial:</label>
                                            <input type="date" name="data" value="" class="form-control" required="required" >
                                        </div>									
                                    </div>									
                                    <div class="form-group">                                
                                        <div class="col-xs-6 ">
                                            <label for="data2">Data Final:</label>
                                            <input type="date" name="data2" value="" class="form-control" required="required" >
                                        </div>									
                                    </div>									
                                    <div class="form-group">                                
                                        <div class="col-xs-6 ">                               
                                            <button name="enviar" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Enviar</button>
                                            <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <div class="panel-body">
                            <div class="col-lg-6">  
                                <div class="form-group">
                                    <form action="gerarPDF.php" class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-xs-6 ">
                                                <input type="submit" value="Salvar em PDF" class="btn btn-default">
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
                            //$status = $_POST['status'];
                            $id_celula = $_POST['id_celula'];

                            $data1 = explode("-", $data_ini);
                            $data2 = explode("-", $data_fim);

                            $dataInteira = $data1[2] . '/' . $data1[1] . ' - ' . $data2[2] . '/' . $data2[1] . '/' . $data2[0];
                            ?>
                            <div class="panel panel-default">         
                                <div class="panel-heading" style="text-align: center;">
                                    Faturamento no período de <?= $dataInteira ?> 
                                </div>
                                <!--</div>-->
                                <div class="table-responsive">                    
                                    <table class="table table-hover table-striped table-condensed" id="tblEditavel">
                                        <thead>
                                            <tr>
                                                <!--<th>Célula</th>-->                               
                                                <th>Produto</th>
                                                <th>¹ Produção Estimada</th>
                                                <th>² Produção Executada</th>
                                                <th>³ Faltaram</th>
                                                <th>Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>                       

                                            <?php
                                            $data1 = explode("-", $data_ini);
                                            include_once "../modell/DetalheCelulaProduto.class.php";
                                            $lote = new DetalheCelulaProduto();
                                            $matriz = $lote->somaMensalProdutoCelula($data_ini, $data_fim, $id_celula);
                                            $diferenca = 0;
                                            $nomes = 0;
                                            $sub_total = 0;
                                            $somaTempoSegu = 0;
                                            $somaPeca = 0;
                                            $somaPecaPronta = 0;

                                            while ($dados = $matriz->fetchObject()) {
                                                $faltas = $dados->NPECAS - $dados->NFEITAS;
                                                echo "<tr>
                                              <td>" . $dados->descricao . "</td>
                                              <td>" . $dados->NPECAS . "</td>
                                              <td>" . $dados->NFEITAS . "</td>
                                              <td>" . $faltas . "</td>
                                              <td>R$ " . number_format($dados->NFEITAS * $dados->preco, 2, ',', '.') . "</td>
                                              </tr>";
                                                $nomes = $dados->funcionarios;
                                                $somaPeca += $dados->NPECAS;
                                                $somaPecaPronta += $dados->NFEITAS;
                                                $sub_total += $dados->NFEITAS * $dados->preco;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <hr>                   
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Célula</th>                                
                                            <th>Total peças determinadas</th>
                                            <th>Total Peças executadas</th>
                                            <th>Total faltaram</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?= $nomes; ?>
                                            </td>                                   
                                            <td>
                                                <?= $somaPeca ?> peças
                                            </td>
                                            <td><?= $somaPecaPronta ?> peças</td>                                
                                            <td><?= $somaPeca - $somaPecaPronta ?> peças</td>                                
                                            <td>R$ <?= number_format($sub_total, 2, ',', '.') ?></td>                                
                                        </tr>
                                    </tbody>                            
                                </table>
                            </div>

                            <hr>
                            <!--</div>-->
                            <!--</div>-->
                            <div class="form-group">
                                <div class="col-xs-12 col-sm-12">
                                    <!--<label for="legenda">¹Tempo: Hora final - Hora inicial.</label><br>-->
                                    <label for="legenda">¹ Produção Estimada: Produção determinada nas metas de produção diária</label><br>
                                    <label for="legenda">² Produção Executada: Produção executada no dia.</label><br>
                                    <label for="legenda">³ Faltaram: diferença entre produção estimada e produção executada</label><br>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
//                    }
        }
        /* Captação de dados */
        $buffer = ob_get_contents(); // Obtém os dados do buffer interno
        $filename = "code.html"; // Nome do arquivo HTML
        file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
        ?>  
        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>
