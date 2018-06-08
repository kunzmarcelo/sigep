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
                                url: "alterarSalario.php",
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
                    <h1 class="page-header">Salários cadastrados</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel-heading">
                        <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                        <a href="cadastra_salario.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    </div>
                    <div class="form-group">
                        <form action="gerarPDF2.php" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-xs-6 col-sm-4">
                                    <input type="submit" value="Salvar em PDF" class="btn btn-default">
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
                            Registros cadastrados
                        </div>
                        <div class="table-responsive">                    
                            <table class="table table-hover" id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th><img src='../bibliotecas/img/1486591262_id.png'></th>
                                        <th>Data Cad.</th>
                                        <th>Salario</th>
                                        <th>N° de func.</th>
                                        <th>Dias</th>
                                        <th>Início</th>
                                        <th>Fim</th>
                                        <th>Tempo</th>
                                        <th>¹Total Mês</th>
                                        <th>²Total dia</th>
                                        <th>³Valor dia</th>
                                        <th>Minuto</th>
                                    </tr>
                                </thead>                                
                                <tbody>                       

                                    <?php
                                    include_once "../modell/ControleSalarial.class.php";
                                    $lote = new ControleSalarial();
                                    $matriz = $lote->listaSalario();
                                    $total = 0;
                                    $valor_dia = 0;
                                    $total_dia = 0;
                                    while ($dados = $matriz->fetchObject()) {

                                        $DATALOTE = explode("-", $dados->data_cadastro);
                                        $data = $DATALOTE[2] . '/' . $DATALOTE[1];
                                        //$data = $DATALOTE[2] . '/' . $DATALOTE[1] . '/' . $DATALOTE[0];


                                        $hora_final = explode(":", $dados->hora_fim);
                                        $final = (($hora_final[0] * 3600) + ($hora_final[1] * 60) + ($hora_final[2] ));
                                        $hora_inicio = explode(":", $dados->hora_inicio);
                                        $inicio = (($hora_inicio[0] * 3600) + ($hora_inicio[1] * 60) + ($hora_inicio[2] ));


                                        $total_segundos = $final - $inicio;
                                        $horas = floor($total_segundos / (60 * 60));
                                        $sobra_horas = ($total_segundos % (60 * 60));
                                        $minutos = floor($sobra_horas / 60);
                                        $sobra_minutos = ($sobra_horas % 60);
                                        $segundos = $sobra_minutos;
                                        $resultadoTempoEstimado = $horas . ':' . $minutos . ':0' . $segundos;


                                        $total = $dados->valor * $dados->n_funcionarios;
                                        $valor_dia = $total / $dados->dias_trabalhados;
                                        $total_dia = $valor_dia / $resultadoTempoEstimado;
                                        $custo_minuto = $total_dia / $resultadoTempoEstimado;

                                        if ($dados->status_salario == true) {
                                            echo "<tr>
                                                     <td title='id_salario'>" . $dados->id_salario . "</td>
                                                     <td title='data de cadastro'>" . $data . "</td>
                                                     <td title='valor' class='editavel'><b> R$ " . number_format($dados->valor, 2, ',', '.') . "</b></td>
                                                     <td title='n_funcionarios' class='editavel'>" . $dados->n_funcionarios . "</td>
                                                     <td title='dias_trabalhados' class='editavel'>" . $dados->dias_trabalhados . "</td>
                                                     <td title='hora_inicio' class='editavel'>" . $dados->hora_inicio . "</td>
                                                     <td title='hora_fim' class='editavel'>" . $dados->hora_fim . "</td>
                                                     <td title='Tempo Calendário'> " . $resultadoTempoEstimado . "</td>
                                                     <td title='Total mês'>R$ " . number_format($total, 2, ',', '.') . "</td>
                                                     <td title='Total dia'>R$ " . number_format($valor_dia, 2, ',', '.') . "</td>
                                                     <td title='Valor dia'>R$ " . number_format($total_dia, 2, ',', '.') . "</td>
                                                     <td title='Custo minuto'>R$ " . number_format($custo_minuto / $dados->n_funcionarios, 2, ',', '.') . "</td>
                                                     <td>
                                                        <span class='glyphicon glyphicon-eye-open' id='desativar' value='desativar'  onclick='desativar(" . $dados->id_salario . ");'></span>
                                                     </td>                                                    
                                                </tr>";
                                        } else {
                                            echo "<tr>
                                                    <td title='id_salario'>" . $dados->id_salario . "</td>
                                                    <td title='data de cadastro'>" . $data . "</td>
                                                    <td title='valor' class='editavel'><b> R$ " . number_format($dados->valor, 2, ',', '.') . "</b></td>
                                                    <td title='n_funcionarios' class='editavel'>" . $dados->n_funcionarios . "</td>
                                                    <td title='dias_trabalhados' class='editavel'>" . $dados->dias_trabalhados . "</td>
                                                    <td title='hora_inicio' class='editavel'>" . $dados->hora_inicio . "</td>
                                                    <td title='hora_fim' class='editavel'>" . $dados->hora_fim . "</td>
                                                    <td title='Tempo Calendário'> " . $resultadoTempoEstimado . "</td>
                                                    <td title='Total mês'>R$ " . number_format($total, 2, ',', '.') . "</td>
                                                    <td title='Total dia'>R$ " . number_format($valor_dia, 2, ',', '.') . "</td>
                                                    <td title='Valor dia'>R$ " . number_format($total_dia, 2, ',', '.') . "</td>
                                                    <td title='Custo minuto'>R$ " . number_format($custo_minuto / $dados->n_funcionarios, 2, ',', '.') . "</td>
                                                    <td>
                                                        <span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_salario . ");'></span>                                                    
                                                    </td>
                                                </tr>";
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
                            <label for="legenda">¹Total mês = Valor do salário * N° de funcionários.</label><br>
                            <label for="legenda">²Total dia = Total mês / Dias trabalhados.</label><br>
                            <label for="legenda">³Valor dia = Total dia / Tempo calendário.</label><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../ajax/salario.js"></script>


        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <!-- For the demo ad only -->   

        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>