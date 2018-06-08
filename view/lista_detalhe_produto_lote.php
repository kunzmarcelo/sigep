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
                                url: "alterarProdutoLote.php",
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
                        <div class="panel-heading">
                            <form class="form-horizontal" method="post">                                
                                <div class="form-group">                                
                                    <div class="col-xs-6">
                                        <label for="n_lote" class="tamanho-fonte">Lote:</label><small> (Campo Obrigatório)</small>
                                        <select name="n_lote" class="form-control" required="required" >                                       
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

                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <button name="enviar" class="btn btn-success">Enviar</button>
                                        <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>
                                    </div>
                                </div>
                            </form>
                            <div class="form-group">
                                <form action="gerarPDF2.php" class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                        </div>
                                    </div>
                                </form>
                            </div>
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center;">
                                Listagem
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">                     
                                <table class="table table-hover" id="tblEditavel">
                                    <thead>
                                        <tr>                                        
                                            <th>#</th> 
                                            <th>Descrição</th> 
                                            <th>Data</th>
                                            <th>Observação</th>
                                            <th>Peças</th>                                        
                                            <th>Valor</th>
                                            <th>Total</th>
                                            <th><i class="fa fa-trash-o"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>                       
                                        <?php
                                        if (isset($_POST['enviar'])) {
                                            //echo 'chegou';
                                            //$id_funcionario = $_POST['id_funcionario'];
                                            $n_lote = $_POST['n_lote'];
                                            //$data1 = $_POST['data1'];
                                            if ($n_lote == '') {
                                                echo 'Voce deve informar os campos acima';
                                            } else {
                                                include_once "../modell/DetalheLoteProduto.class.php";
                                                $lote = new DetalheLoteProduto();
                                                $matriz = $lote->listaLoteProdutoCondicaoLote($n_lote);
                                                $numero = 0;
                                                $q_peca = 0;
                                                // $total = 0;
                                                $subtotal = 0;
                                                // $subtotal2 = 0;
                                                //include_once "../modell/DetalheProdutoFuncao.php";
                                                //$lote = new DetalheProdutoFuncao();
                                                //$teste = $lote->somaValorMaoObra();
//                                            ******** pucha o valor de mao de obra indireta da tabela de custo de mao de obra ******
                                                // $dados1 = $teste->fetchObject();
                                                // $mao_obra_indireta = $dados1->custoMaoObra;
                                                while ($dados = $matriz->fetchObject()) {
                                                    $tempo_producao = explode(":", $dados->tempo_producao);
                                                    $calculo[0] = ($tempo_producao[0] * 60) * '0.15';
//                                            //echo $calculo[0] ;
                                                    $calculo[1] = $tempo_producao[1] * '0.15';
                                                    $calculo[2] = ($tempo_producao[1] / 60 ) * '0.15';

                                                    //$produto = (float) ($dados->tecido * $dados->custo_tecido);
                                                    //$subProduto = (float) ($dados->tecido2 * $dados->custo_tecido2);
                                                    //$valor_peca = (float) ($dados->tecido * $dados->custo_tecido) + ($dados->tecido2 * $dados->custo_tecido2);
                                                    //print_r($calculo[0] + $calculo[1] + $calculo[2]);
//                                                print_r($subProduto);
                                                    //$unitario = (float) ((($produto * 1) + ($subProduto * 1)) + $calculo[0] + $calculo[1] + $calculo[2] + $mao_obra_indireta);
                                                    //print_r($total);
                                                    // $total = (float)  $dados->preco * $dados->n_peca;
                                                    // $total2 = (float) $dados->PRECOLOTE * $dados->n_peca;
                                                    //echo $total2;

                                                    $DATALOTE = explode("-", $dados->DATALOTE);
                                                    $DATALOTE = $DATALOTE[2] . '/' . $DATALOTE[1] . '/' . $DATALOTE[0];

                                                    //if (empty($dados->PRECOLOTE)){
                                                    //$total = (float)  $dados->preco * $dados->n_peca;
                                                    //$preco = $dados->preco;
                                                    //}else {
                                                    $total = (float) $dados->PRECOLOTE * $dados->n_peca;
                                                    $preco = $dados->PRECOLOTE;
                                                    // }

                                                    echo "<tr>
                                                            <td>" . $dados->IdDetalhe . "</td>
                                                            <td title='id_produto' class='editavel'>" . $dados->descricao . "</td>
                                                            <td>" . $DATALOTE . "</td>
                                                            <td title='observacao' class='editavel'>" . $dados->observacao . "</td>
                                                            <td title='n_peca' class='editavel'><b>" . $dados->n_peca . "</b></td>                                                            
                                                            <td title='preco' class='editavel'><b>" . 'R$ ' . number_format($preco, 2, ',', '.') . "</b></td>
                                                            <td><b>" . 'R$ ' . number_format($total, 2, ',', '.') . "</b></td>
                                                                <td>
                                                        <a href='#' id='ativar' value='ativar'  onclick='ativar(" . $dados->IdDetalhe . ");'>
                                                            <i class='fa fa-trash'></i>
                                                        </a>                                                    
                                                    </td>
                                                      </tr>";
                                                    $subtotal += $total;
                                                    $numero = $dados->numero;
                                                    $q_peca += $dados->n_peca;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>                                            
                                                    <th>Data do Lote</th>
                                                    <th>Lote Número</th>
                                                    <th>Total de Peças</th>                                               
                                                    <th>Sub Total Lote</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                <tr>
                                                    <th> <?= $DATALOTE ?> </th>
                                                    <th> <?= $numero ?> </th>
                                                    <th> <?= $q_peca ?> </th>                                                
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

        <script src="../ajax/deletar_detalhe_produto_lote.js"></script>
        <script src="../ajax/jquery.js"></script>
        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>
