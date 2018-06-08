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



                <div class="row">
                    <div class="col-xs-6">
                        <form class="form-horizontal" method="post">                            
                            <div class="form-group">                                
                                <label for="data">Data inicial:</label>
                                <input type="date" name="data" value="" class="form-control" required="required" >
                            </div>
                            <div class="form-group">
                                <label for="id_celula" class="tamanho-fonte">Pessoas por célula de trabalho:</label><small> (Campo Obrigatório)</small>
                                <select name="id_celula" class="form-control" required="required" onchange="this.form.submit()">                                       
                                    <?php
                                    echo "<option value=''>Selecione ...</option>";
                                    include_once '../modell/CelulaTrabalho.class.php';
                                    $lote = new CelulaTrabalho();
                                    $matriz = $lote->listaCelula();

                                    while ($dados = $matriz->fetchObject()) {
                                        if ($dados->status_celula == TRUE) {
                                            $cod = $dados->id_celula;
                                            $pessoas_celula = $dados->pessoas_celula;
                                            $funcionarios = $dados->funcionarios;
                                            echo "<option value=" . $cod . ">" . $pessoas_celula . ' - ' . $funcionarios . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>                           
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                                <span class='glyphicon glyphicon-plus'></span> Adicionar
                            </button>


                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#anotacoes">
                                <span class='glyphicon glyphicon-list-alt'></span> Anotações
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <form action="gerarPDF.php" class="form-horizontal">

                                <button type="submit" class="btn btn-default">Exportar para PDF</button>
                                <!--<input type="submit" value="Salvar em PDF" class="btn btn-default">-->

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
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="text-align: center">
                            Listagem
                        </div>
                        <table width="100%" class="table table-striped table-bordered table-hover table-condensed" id="tblEditavel">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Data</th>
                                    <th>Célula</th>
                                    <th>Abs</th>
                                    <th>Motivo</th>
                                    <th>Produto</th>
                                    <th>Obs.</th>
                                    <th>Quant.</th>
                                    <th>Feitas</th>
                                    <th>Unit.</th>
                                    <th>¹Reajuste</th>
                                    <th>²Estimado</th>
                                    <!--<th>²Concluído</th>-->
                                    <th>³Faltam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>                       

                                <?php
                                $data_ini = \filter_input(INPUT_POST, 'data');
                                $id_celula = \filter_input(INPUT_POST, 'id_celula');
                                if (empty($id_celula) || empty($data_ini)) {
                                    echo" <div class='alert alert-warning' role='alert'>
                                               <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Selecione todos campos acima.</h4>
                                            </div>";
                                } else {

//                            if (isset($_POST['enviar'])) {
//                                

                                    $data1 = explode("-", $data_ini);
                                    include_once "../modell/DetalheCelulaProduto.class.php";
                                    $lote = new DetalheCelulaProduto();
                                    $matriz = $lote->listaCelulaProdutoTodosCelula($data_ini, $id_celula);
                                    $diferenca = 0;
                                    $somaTempoHora = 0;
                                    $somaTempoMinu = 0;
                                    $somaTempoSegu = 0;
                                    $somaPeca = 0;
                                    $somaPecaPronta = 0;

                                    while ($dados = $matriz->fetchObject()) {
                                        if ($dados->status == 0) {
                                            $status = "<small><span class='label label-danger'>Parado</span></small>";
                                        }
                                        if ($dados->status == 1) {
                                            $status = "<small><span class='label label-warning'>Em Produção</span></small>";
                                        }
                                        if ($dados->status == 2) {
                                            $status = "<small><span class='label label-success'>Finalizado</span></small>";
                                        }
                                        if ($dados->status == 3) {
                                            $status = "<small><span class='label label-info'>Proximo Dia</span></small>";
                                        }
                                        if ($dados->status > 3) {
                                            $status = "<small>ERRO</small>";
                                        }

                                        if ($dados->OBSERVACAO == '') {
                                            $OBSERVACAO = '-';
                                        } else {
                                            $OBSERVACAO = $dados->OBSERVACAO;
                                        }
                                        if ($dados->motivo_falta == '') {
                                            $motivo_falta = '-';
                                        } else {
                                            $motivo_falta = $dados->motivo_falta;
                                        }
                                        if ($dados->falta == 0) {
                                            $falta = '-';
                                        } else {
                                            $falta = $dados->falta;
                                        }


                                        if ($dados->falta > 0) {
                                            $data1 = explode("-", $dados->DATACELULA);
                                            $diferenca = ((( $dados->pecas_finalizadas * 100)) / $dados->pecas_determinadas); //porcentagem

                                            $tempoUni = explode(":", $dados->tempo_unitario);
                                            $tempo3 = (($tempoUni[0] * 3600) + ($tempoUni[1] * 60) + ($tempoUni[2] ));

                                            $tempoTotal = ($dados->pecas_determinadas * $tempo3) / ($dados->pessoas_celula - $dados->falta);
//
//                                        if ($dados->DATACELULA <= '2017-11-08') {
//                                            $total_segundos1 = $tempoTotal;
//                                            $porcentagem = $dados->margem_erro; //0%
//                                        } else {
//                                          echo  $porcentagem =   $dados->margem_erro; //>0%
//                                            $total_segundos1 = $tempoTotal + (($porcentagem / 100) * $tempoTotal);
//                                        }
                                            $porcentagem = $dados->margem_erro; //>0%
                                            $total_segundos1 = $tempoTotal + (($porcentagem / 100) * $tempoTotal);
                                            $horas1 = floor($total_segundos1 / (60 * 60));
                                            $sobra_horas1 = ($total_segundos1 % (60 * 60));
                                            $minutos1 = floor($sobra_horas1 / 60);
                                            $sobra_minutos1 = ($sobra_horas1 % 60);
                                            $segundos1 = $sobra_minutos1;
                                            $resultadoTempoEstimado = $horas1 . ':' . $minutos1 . ':' . $segundos1;
                                        } else {
                                            if ($dados->pecas_determinadas > 0) {
                                                $data1 = explode("-", $dados->DATACELULA);
                                                $diferenca = ((( $dados->pecas_finalizadas * 100)) / $dados->pecas_determinadas); //porcentagem
                                            } else {
                                                $data1 = explode("-", $dados->DATACELULA);
                                                $diferenca = ((( $dados->pecas_finalizadas * 100)) / ($dados->pecas_determinadas + 1)); //porcentagem
                                            }

                                            $tempoUni = explode(":", $dados->tempo_unitario);
                                            $tempo3 = (($tempoUni[0] * 3600) + ($tempoUni[1] * 60) + ($tempoUni[2] ));
                                            $tempoTotal = ($dados->pecas_determinadas * $tempo3) / $dados->pessoas_celula;

//                                        if ($dados->DATACELULA <= '2017-11-08') {
//                                            $total_segundos1 = $tempoTotal;
//                                            $porcentagem = $dados->margem_erro; //0%
//                                        } else {
//                                           
//                                           echo $total_segundos1 = $tempoTotal + (($porcentagem / 100) * $tempoTotal).'<br>';
//                                        }
                                            $porcentagem = $dados->margem_erro; //>0%

                                            $total_segundos1 = $tempoTotal + (($porcentagem / 100) * $tempoTotal);
                                            $horas1 = floor($total_segundos1 / (60 * 60));
                                            $sobra_horas1 = ($total_segundos1 % (60 * 60));
                                            $minutos1 = floor($sobra_horas1 / 60);
                                            $sobra_minutos1 = ($sobra_horas1 % 60);
                                            $segundos1 = $sobra_minutos1;
                                            $resultadoTempoEstimado = $horas1 . ':' . $minutos1 . ':' . $segundos1;
                                        }




                                        $faltam = $dados->pecas_determinadas - $dados->pecas_finalizadas;
                                        if ($faltam == 0) {
                                            $faltam2 = number_format($faltam, 2, '.', '.');
                                        } else {
                                            $faltam2 = "<b>" . number_format($faltam, 2, '.', '.') . " </b>";
                                        }

                                        if ($minutos1 <= 9) {
                                            $tempo_estimado_resultado = '0' . $horas1 . ':0' . $minutos1 . ':' . $segundos1;
                                        } else {
                                            $tempo_estimado_resultado = '0' . $horas1 . ':' . $minutos1 . ':' . $segundos1;
                                        }

                                        echo "<tr>
                                             <td title='id'>" . $dados->id . "</td>
                                             <td title='data' class='editavel'>" . $data1[2] . '/' . $data1[1] . "</td>                                                   
                                             <td title='funcionarios'>" . $dados->funcionarios . "</td>                                                   
                                             <td title='falta' class='editavel'>" . $falta . "</td>                                                 
                                             <td title='motivo_falta' class='editavel'>" . $motivo_falta . "</td>                                                 
                                             <td title='id_produto' class='editavel'>" . $dados->descricao . "</td>
                                             <td title='obs' class='editavel'>" . $OBSERVACAO . " </td>
                                             <td title='pecas_determinadas' class='editavel'>" . number_format($dados->pecas_determinadas, 2, '.', '.') . "</td>
                                             <td title='pecas_finalizadas' class='editavel'>" . number_format($dados->pecas_finalizadas, 2, '.', '.') . "</td>
                                             <td title='tempo_unitario' class='editavel'>" . $dados->tempo_unitario . "</td>
                                             <td title='margem_erro' class='editavel'><b>" . number_format($porcentagem, 2, '.', '.') . " %</b></td>
                                             <td title='(número de peças * tempo Unitário) / número de pessoas'>" . $tempo_estimado_resultado . " </td>                                             
                                             <td title='Peças que faltam'>" . $faltam2 . "</td>
                                             <td title='status' class='editavel'>" . $status . " </td>
                                              </tr>";
                                        // <td title='percentual de peças concluidas'>" . number_format($diferenca, 2, '.', '.') . " %</td>
                                        $somaTempoHora += $horas1;
                                        $somaTempoMinu += $minutos1;
                                        $somaTempoSegu += $segundos1;
                                        $somaPeca += $dados->pecas_determinadas;
                                        $somaPecaPronta += $dados->pecas_finalizadas;
                                        $data = $dados->DATACELULA;
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
                                    <th>Tempo Calen.</th>
                                    <th>Tempo líquido</th>                                
                                    <th>Σ Quant.</th>                               
                                    <th>Σ Prontas</th>
                                    <th>Σ Faltam</th>
                                    <th>Concluído</th>                               
                                    <th>Tempo de produção</th>
                                    <th>≠ Tempo Líquido - Tempo de produção</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php
                                        $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
                                        $data = date('Y-m-d');

                                        // Varivel que recebe o dia da semana (0 = Domingo, 1 = Segunda ...)
                                        $diasemana_numero = date('w', strtotime($data));

                                        // Exibe o dia da semana com o Array
                                        $diasemana[$diasemana_numero];


                                        include_once '../modell/ConfigCelula.class.php';

                                        $newConfig = new ConfigCelula();
                                        $matriz2 = $newConfig->listaUmConfig($diasemana[$diasemana_numero]);
                                        while ($dados = $matriz2->fetchObject()) {
                                            if ($dados->status == true) {

                                                $hora_ini = $dados->hora_ini;
                                                $hora_fim = $dados->hora_fim;
                                                $hora_des = $dados->hora_des;
                                                $data_ini = $dados->data_ini;
                                                $data_fim = $dados->data_fim;
                                                $dias = $dados->dias;
                                            }
                                        }


                                        $data_atual = date('Y-m-d');
                                        //echo $data_atual;
                                        //echo $data_atual;
                                        //echo $data_fim;
                                        if ($data_atual > $data_fim) {
                                            echo" <div class='alert alert-warning' role='alert'>
                                               <h5> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Confira a data na configuraçao.</h5>
                                            </div>";
                                        } else {
                                            $hora_ini1 = explode(":", $hora_ini);
                                            $hora_fim1 = explode(":", $hora_fim);
                                            $hora_int1 = explode(":", $hora_des);

                                            $tempo1 = (($hora_ini1[0] * 3600) + ($hora_ini1[1] * 60) + ($hora_ini1[2]));
                                            $tempo2 = (($hora_fim1[0] * 3600) + ($hora_fim1[1] * 60) + ($hora_fim1[2]));
                                            $tempo3 = (($hora_int1[0] * 3600) + ($hora_int1[1] * 60) + ($hora_int1[2]));

                                            //$resultado_tempo = ($tempo2 - $tempo1) - $tempo3;
                                            $resultado_tempo = ($tempo2 - $tempo1);
                                            $total_segundos = $resultado_tempo;

                                            $horas = floor($total_segundos / (60 * 60));
                                            $sobra_horas = ($total_segundos % (60 * 60));
                                            $minutos = floor($sobra_horas / 60);
                                            $sobra_minutos = ($sobra_horas % 60);
                                            $segundos = $sobra_minutos;
                                            echo "<strike>$horas:$minutos:0$segundos</strike>";
                                            ?>

                                        </td>                                    
                                        <td> 
                                            <?php
                                            $hora_ini1 = explode(":", $hora_ini);
                                            $hora_fim1 = explode(":", $hora_fim);
                                            $hora_int1 = explode(":", $hora_des);

                                            $tempo1 = (($hora_ini1[0] * 3600) + ($hora_ini1[1] * 60) + ($hora_ini1[2]));
                                            $tempo2 = (($hora_fim1[0] * 3600) + ($hora_fim1[1] * 60) + ($hora_fim1[2]));
                                            $tempo3 = (($hora_int1[0] * 3600) + ($hora_int1[1] * 60) + ($hora_int1[2]));

                                            $total_segundos = ($tempo2 - $tempo1) - $tempo3;
                                            //$total_segundos = ($resultado_tempo - (($resultado_tempo)));

                                            $horas = floor($total_segundos / (60 * 60));
                                            $sobra_horas = ($total_segundos % (60 * 60));
                                            $minutos = floor($sobra_horas / 60);
                                            $sobra_minutos = ($sobra_horas % 60);
                                            $segundos = $sobra_minutos;
                                            echo "<b>0$horas:$minutos:0$segundos</b>"
                                            ?>

                                        </td>
                                        <td><?= $somaPeca ?> peças</td>
                                        <td><?= $somaPecaPronta ?> peças</td>
                                        <td><b><?= $somaPeca - $somaPecaPronta ?> peças</b></td>

                                        <td>
                                            <?php
                                            if (empty($somaPeca)) {
                                                $somaPeca = 1;
                                            } else {
                                                echo number_format($diferenca = ((($somaPecaPronta * 100)) / $somaPeca), 2, '.', '.');
                                            }//porcentagem 
                                            ?>
                                            %</td>
                                        <td> 
                                            <?php
                                            $total_segundos1 = (($somaTempoHora * 3600) + ($somaTempoMinu * 60) + ($somaTempoSegu));
                                            $horas1 = floor($total_segundos1 / (60 * 60));
                                            $sobra_horas1 = ($total_segundos1 % (60 * 60));
                                            $minutos1 = floor($sobra_horas1 / 60);
                                            $sobra_minutos1 = ($sobra_horas1 % 60);
                                            $segundos1 = $sobra_minutos1;
                                            echo "<span><b> 0$horas1:$minutos1:$segundos1 <b></span>";
                                            ?>

                                        </td>
                                        <td> 
                                            <?php
                                            $total_segundos1 = ((($tempo2 - $tempo1) - $tempo3) - (($somaTempoHora * 3600) + ($somaTempoMinu * 60) + ($somaTempoSegu)));
                                            $horas1 = floor($total_segundos1 / (60 * 60));
                                            $sobra_horas1 = ($total_segundos1 % (60 * 60));
                                            $minutos1 = floor($sobra_horas1 / 60);
                                            $sobra_minutos1 = ($sobra_horas1 % 60);
                                            $segundos1 = $sobra_minutos1;
                                            if ($horas1 == '-1') {
                                                echo "<label> 00 hora (s), 00 minuto (s) e 00 segundo (s)</label>";
                                            } else {
                                                echo "<label> $horas1 hora (s), $minutos1 minuto (s) e $segundos1 segundo (s)</label>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>                     
                            </table>

                            <hr>
                            <hr>
                            <div class="panel-heading" style="text-align: center;">
                                Anotações realizadas
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descrição</th>                                
                                    </tr>
                                </thead>
                                <tbody>                            
                                    <?php
                                    $data_ini = \filter_input(INPUT_POST, 'data');
                                    $id_celula = \filter_input(INPUT_POST, 'id_celula');

                                    include_once '../modell/Anotacao.class.php';
                                    $newAnotacao = new Anotacao();
                                    $matriz3 = $newAnotacao->listaAnotacao($id_celula, $data_ini);

                                    while ($dados = $matriz3->fetchObject()) {
                                        $data1 = explode("-", $dados->data);
                                        if ($dados->status == true) {
                                            echo "<tr class='warning'>
                                            <td title='id'>" . $dados->id_anotacao . "</td>                                           
                                            <td title='id' >" . $dados->descricao . "</td>
                                            
                                        </tr>";
                                        }
                                    }
                                    ?>


                                </tbody>                     
                            </table>

                            <hr>
                            <!--</div>-->
                            <!--</div>-->
                            <!--                    <div class="form-group">
                                                    <div class="col-xs-12 col-sm-12">
                                                        <label for="legenda">¹Tempo: Hora final - Hora inicial.</label><br>
                                                        <label for="legenda">¹ Reajuste: tempo estimado de produção em sofre um margem_erro de <?= $porcentagem ?>% de aumento a partir da data 08/11/2017 .</label><br>
                                                        <label for="legenda">² Tempo Estimado: Tempo estimado para a produção das peças = (número de peças * tempo Unitário) / número de pessoas</label><br>
                                                        <label for="legenda">² Concluído: Percentual de peças concluidas.</label><br>
                                                        <label for="legenda">³ Faltam: quantidade de peças determinada - numero de peças feitas</label><br>
                                                        <label for="legenda">Status: Informe: 0 - Parado; 1 - Em Produção; 2 - Finalizado; 3 - Proximo Dia;</label><br>
                                                    </div>
                                                </div>-->
                            <?php
                        }
                    }
                    /* Captação de dados */
                    $buffer = ob_get_contents(); // Obtém os dados do buffer interno
                    $filename = "code.html"; // Nome do arquivo HTML
                    file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
                    ?>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Cadastro de Produção</h4>
                                </div>
                                <div class="modal-body">
                                    <form method="post" class="form-horizontal">                                       
                                        <div class="panel-body">                                                
                                            <div class="form-group">
                                                <label for="id_celula" class="tamanho-fonte">Pessoas por célula de trabalho:</label><small> (Campo Obrigatório)</small>
                                                <select name="id_celula" class="form-control" required="required" >                                       
                                                    <?php
                                                    echo "<option value=''>Selecione ...</option>";
                                                    include_once '../modell/CelulaTrabalho.class.php';
                                                    $lote = new CelulaTrabalho();
                                                    $matriz = $lote->listaCelula();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status_celula == TRUE) {
                                                            $id_celula = $dados->id_celula;
                                                            $pessoas_celula = $dados->pessoas_celula;
                                                            $funcionarios = $dados->funcionarios;
                                                            echo "<option value=" . $id_celula . ">" . $pessoas_celula . ' - ' . $funcionarios . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>
                                            <div class="form-group">
                                                <label for="id_produto" class="tamanho-fonte">Produto</label><small> (Campo Obrigatório)</small>
                                                <select name="id_produto" class="form-control" required="required" >                                       
                                                    <?php
                                                    echo "<option value=''>Selecione ...</option>";
                                                    include_once '../modell/Produto.class.php';
                                                    $prod = new Produto();
                                                    $matriz = $prod->listaProduto();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status == true) {
                                                            $cod = $dados->id_produto;
                                                            $descricao = $dados->descricao;
                                                            echo "<option value=" . $cod . ">" . $descricao . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>
                                            <div class="form-group">
                                                <label for="obs">Observação:</label>
                                                <input type="text" id="obs" name="obs" class="form-control" placeholder="Ex.: MC: manga curta; ML: manga Longa"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="data">Data da produção:</label>
                                                <input type="date" id="data" name="data" class="form-control" required="required" />
                                            </div>
                                            <div class="form-group">
                                                <label for="n_pecas">Quantidade de serem produzidas:</label>
                                                <input type="number" id="n_pecas" name="n_pecas" class="form-control" placeholder="numero de peças" required="required" />
                                            </div>
                                            <div class="form-group">
                                                <label for="tempo_unitario">Tempo Unitário do Produto:</label>
                                                <input type="time" id="tempo_unitario" name="tempo_unitario" class="form-control" step='1' min="00:00:00" max="24:00:00" />
                                            </div>
                                            <div class="form-group">
                                                <label for="reajuste">Reajuste em porcentagem para mais:</label>
                                                <select name="reajuste" class="form-control" required="required">                                       
                                                    <?php
                                                    $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
                                                    $data = date('Y-m-d');

                                                    // Varivel que recebe o dia da semana (0 = Domingo, 1 = Segunda ...)
                                                    $diasemana_numero = date('w', strtotime($data));

                                                    // Exibe o dia da semana com o Array
                                                    // echo $diasemana[$diasemana_numero];
//                                            echo "<option value=''><b>Selecione ...</b></option>";
                                                    include_once '../modell/ConfigCelula.class.php';
                                                    $fun = new ConfigCelula();
                                                    $matriz = $fun->listaUmConfig($diasemana[$diasemana_numero]);


                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status == true) {
                                                            $desconto = $dados->desconto;
                                                            $descricao = $dados->dias;
                                                            echo "<option value='$desconto'>" . $descricao . ' ' . $desconto . '% ' . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select> 

        <!--                                        <select name="reajuste" class="form-control" required="required">
                                                    <option value="05">05</option>
                                                    <option value="10">10</option>
                                                    <option value="15">15</option>
                                                    <option value="20">20</option>
                                                    <option value="25">25</option>
                                                </select>-->
                                            </div>
                                            <div class="form-group">
                                                <label for="n_feitas">Quantidade de peças produzidas:</label>
                                                <input type="number" id="n_feitas" name="n_feitas" class="form-control" placeholder="numero de peças"  />
                                            </div>
                                            <div class="form-group">                                            
                                                <label for="abstencao" class="tamanho-fonte">Funcionário que faltou:</label><small> (Campo Obrigatório)</small>
                                                <select name="abstencao" class="form-control" required="required">                                       
                                                    <?php
                                                    echo "<option value='0'><b>Selecione ...</b></option>";
                                                    include_once '../modell/Funcionario.class.php';
                                                    $fun = new Funcionario();
                                                    $matriz = $fun->listaFuncionario();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->ativo == true && $dados->departamento != 'Escritório') {

                                                            $nome = $dados->nome;
                                                            echo "<option value=1>" . $nome . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>
                                            <div class="form-group">
                                                <label for="motivo">Motivo:</label>
                                                <input type="text" id="motivo" name="motivo" class="form-control" />
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                            </div>
                                        </div>
                                    </form>

                                    <?php
                                    include_once "../modell/DetalheCelulaProduto.class.php";
//instancia a classe de controle
                                    $prodL = new DetalheCelulaProduto();

                                    $id_celula = \filter_input(INPUT_POST, 'id_celula');
                                    $id_produto = \filter_input(INPUT_POST, 'id_produto');
                                    $data = \filter_input(INPUT_POST, 'data');
                                    $pecas_determinadas = \filter_input(INPUT_POST, 'n_pecas');
                                    $pecas_finalizadas = \filter_input(INPUT_POST, 'n_feitas');
                                    $tempo_unitario = \filter_input(INPUT_POST, 'tempo_unitario');
                                    $falta = \filter_input(INPUT_POST, 'abstencao');
                                    $motivo_falta = \filter_input(INPUT_POST, 'motivo');
                                    $status = '1';
                                    $obs = \filter_input(INPUT_POST, 'obs');
                                    $margem_erro = \filter_input(INPUT_POST, 'reajuste');
                                    $cadastro = \filter_input(INPUT_POST, 'cadastrar');
                                    if (isset($cadastro)) {
                                        if (empty($id_celula) || empty($id_produto) || empty($pecas_determinadas)) {
                                            echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                            //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                        } else {
                                            //var_dump($id_lote, $id_produto, $data, $n_peca,$preco,$observacao);
                                            $status = $prodL->cadastraCelulaProduto($id_celula, $id_produto, $data, $pecas_determinadas, $pecas_finalizadas, $tempo_unitario, $status, $obs, $falta, $motivo_falta, $margem_erro);
                                            if ($status == true) {
                                                echo "<script> alert('Registro inserido com sucesso.');</script>";
                                            } else {
                                                echo "<script> alert('Erro ao inserir registro.');</script>";
                                            }
                                        }
                                    }
                                    ?>


                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- MODAL DE ANOTAÇÕES -->
                    <div class="modal fade" id="anotacoes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Cadastro de Anotações</h4>
                                </div>
                                <div class="modal-body">
                                    <form method="post" class="form-horizontal">
                                        <div class="panel-body">                                                
                                            <div class="form-group">
                                                <label for="data">Data do ocorrido:</label>
                                                <input type="date" id="data" name="data" class="form-control"  required="required"   />
                                            </div>                                    
                                            <div class="form-group">
                                                <label for="id_celula" class="tamanho-fonte">Célula de Trabalho:</label><small> (Campo Obrigatório)</small>
                                                <select name="id_celula" class="form-control" required="required" >                                       
                                                    <?php
                                                    echo "<option value=''>Selecione ...</option>";
                                                    include_once '../modell/CelulaTrabalho.class.php';
                                                    $lote = new CelulaTrabalho();
                                                    $matriz = $lote->listaCelula();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status_celula == TRUE) {
                                                            $id_celula = $dados->id_celula;
                                                            $pessoas_celula = $dados->pessoas_celula;
                                                            $funcionarios = $dados->funcionarios;
                                                            echo "<option value=" . $id_celula . ">" . $pessoas_celula . ' - ' . $funcionarios . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>                               
                                            <div class="form-group">
                                                <label for="descricao">Descrição da obeservação:</label>
                                                <textarea id="descricao" name="descricao" class="form-control" required="required" maxlength="250" rows="5"> </textarea>
                                                <div id="characterLeft"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>                 
                                            </div>
                                        </div>
                                    </form>

                                    <?php
                                    include_once "../modell/Anotacao.class.php";

//instancia a classe de controle
                                    $prod = new Anotacao();

                                    $descricao = \filter_input(INPUT_POST, 'descricao');
                                    $data = \filter_input(INPUT_POST, 'data');
                                    $id_celula = \filter_input(INPUT_POST, 'id_celula');
                                    $status = TRUE;
                                    $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                    if (isset($cadastro)) {
                                        if (empty($descricao)) {
                                            echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                            //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                        } else {
                                            $status = $prod->cadastraAnotacao($descricao, $data, $id_celula, $status);
                                            if ($status == true) {
                                                echo "<script> alert('Anotação inserida com sucesso.');</script>";
                                            } else {
                                                echo "<script> alert('Erro ao inserir registro.');</script>";
                                            }
                                        }
                                    }
                                    ?>   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            $('#characterLeft').text('250 caracteres aceitos');
            $('#descricao').keyup(function () {
                var max = 250;
                var len = $(this).val().length;
                if (len >= max) {
                    $('#characterLeft').text(' Você atingiu o limite de caracteres');
                } else {
                    var ch = max - len;
                    $('#characterLeft').text(ch + ' caracteres');
                }
            });
        </script>




<?php require_once "./actionRodape.php"; ?>

    </body>
</html>
