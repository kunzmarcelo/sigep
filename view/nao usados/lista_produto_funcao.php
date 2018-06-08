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

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Produtos e funções por produto</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel-heading">
                        <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                        <a href="cadastra_produto_funcao.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    </div>
                    <div class="panel-heading">
                        <form class="form-horizontal" method="post">

                            <div class="form-group">
                                <div class="col-xs-6 col-sm-4">                                   
                                    <label for="id_produto" class="tamanho-fonte">Produto</label><small> (Campo Obrigatório)</small>
                                    <select name="id_produto" class="form-control" required="required" >                                       
                                        <?php
                                        echo "<option value='Selecione'>Selecione ...</option>";
                                        include_once '../modell/Produto.class.php';
                                        $prod = new Produto();
                                        $matriz = $prod->listaProduto();

                                        while ($dados = $matriz->fetchObject()) {
                                            $cod = $dados->id;
                                            $nome = $dados->referencia;
                                            $descricao = $dados->descricao;
                                            echo "<option value=" . $cod . ">" . $descricao . "</option>";
                                        }
                                        ?>
                                    </select>                            

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-6 col-sm-4">
                                    <button name="enviar" class="btn btn-success">Enviar</button>
                                    <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>
                                </div>
                            </div>
                        </form>
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
                                        <!--<th hidden="hidden">#</th>-->                                        
                                        <th>Funções</th>
                                        <th>Tempo de função</th>
                                        <th>R$ da função por min</th>
                                        <th>Custo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_POST['enviar'])) {

                                        $id_produto = $_POST['id_produto'];
                                        //$id_funcionario = $_POST['id_funcionario'];
                                        // $data_ini = $_POST['data'];
                                        // $data_fim = $_POST['data2'];
                                        //$data1 = $_POST['data1'];
                                        if ($id_produto == '' || $id_produto == 'Selecione') {
                                            echo "<div class='alert alert-warning alert-dismissable'>
                                                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                        Você deve selecionar os campos acima.
                                                    </div>";
                                        } else {
                                            include_once "../modell/ProdutoFuncao.class.php";
                                            $lote = new ProdutoFuncao();
                                            $matriz = $lote->listaProdutoFuncaoCondicaoProduto($id_produto);
                                            if (empty($matriz)) {
                                                echo "<div class='alert alert-info alert-dismissable'>
                                                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                        Não possui nenhum registro armazenado.
                                                    </div>";
                                            } else {
                                                $tecido_metros = 0;
                                                $custo_metro = 0;
                                                $custoTotal = 0;
                                                while ($dados = $matriz->fetchObject()) {
                                                    $tempo_medio = $dados->tempo_medio;
                                                    $hora1 = explode(":", $tempo_medio);

                                                    //$teste = ($hora1[0] + ( $hora1[1] / 60 ) + ($hora1[2] / 3600 )) * $dados->custo_funcao;
                                                    //$teste1 = $hora1[0] * $dados->custo_funcao;
                                                    $teste2 = ($hora1[1] * $dados->custo_funcao);
                                                    $teste3 = ($hora1[2] * $dados->custo_funcao);

                                                    $custo = $teste2;
                                                    echo "<tr>                                                                                                       
                                                            <td>" . $dados->funcao . "</td>
                                                            <td>" . $dados->tempo_medio . "</td>
                                                            <td>" . 'R$ ' . number_format($dados->custo_funcao, 2, ',', '.') . "</td>
                                                            <td>" . 'R$ ' . number_format($custo, 2, ',', '.') . "</td>
                                                        </tr>";
                                                    $custoTotal += $custo;
                                                    $tecido_metros = $dados->tecido;
                                                    $descricao = $dados->descricao;
                                                    $custo_metro = $dados->custo_tecido;
                                                    $custoProduto = $tecido_metros * $custo_metro;
                                                }
                                                echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

                                                echo "<tr>                                                        
                                                        <th>Custo das funções</th>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b><span class='btn btn-info'>R$ " . number_format($custoTotal, 2, ',', '.') . " </span></b></td>
                                                     </tr>";

                                                echo "<tr>
                                                        <th>Custo do produto preço do tecido * metragem</th>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b><span class='btn btn-info'>R$ " . number_format($custoProduto, 2, ',', '.') . " </span></b></td>
                                                     </tr>";
                                                echo "<tr>
                                                        <th>Custo de confecção da $descricao</th>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b><span class='btn btn-success'>R$ " . number_format($custoProduto + $custoTotal, 2, ',', '.') . " </span></b></td>
                                                     </tr>";
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!--</div>-->
                    </div>
                    <?php
                    /* Captação de dados */
                    $buffer = ob_get_contents(); // Obtém os dados do buffer interno
                    $filename = "code.html"; // Nome do arquivo HTML
                    file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
                    ?>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12">
                            <!--<label for="legenda">*A soma das entradas é o (valor a vista + total de cartão + total de parcelas com cartão + total de parcelas).</label>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../ajax/desativar_funcionario.js"></script>
        <script src="../ajax/jquery.js"></script>

        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <!-- For the demo ad only -->   

        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>