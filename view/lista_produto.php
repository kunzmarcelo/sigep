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
                                url: "alterarProduto.php",
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


                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center">
                                Listagem
                            </div>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descrição</th>
                                        <th>Preço Médio</th>
                                        <th>Tempo</th>
                                        <th>Kg ou M</th>
                                        <th>Valor</th>
                                        <th>Sub material</th>
                                        <th>Kg ou M</th>
                                        <th>Valor</th>
                                        <th>SubTotal</th>
                                        <th><i class='fa fa-low-vision'></i></th>
                                        <th><i class='fa fa-trash-o'></i></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Descrição</th>
                                        <th>Preço Médio</th>
                                        <th>Tempo</th>
                                        <th>Kg ou M</th>
                                        <th>Valor</th>
                                        <th>Sub material</th>
                                        <th>Kg ou M</th>
                                        <th>Valor</th>
                                        <th>SubTotal</th>
                                        <th><i class='fa fa-low-vision'></i></th>
                                        <th><i class='fa fa-trash-o'></i></th>
                                    </tr>
                                </tfoot>
                                <tbody>                       

                                    <?php
                                    include_once "../modell/Produto.class.php";
                                    $lista = new Produto();
                                    $matriz = $lista->listaProduto();
                                    if (empty($matriz)) {
                                        echo "<div class='alert alert-info alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Não possui nenhum registro armazenado.
                                            </div>";
                                    } else {
                                        while ($dados = $matriz->fetchObject()) {
                                            if (empty($dados->material)) {
                                                $dados->material = 0;
                                            }
                                            if (empty($dados->material2)) {
                                                $dados->material2 = 0;
                                            }


                                            $tempo_producao = explode(":", $dados->tempo_producao);
                                            $custo_minuto = 0.25;
                                            $calculo[0] = ($tempo_producao[0] * 60) * $custo_minuto;
                                            $calculo[1] = $tempo_producao[1] * $custo_minuto;
                                            $calculo[2] = ($tempo_producao[2] / 60 ) * $custo_minuto;
                                            $soma_tempo = $calculo[1] + $calculo[0] + $calculo[2];
                                            $custo_sub_produto = $dados->material2 * $dados->custo_material2;


//                                          // ----->>>>> Novo calculo de custo de produção funciona sem o calculo do tempo, pois

                                            $subtotal = (float) ((($dados->material * $dados->custo_material) + $soma_tempo) + $custo_sub_produto);
//                                          é realizado quando lista o detalhe produto função ------>>>>> IMPORTANTE
                                            //$subtotal = (float) (($dados->tecido * $dados->custo_tecido) + $custo_sub_produto);
                                            //print_r($subtotal);

                                            if ($dados->status == true) {
                                                echo"<tr>
                                                <td title='id'>" . $dados->id_produto . "</td>                                                    
                                                <td title='descricao' class='editavel'>" . substr($dados->descricao, 0, 30) . "</td>                                                   
                                                <td title='preco' class='editavel'> <b>" . 'R$ ' . number_format($dados->preco, 2, ',', '.') . "</b></td>
                                                <td title='tempo_producao' class='editavel'>" . $dados->tempo_producao . "</td>
                                                <td title='material' class='editavel'>" . $dados->material . "</td>
                                                <td title='custo_material' class='editavel'>" . 'R$ ' . number_format($dados->custo_material, 2, ',', '.') . "</td>                                                    
                                                <td title = 'descricao2' class = 'editavel'>" . $dados->descricao2 . "</td>
                                                <td title='material2' class='editavel'>" . $dados->material2 . "</td>
                                                <td title='custo_material2' class='editavel'>" . 'R$ ' . number_format($dados->custo_material2, 2, ',', '.') . "</td>
                                                <td><b>" . 'R$ ' . number_format($subtotal, 2, ',', '.') . "</b></td>
                                                <td>     
                                                    <span class='glyphicon glyphicon-eye-open' id='ocultar' value='ocultar'  onclick='ocultar(" . $dados->id_produto . ");'></span> 													
                                                </td>
                                                <td>
                                                    <a href='#' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_produto . ");'>
                                                       <i class='fa fa-trash'></i>
                                                    </a>                                                    
                                                </td>";
                                                echo"</tr>";
                                            } else {
                                                echo"<tr>
                                                <td title='id'>" . $dados->id_produto . "</td>                                                    
                                                <td title='descricao' class='editavel'>" . substr($dados->descricao, 0, 30) . "</td>                                                   
                                                <td title='preco' class='editavel'> <b>" . 'R$ ' . number_format($dados->preco, 2, ',', '.') . "</b></td>
                                                <td title='tempo_producao' class='editavel'>" . $dados->tempo_producao . "</td>
                                                <td title='material' class='editavel'>" . $dados->material . "</td>
                                                <td title='custo_material' class='editavel'>" . 'R$ ' . number_format($dados->custo_material, 2, ',', '.') . "</td>                                                    
                                                <td title = 'descricao2' class = 'editavel'>" . $dados->descricao2 . "</td>
                                                <td title='material2' class='editavel'>" . $dados->material2 . "</td>
                                                <td title='custo_material2' class='editavel'>" . 'R$ ' . number_format($dados->custo_material2, 2, ',', '.') . "</td>
                                                <td><b>" . 'R$ ' . number_format($subtotal, 2, ',', '.') . "</b></td>
                                                <td>     
                                                    <span class='glyphicon glyphicon-eye-close' id='visualizar' value='visualizar'  onclick='visualizar(" . $dados->id_produto . ");'></span> 													
                                                </td>
                                                <td>
                                                    <a href='#' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_produto . ");'>
                                                        <i class='fa fa-trash'></i>
                                                    </a>                                                    
                                                </td>";
                                                echo"</tr>";
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!--</div>-->
                        <!--</div>-->
                        <?php
                        /* Captação de dados */
                        $buffer = ob_get_contents(); // Obtém os dados do buffer interno
                        $filename = "code.html"; // Nome do arquivo HTML
                        file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
                        ?>
                        <div class="row">
                            <div class="panel-body">
                                <div class="form-group">
                                    <form action="gerarPDF.php" class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-xs-6 col-sm-4">
                                                <button type="submit" class="btn btn-default">Exportar para PDF</button>
                                                <!--<input type="submit" value="Salvar em PDF" class="btn btn-default">-->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label for="legenda">*Sub Total ((tecido em metros * custo tecido) + (tecido em metros2 * custo tecido2) + (tempo * 0,15)).</label><br>
                                    <label for="legenda">* Para o calculo exato de produção <a href="calculoCustoPecaProduto.php">clique aqui.</a></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>


<!--<script src="../bootstrap/assets/js/toucheffects.js"></script>-->
        <script src="../ajax/deletar_produto.js"></script>
        <?php require_once "./actionRodape.php"; ?>


    </body>
</html>
