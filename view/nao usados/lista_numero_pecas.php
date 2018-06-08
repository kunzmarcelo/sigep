<?php
session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
    include_once "../view/funcoes.php";
{
    include_once '../view/funcoes.php';
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
                                url: "alterarMaquina.php",
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
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Salários de funcionários</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel-heading">
                        <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                        <a href="cadastro_numero_pecas.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    </div>
                    <div class="form-group">
                        <form action="gerarPDF2.php" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-lg-6"> 
                                    <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post">                            
                            <div class="col-lg-6">                        
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
                                                $cod = $dados->id_funcionario;
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
                            Relatório de salário de funcionários
                        </div>
                        <div class="table-responsive">                    
                            <table class="table table-hover" id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th><img src='../bibliotecas/img/1486591262_id.png'></th>
                                        <th>Nome</th>
                                        <th>Data</th>
                                        <th>Data</th>
                                        <th>Peças</th>
                                        <th>Valor Produção</th>
                                        <th>Salário Base</th>
                                        <th>Percentual</th>
                                        <th>Média</th>
                                    </tr>
                                </thead>                                
                                <tbody>                       

                                    <?php
                                    if (isset($_POST['enviar'])) {
                                        //echo 'chegou';
                                        //$id_funcionario = $_POST['id_funcionario'];
                                        $id_funcionario = $_POST['id_funcionario'];
                                        include_once "../modell/NumeroPecas.class.php";
                                        $lote = new NumeroPecas();
                                        $matriz = $lote->litaTodosNumeroPecas($id_funcionario);

                                        $row_count = $matriz->rowCount();
                                        //echo $row_count . ' rows selected';
                                        $soma_total_pecas = 0;
                                        $soma_val_producao = 0;
                                        while ($dados = $matriz->fetchObject()) {
                                            $data1 = explode("-", $dados->data_ini);
                                            $data2 = explode("-", $dados->data_fim);
                                            $data1 = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
                                            $data2 = $data2[2] . '/' . $data2[1] . '/' . $data2[0];

                                            $percentual = (($dados->salario_producao / $dados->valor) - 1) * 100;
                                            $media = $dados->salario_producao / $dados->n_peca;
                                            if (empty($dados)) {
                                                echo "<div class='alert alert-info alert-dismissable'>
                                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                    Não possui nenhum registro armazenado.
                                                    </div>";
                                            } else {
                                                echo "<tr>
                                                    <td title='id'>" . $dados->id . "</td>
                                                    <td title='nome' class='editavel'>" . $dados->nome . "</td>                                                    
                                                    <td title='data_ini' class='editavel'>" . $data1 . "</td>                                                    
                                                    <td title='data_fim' class='editavel'>" . $data2 . "</td>                                                    
                                                    <td title='n_peca' class='editavel'>" . number_format($dados->n_peca, 0, '.', '.') . "</td>                                                    
                                                    <td title='salario_producao' class='editavel'>R$ " . number_format($dados->salario_producao, 2, ',', '.') . "</td>                                                    
                                                    <td title='valor' class='editavel'>R$ " . number_format($dados->valor, 2, ',', '.') . "</td>
                                                    <td title='percentual'><b>" . number_format($percentual, 2, ',', '.') . " %</b> </td>
                                                    <td title='média'><b>" . number_format($media, 3, ',', '.') . "</b> </td>
                                                </tr>";
                                            }
                                            $soma_total_pecas += $dados->n_peca;
                                            $soma_val_producao += $dados->salario_producao;
                                        }
                                       // echo $soma_val_producao / $row_count;
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <table class="table table-hover" id="tblEditavel">
                                <thead>
                                    <tr>                                        
                                        <th colspan="2">Soma Valor Produção</th>
                                        <th colspan="2">Média de salário mês</th>                          
                                    </tr>
                                </thead>                                
                                <tbody>
                                    <tr> 
                                        <td colspan="2">R$ <?=number_format($soma_val_producao, 2, ',', '.');?></td>
                                        <td colspan="2"> R$ <?=number_format($soma_val_producao /$row_count, 2, ',', '.') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-hover" id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th colspan="2">Soma número de peças</th>
                                        <th colspan="2">Média de peças mês</th>                                       
                                    </tr>
                                </thead>                                
                                <tbody>
                                    <tr>
                                        <td colspan="2"><?=$soma_total_pecas;?></td>
                                        <td colspan="2"><?=number_format($soma_total_pecas/$row_count, 2, ',', '.')?></td>
                                    </tr>
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
        <script src="../ajax/deletar_funcao.js"></script>
        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <!-- For the demo ad only -->   

        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>