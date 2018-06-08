<?php
session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
    include_once "../view/funcoes.php"; {
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
                                url: "alterarMeta.php",
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
                    <h1 class="page-header">Produção Cadastradas</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel-heading">
                        <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                        <a href="cadastra_meta_diaria.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post">                            
                            <div class="col-lg-6">                                                                           
                                <div class="form-group"> 
                                    <label for="data">Data inicial:</label>
                                    <input type="date" name="data" value="data" class="form-control" required="required" >
                                </div>
                                <!--                                <div class="form-group">
                                
                                                                    <label for="data2">Data final:</label>
                                                                    <input type="date" name="data2" value="data2" class="form-control" required="required" >
                                                                </div>-->

                                <div class="form-group">
                                    <button name="enviar" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Enviar</button>
                                    <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group">
                        <form action="gerarPDF.php" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-xs-6 col-sm-4">
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
        <div class="panel panel-primary">            
            <div class="panel-heading" style="text-align: center;">
                Lista Produção Cadastradas
            </div>
            <div class="table-responsive">                    
                <table class="table table-hover" id="tblEditavel">
                    <thead>
                        <tr>
                            <th><img src='../bibliotecas/img/1486591262_id.png'></th>
                            <th>Data</th>
                            <th>Produto</th>
                            <th>Quant.</th>
                            <th>Unitário</th>
                            <th>Estimativa</th>
                            <th>Desconto</th>
                            <th>Total</th>
                            <th>Pessoal</th>
                            <th>Produzidas</th>
                            <th>Tempo levado</th>
                            <th>Diferença</th>
                            <th>Faltam</th>
                            <!--<th>Status</th>-->
                        </tr>
                    </thead>                                
                    <tbody>                       

                        <?php
                        if (isset($_POST['enviar'])) {
                            $data_ini = $_POST['data'];
                            $data1 = explode("-", $data_ini);
                            $data1 = $data1[2] . '/' . $data1[1] . '/' . $data1[0];

                            include_once "../modell/MetaDiaria.class.php";
                            $lote = new MetaDiaria();
                            $matriz = $lote->listaMeta($data_ini);

                            $data_prod = 0;
                            $quantidade = 0;
                            $quantidade_produzidas = 0;
                            $total_pecas = 0;
                            $tempo_levado = 0;
                            $diferença_tempo = 0;
                            $tempo_determinado = 0;
                            $total_pecas_faltantes = 0;

                            while ($dados = $matriz->fetchObject()) {
                                $data1 = explode("-", $dados->data_producao);
                                // $data = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
                                $data = $data1[2] . '/' . $data1[1];

                                $hora_ini1 = explode(":", $dados->tempo_producao);
                                $hora_fim1 = explode(":", $dados->tempo_estimado);
                                $tempo1 = (($hora_ini1[0] * 3600) + ($hora_ini1[1] * 60) + ($hora_ini1[2] ));
                                $tempo2 = (($hora_fim1[0] * 3600) + ($hora_fim1[1] * 60) + ($hora_fim1[2]));
                                $teste = $tempo1 - $tempo2;
                                $total_segundos = $teste;
                                $horas = floor($total_segundos / (60 * 60));
                                $sobra_horas = ($total_segundos % (60 * 60));
                                $minutos = floor($sobra_horas / 60);
                                $sobra_minutos = ($sobra_horas % 60);
                                $segundos = $sobra_minutos;
                                $resultado_tempo = $horas . ':' . $minutos . ':' . $segundos;



                                $total_com_desconto = $dados->n_pecas - (($dados->n_pecas * $dados->desconto) / 100);
                                $teste_procentagem = (($dados->n_pecas / $dados->n_pecas_feitas) - 1) * 100;
                                $pecas_faltantes = $total_com_desconto - $dados->n_pecas_feitas;


                                if ($resultado_tempo > "0:0:0") {
                                    $resutaldo_tempo = "<td class='danger'>" . $resultado_tempo . "</td>";
                                } else {
                                    $resutaldo_tempo = "<td>" . $resultado_tempo . "</td>";
                                }

                                if (round($pecas_faltantes) > 0) {
                                    $pecas_faltantes = "<td class='danger'>" . round($pecas_faltantes) . " peças</td>";
                                } else {
                                    $pecas_faltantes = "<td>" . round($pecas_faltantes) . " peças</td>";
                                }

                                if ($dados->status == true) {

                                    echo "<tr>
                                           <td title='id'>" . $dados->id_meta . "</td>
                                           <td class='editavel' title='data_producao'>" . $data . "</td>
                                           <td class='editavel' title='id_produto'>" . $dados->descricao . "</td>
                                           <td class='editavel success' title='n_pecas'>" . $dados->n_pecas . " peças</td>
                                           <td class='editavel success' title='tempo_unitario'>" . $dados->tempo_unitario . "</td>
                                           <td class='editavel success' title='tempo_estimado'>" . $dados->tempo_estimado . "</td>
                                           <td class='editavel success' title='desconto'>" . $dados->desconto . " %</td>
                                           <td title='total com desconto'>" . round($total_com_desconto) . " peças</td>
                                           <td class='editavel' title='n_pessoas'>" . $dados->n_pessoas . "</td>
                                           <td class='editavel warning' title='n_pecas_feitas'>" . $dados->n_pecas_feitas . " peças</td>
                                           <td class='editavel warning' title='tempo_producao'>" . $dados->tempo_producao . "</td>
                                                $resutaldo_tempo
                                                $pecas_faltantes
                                            <td>     
                                             <span class='glyphicon glyphicon-eye-open' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_meta . ");'></span> 													
                                           </td>
                                        </tr>";
                                } else {
                                    echo "<tr>
                                        <td title='id'>" . $dados->id_meta . "</td>
                                        <td class='editavel' title='data_producao'>" . $data . "</td>
                                        <td class='editavel' title='id_produto'>" . $dados->descricao . "</td>
                                        <td class='editavel success' title='n_pecas'>" . $dados->n_pecas . " peças</td>
                                        <td class='editavel success' title='tempo_unitario'>" . $dados->tempo_unitario . "</td>
                                        <td class='editavel success' title='tempo_estimado'>" . $dados->tempo_estimado . "</td>
                                        <td class='editavel success' title='desconto'>" . $dados->desconto . " %</td>
                                        <td title='total com desconto'>" . round($total_com_desconto) . " peças</td>
                                        <td class='editavel' title='n_pessoas'>" . $dados->n_pessoas . "</td>
                                        <td class='editavel warning' title='n_pecas_feitas'>" . $dados->n_pecas_feitas . " peças</td>
                                        <td class='editavel warning' title='tempo_producao'>" . $dados->tempo_producao . "</td>
                                        $resutaldo_tempo
                                        $pecas_faltantes
                                        <td>     
                                            <span class='glyphicon glyphicon-eye-close' id='finalizar' value='finalizar'  onclick='finalizar(" . $dados->id_meta . ");'></span> 													
                                        </td>
                                    </tr>";
                                }
                                if ($dados->status == true) {
                                    $data_prod = $data;
                                    $quantidade += $dados->n_pecas;
                                    $quantidade_produzidas += $dados->n_pecas_feitas;
                                    $tempo_determinado += $tempo2;
                                    $tempo_levado += $tempo1;
                                    $diferença_tempo += $teste;
                                    $total_pecas += round($total_com_desconto);
                                    $total_pecas_faltantes += $pecas_faltantes;
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
                
            </div>
            
            <!--</div>-->
        </div>
        <hr>
        <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Data</th>
                            <th>Produtos</th>
                            <th>Σ Quant.</th>
                            <th>Tempo</th>
                            <th>Σ Estimativa</th>
                            <th>Desconto</th>
                            <th>Σ Total Peças</th>
<!--                            <th>Pessoas</th>-->
                            <th>Σ Produzidas</th>
                            <th>Σ Tempo Levado</th>
                            <th>Σ Diferença</th>
                            <th>Σ Faltam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td><?=$data_prod?></td>
                            <td></td>
                            <td><?= $quantidade; ?></td>
                            <td></td>
                            <td>
                                <?php
                                $total_segundos = $tempo_determinado;
                                $horas = floor($total_segundos / (60 * 60));
                                $sobra_horas = ($total_segundos % (60 * 60));
                                $minutos = floor($sobra_horas / 60);
                                $sobra_minutos = ($sobra_horas % 60);
                                $segundos = $sobra_minutos;
                                echo $resultado_tempo = $horas . ':' . $minutos . ':' . $segundos;
                                ?>
                            </td>
                            <td></td>
                            <td><?= $total_pecas; ?></td>
                            <!--<td></td>-->
                            <td><?= $quantidade_produzidas; ?></td>
                            <td>
                                <?php
                                $total_segundos = $tempo_levado;
                                $horas = floor($total_segundos / (60 * 60));
                                $sobra_horas = ($total_segundos % (60 * 60));
                                $minutos = floor($sobra_horas / 60);
                                $sobra_minutos = ($sobra_horas % 60);
                                $segundos = $sobra_minutos;
                                echo $resultado_tempo = $horas . ':' . $minutos . ':' . $segundos;
                                ?>
                            </td>
                            <td>
                                <?php
                                $total_segundos = $diferença_tempo;
                                $horas = floor($total_segundos / (60 * 60));
                                $sobra_horas = ($total_segundos % (60 * 60));
                                $minutos = floor($sobra_horas / 60);
                                $sobra_minutos = ($sobra_horas % 60);
                                $segundos = $sobra_minutos;
                                echo $resultado_tempo = $horas . ':' . $minutos . ':' . $segundos;
                                ?>
                            </td>
                            <td><?= $total_pecas - $quantidade_produzidas; ?></td>
                        </tr>
                    </tbody>
                </table>
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

        <!--</div>-->
        <script src="../ajax/meta_diaria.js"></script>


        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <!-- For the demo ad only -->   

        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>