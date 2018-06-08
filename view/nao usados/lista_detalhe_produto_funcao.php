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
                                url: "alterarDetalheProdutoFuncao.php",
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
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Lista de custo de produtos</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel-heading">
                        <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                        <a href="cadastra_detalhe_produto_funcao.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post">
                            <div class="col-lg-6">
                                <div class="form-group">                              
                                    <label for="id_produto" class="tamanho-fonte">Produto</label><small> (Campo Obrigatório)</small>
                                    <select name="id_produto" class="form-control" required="required" >                                       
                                        <?php
                                        echo "<option value='Selecione'>Selecione ...</option>";
                                        include_once '../modell/Produto.class.php';
                                        $prod = new Produto();
                                        $matriz = $prod->listaProduto();

                                        while ($dados = $matriz->fetchObject()) {
                                            $cod = $dados->id;
                                            $descricao = $dados->descricao;
                                            echo "<option value=" . $cod . ">" . $descricao . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button name="enviar" class="btn btn-success">Enviar</button>
                                    <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-6">  
                            <div class="form-group">
                                <form action="gerarPDF2.php" class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-xs-6 col-sm-4">
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
                    <div class="panel panel-primary">            
                        <div class="panel-heading" style="text-align: center;">
                            Lista de produtos com custo de produção por função
                        </div>
                        <div class="table-responsive">                    
                            <table class="table table-hover" id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th title="produto"><img src='../bibliotecas/img/1486591262_id.png'></th>

                                        <th>Funções</th>
                                        <th>Tempo</th>
                                        <th>Mão de obra direta</th>
                                        <th>Sub total</th>
                                        <th title="Excluir item"><img src='../bibliotecas/img/1486594122_delete.png'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_POST['enviar'])) {
                                        $id_produto = $_POST['id_produto'];
                                        if ($id_produto == '' || $id_produto == 'Selecione') {
                                            echo "<div class='alert alert-warning alert-dismissable'>
                                                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                        Você deve selecionar os campos acima.
                                                    </div>";
                                        } else {
                                            include_once "../modell/DetalheProdutoFuncao.php";
                                            $lote = new DetalheProdutoFuncao();
                                            $matriz = $lote->listaProdutoFuncaoCondicaoProduto($id_produto);
                                            $teste = $lote->somaValorMaoObra();


//                                            ******** pucha o valor de mao de obra indireta da tabela de custo de mao de obra ******
                                            $dados1 = $teste->fetchObject();
                                            $mao_obra_indireta = $dados1->custoMaoObra;
//                                            ******** pucha o valor de mao de obra indireta da tabela de custo de mao de obra ******



                                            if (empty($matriz)) {
                                                echo "<div class='alert alert-info alert-dismissable'>
                                                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                        Não possui nenhum registro armazenado.
                                                    </div>";
                                            } else {
                                                $tecido_metros = 0;
                                                $custo_metro = 0;
                                                $custoTotal = 0;
                                                $somaTempo = 0;
                                                //$mao_obra_indireta = 0;

                                                while ($dados = $matriz->fetchObject()) {
                                                    $tempo_producao2 = explode(":", $dados->tempo_funcao);
                                                    $calculo0 = ($tempo_producao2[0] * 60) * $dados->mao_obra;
                                                    $calculo1 = $tempo_producao2[1] * $dados->mao_obra;
                                                    $calculo2 = ($tempo_producao2[2] / 60 ) * $dados->mao_obra;

                                                    $custo = $calculo0 + $calculo1 + $calculo2;
                                                    echo "<tr>
                                                            <td title='id'>" . $dados->idDetalhe . "</td>
                                                            <td>" . $dados->funcao . "</td>
                                                            <td title='tempo_funcao' class='editavel'>" . $dados->tempo_funcao . "</td>
                                                            <td title='mao_obra' class='editavel'>" . 'R$ ' . number_format($dados->mao_obra, 2, ',', '.') . "</td>
                                                            <td title='mão de obra * o tempo'> <b>" . 'R$ ' . number_format($custo, 2, ',', '.') . "</b></td>
                                                            <td>
                                                                <a href='#' id='ativar' value='ativar'  onclick='ativar(" . $dados->idDetalhe . ");'>
                                                                    <img src='../bibliotecas/img/trash_can.png'>
                                                                </a>
                                                            </td>
                                                        </tr>";

                                                    $custoTotal += $custo;
                                                    $descricao = $dados->descricao;
                                                    //$tecido_metros = $dados->tecido;
                                                    //$custo_metro = $dados->custo_tecido;
                                                    //calculo da tabela produto

                                                    $custo_sub_produto = $dados->tecido2 * $dados->custo_tecido2;
                                                    $custoProduto = (float) ((($dados->tecido * $dados->custo_tecido)) + $custo_sub_produto);

//                                                    print_r($custoProduto);
                                                    //echo 'deu';

                                                    $tempo_funcao = \explode(":", $dados->tempo_funcao);
                                                    $horas1 = $tempo_funcao[0] * 3600;
                                                    $horas2 = $tempo_funcao[1] * 60;
                                                    $horas3 = $tempo_funcao[2];

//                                                   
                                                    $somaTempo += $horas1 + $horas2 + $horas3;
                                                    //echo $somaTempo;
                                                }
//                                                echo $somaTempo;
                                                $total_segundos = $somaTempo;

                                                $horas = floor($total_segundos / (60 * 60));
                                                $sobra_horas = ($total_segundos % (60 * 60));
                                                $minutos = floor($sobra_horas / 60);
                                                $sobra_minutos = ($sobra_horas % 60);
                                                $segundos = $sobra_minutos;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!--</div>-->
                        </div>

                        <div class="panel panel-primary">                        
                            <div class="panel-heading" style="text-align: center;">
                                <label><?= $descricao; ?></label>
                            </div>
                            <table class="table table-hover" >                            
                                <tbody>
                                    <tr>
                                        <th style="text-align: left;">Tempo de produção:</th>
                                        <td class="alert alert-info">
                                            <p class="alert-link">
                                                R$ <?= $horas . ' hora(s), ' . $minutos . ' minuto(s) e ' . $segundos . ' segundo(s)'; ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">Mão de obra direta:</th>
                                        <td class="alert alert-info">
                                            <p class="alert-link">
                                                R$ <?= number_format($custoTotal, 2, ',', '.'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">Mão de obra indireta:</th>
                                        <td class="alert alert-info">
                                            <p class="alert-link">
                                                R$ <?= number_format($mao_obra_indireta, 2, ',', '.'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">Custo do Produto:</th>
                                        <td class="alert alert-info">                                           
                                            <p class="alert-link">
                                                <?php
//                                                if (!isset($custoProduto)){
//                                                    $custoProduto = 0;
//                                                  echo number_format($custoProduto, 2, ',', '.');
//                                                }else{
//                                                 echo number_format($custoProduto, 2, ',', '.');
//                                                }                                              
                                                ?>
                                                R$ <?= number_format($custoProduto, 2, ',', '.'); ?>
                                            </p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th style="text-align: left;"><h3>Total:</h3></th>
                                <td class="alert alert-info">
                                    <p class="alert-link">
                                    <h3>R$ <?= number_format($custoProduto + $custoTotal + $mao_obra_indireta, 2, ',', '.'); ?></h3>
                                    </p>
                                </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="form-group">
                            <label for="legenda">* Tempo de produção = é a soma do tempo de cada função acima informado</label><br>
                        </div>
                        <div class="form-group">
                            <label for="legenda">* Mão de obra direta = é a soma do sub total</label>
                        </div>
                        <div class="form-group">
                            <label for="legenda">* Mão de obra indireta = é soma de valores que é feita da tabela custo mão de obra</label>
                        </div>
                        <div class="form-group">
                            <label for="legenda">* Custo do Produto = é a soma do custo de produto da tabela produto</label>
                        </div>
                        <div class="form-group">
                            <label for="legenda">* Custo total = é a soma da mão de obra direta + a mão de obra indireta + o custo do produto</label>
                        </div>
                    </div>
                    <?php
                }
                /* Captação de dados */
                $buffer = ob_get_contents(); // Obtém os dados do buffer interno
                $filename = "code.html"; // Nome do arquivo HTML
                file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
                ?>  
            </div>
        </div>
        <script src="../ajax/deletar_detalhe_produto_funcao.js"></script>
        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>