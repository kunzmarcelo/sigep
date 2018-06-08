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



    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">                    

                    <h1 class="page-header"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span>Lista de funções agrupadas.</h1>

                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel-heading">
                        <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                        <a href="cadastra_funcao_funcionario.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post">                            
                            <div class="col-lg-6">                                                                           
                                <div class="form-group"> 
                                    <label for="data">Data inicial:</label>
                                    <input type="date" name="data" value="data" class="form-control" required="required" >
                                </div>
                                <div class="form-group">

                                    <label for="data2">Data final:</label>
                                    <input type="date" name="data2" value="data2" class="form-control" required="required" >
                                </div>
                                <div class="form-group">

                                    <label for="id_funcionario" class="tamanho-fonte">Funcionário:</label><small> (Campo Obrigatório)</small>
                                    <select name="id_funcionario" class="form-control" required="required" >                                       
                                        <?php
                                        echo "<option value='Selecione'>Selecione ...</option>";
                                        include_once '../modell/Funcionario.class.php';
                                        $fun = new Funcionario();
                                        $matriz = $fun->listaFuncionario();

                                        while ($dados = $matriz->fetchObject()) {
                                            if ($dados->ativo == true && $dados->departamento != 'Escritório') {
                                                $cod = $dados->id;
                                                $nome = $dados->nome;
                                                echo "<option value=" . $cod . ">" . $nome . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <button name="enviar" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Enviar</button>
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
                    <?php
                    if (isset($_POST['enviar'])) {
                        //echo 'chegou';
                        //$id_funcionario = $_POST['id_funcionario'];
                        $id_funcionario = $_POST['id_funcionario'];
                        $data_ini = $_POST['data'];
                        $data_fim = $_POST['data2'];
                        $data1 = explode("-", $data_ini);
                        $data2 = explode("-", $data_fim);
                        //$data1 = $_POST['data1'];
                        if ($id_funcionario == '') {
                            echo "<div class='alert alert-danger alert-dismissable'>
                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                    Você deve selecionar os campos acima.
                                </div>";
                        } else {
                            include_once "../modell/FuncaoFuncionario.class.php";
                            $lote = new FuncaoFuncionario();
                            $matriz = $lote->listaFuncaoAgrupado($data_ini, $data_fim, $id_funcionario);
                            if (empty($matriz)) {
                                echo "<div class='alert alert-warning alert-dismissable'>
                                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                        Não possui nenhum registro armazenado.
                                    </div>";
                            } else {
                                ?>
                                <div class="panel panel-primary">            
                                    <div class="panel-heading" style="text-align: center;">
                                        Relatório Agrupado por função. Gerado entre as datas <?= $data1[2] . '/' . $data1[1]; ?> até <?= $data2[2] . '/' . $data2[1] . '/' . $data2[0] ?>
                                    </div>
                                    <div class="table-responsive">                    
                                        <table class="table table-hover" id="tblEditavel">
                                            <thead>
                                                <tr>
                                                    <!--<th title="produto"><img src='../bibliotecas/img/1486591262_id.png'></th>-->
                                                    <!--<th>#</th>-->                                       
                                                    <th>Função</th>                                        
                                                    <!--<th>N° peças</th>-->
                                                    <th>Quantidade</th>
                                                   <!-- <th>Valor da Função</th>-->

                                                                            <!--<th title="Excluir item"><img src='../bibliotecas/img/1486594122_delete.png'></th>-->
                                                </tr>
                                            </thead>                                            
                                            <tbody>
                                                <?php
                                                $pecas_fabricadas = 0;
                                                $soma = 0;
                                                $testeConta = 0;

                                                while ($dados = $matriz->fetchObject()) {

                                                    $entrada = '07:30:00';
                                                    $saida = '17:55:00';
                                                    $intervalo = '02:00:00';

                                                    $hora1 = explode(":", $entrada);
                                                    $hora2 = explode(":", $saida);
                                                    $hora3 = explode(":", $intervalo);

                                                    $acumulador1 = ($hora1[0] * 3600) + ($hora1[1] * 60) + $hora1[2];
                                                    $acumulador2 = ($hora2[0] * 3600) + ($hora2[1] * 60) + $hora2[2];
                                                    $acumulador3 = ($hora3[0] * 3600) + ($hora3[1] * 60) + $hora3[2];

                                                    $resultado = ($acumulador2 - $acumulador1) - $acumulador3;

                                                    $hora_ponto = floor($resultado / 3600);

                                                    $resultado = $resultado - ($hora_ponto * 3600);

                                                    $min_ponto = floor($resultado / 60);

                                                    $resultado = $resultado - ($min_ponto * 60);

                                                    $secs_ponto = $resultado;
//Grava na variável resultado final
                                                    $tempo = $hora_ponto . ":" . $min_ponto . ":" . $secs_ponto;
                                                    //echo $tempo;


                                                    $subtotal = $dados->SOMA / $tempo;
                                                    //print_r('sub total' . $subtotal);
                                                    $valor_producao = (($dados->custo_funcao * 60) * $subtotal);
                                                    //echo ' valor producao' . $valor_producao;
                                                    $soma += $valor_producao;

                                                    echo "<tr>                                                       
                                                            <td>" . $dados->funcao . "</td>
                                                            <td title='Total de peças'><b>" . $dados->SOMA . "</b></td>                                                                
                                                            <td title='Total de peças'><b>R$ " . number_format($valor_producao, 2, ',', '') . "</b></td>                                                                
                                                                                                                           
                                                        </tr>";
                                                    $pecas_fabricadas += $dados->SOMA;
                                                    $nome = $dados->nome;
                                                }

                                               
                                            }
                                        }
                                        ?>
                                    </tbody>                                
                                </table>                            
                            </div>                                    
                            <!--</div>-->
                        </div>
                        <div class="panel panel-primary">                                
                            <table class="table table-hover" >                            
                                <tbody>                                   
                                    <tr>
                                        <th style="text-align: right;">Funcionário:</th>
                                        <td><?= $nome; ?></td>
                                    </tr>
                                     <tr>
                                        <th style="text-align: right;">Peças Fabricadas:</th>
                                        <td><?= $pecas_fabricadas; ?></td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: right;">Total:</th>
                                        <td>R$ <?= number_format($soma, 2, ',', ''); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <?php
                    }
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
        <script src="../ajax/deletar_funcao_funcionario.js"></script>
        <script src="../ajax/jquery.js"></script>
        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <!-- For the demo ad only -->
        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>
