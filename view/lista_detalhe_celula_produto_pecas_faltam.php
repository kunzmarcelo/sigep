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
//                        $url = $_SERVER['REQUEST_URI'];
//                        $part = explode("/", $url);
//                        $part[3];
//
//                        include_once '../modell/Produto.class.php';
//                        $con = new BancoDadosPDO();
//                        $titulo = $con->listarUm("menu_filho", "link like '$part[3]'");
//                        $resultado = $titulo->fetchObject();
 //$resultado->nome
                        ?>
                        Listagem de peças parâmetros

                    </h1>
                </div>
                <!-- /.col-lg-12 -->

                <div class="row">
                    <div class="col-lg-12">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
                                <form class="form-horizontal" method="get">                                    
                                    <div class="form-group"> 
                                        <div class="col-xs-6">
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
                                        <div class="col-xs-6">
                                            <label for="status" class="tamanho-fonte">Status:</label><small> (Campo Obrigatório)</small>
                                            <select name="status" class="form-control" required="required" >                                       
                                                <option value="">Selecione...</option>
                                                <option value="0">Parado  </option>
                                                <option value="1">Em Produção</option>
                                                <option value="2">Finalizado</option>
                                                <option value="3">Proximo Dia</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">                                
                                        <div class="col-xs-6">
                                            <label for="data">Data inicial:</label>
                                            <input type="date" name="data" value="" class="form-control" required="required" >
                                        </div>									
                                    </div>									
                                    <div class="form-group">                                
                                        <div class="col-xs-6">
                                            <label for="data2">Data Final:</label>
                                            <input type="date" name="data2" value="" class="form-control" required="required" >
                                        </div>									
                                    </div>									
                                    <div class="form-group">                                
                                        <div class="col-xs-6">                               
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
                                            <div class="col-xs-6">
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
                                            <th>Qtd. Falta</th>
                                            <th>Unit.</th>
                                            <!--<th>¹Tempo</th>-->
                                            <th>¹Estimativa</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>                       

                                        <?php
                                        if (isset($_GET['enviar'])) {
                                            $data_ini = $_GET['data'];
                                            $data_fim = $_GET['data2'];
                                            $status = $_GET['status'];
                                            $id_celula = $_GET['id_celula'];

                                            $data1 = explode("-", $data_ini);
                                            include_once "../modell/DetalheCelulaProduto.class.php";
                                            $lote = new DetalheCelulaProduto();
                                            $matriz = $lote->listaCelulaProdutoPecasFantantes($data_ini, $data_fim, $status, $id_celula);
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

                                                $faltas = $dados->pecas_determinadas - $dados->pecas_finalizadas;

                                                if ($dados->falta == 1) {
                                                    $data1 = explode("-", $dados->DATACELULA);
                                                    $diferenca = ((( $dados->pecas_finalizadas * 100)) / $dados->pecas_determinadas); //porcentagem

                                                    $tempoUni = explode(":", $dados->tempo_unitario);
                                                    $tempo3 = (($tempoUni[0] * 3600) + ($tempoUni[1] * 60) + ($tempoUni[2] ));
                                                    $tempoTotal = ($faltas * $tempo3) / ($dados->pessoas_celula - $dados->falta);
                                                    $total_segundos1 = $tempoTotal;
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
                                                    $tempoTotal = ($faltas * $tempo3) / $dados->pessoas_celula;
                                                    $total_segundos1 = $tempoTotal;
                                                    $horas1 = floor($total_segundos1 / (60 * 60));
                                                    $sobra_horas1 = ($total_segundos1 % (60 * 60));
                                                    $minutos1 = floor($sobra_horas1 / 60);
                                                    $sobra_minutos1 = ($sobra_horas1 % 60);
                                                    $segundos1 = $sobra_minutos1;
                                                    $resultadoTempoEstimado = $horas1 . ':' . $minutos1 . ':' . $segundos1;
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

                                                echo "<tr>
                                                        <td title='id'>" . $dados->id . "</td>
                                                        <td title='data' class='editavel'>" . $data1[2] . '/' . $data1[1] . "</b></td>                                                   
                                                        <td title='numero'>" . $dados->pessoas_celula . ' - ' . $dados->funcionarios . "</td>                                                   
                                                        <td title='falta' class='editavel'>" . $falta . "</td>                                                 
                                                        <td title='motivo_falta' class='editavel'>" . $motivo_falta . "</td>                                                 
                                                        <td title='id_produto' class='editavel'>" . $dados->descricao . "</td>
                                                        <td title='obs' class='editavel'>" . $OBSERVACAO . " </td>                                                   
                                                        <td title='Faltam' >" . number_format($faltas, 2, '.', '.') . "</td>
                                                        <td title='tempo_unitario' class='editavel'>" . $dados->tempo_unitario . "</td>
                                                        <td title='(número de peças * tempo Unitário) / número de pessoas'>" . '0' . $horas1 . ':' . $minutos1 . ':' . $segundos1 . " </td>
                                                        <td title='status' class='editavel'>" . $status . " </td>
                                                  </tr>";
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
                                            <th>Período</th>
                                            <th>Total de Peças que Faltam</th>
                                            <th>Tempo estimado para recuperação da produção</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                $data_ini = explode("-", $_GET['data']);
                                                $data_fim = explode("-", $_GET['data2']);
                                                echo" $data_ini[2]/$data_ini[1] á $data_fim[2]/$data_fim[1]/$data_fim[0]";
                                                ?>
                                            </td>
                                            <td><?= $somaPeca - $somaPecaPronta ?> peças</td>
                                            <td> 
                                                <?php
                                                $total_segundos1 = (($somaTempoHora * 3600) + ($somaTempoMinu * 60) + ($somaTempoSegu));
                                                $horas1 = floor($total_segundos1 / (60 * 60));
                                                $sobra_horas1 = ($total_segundos1 % (60 * 60));
                                                $minutos1 = floor($sobra_horas1 / 60);
                                                $sobra_minutos1 = ($sobra_horas1 % 60);
                                                $segundos1 = $sobra_minutos1;
                                                echo "<b><span> $horas1 hora(s) $minutos1 minuto(s) $segundos1 segundo(s) </span></b>";
                                                ?>

                                            </td>                                                
                                        </tr>
                                    </tbody>                                        
                                </table>

                                <hr>
                                <!--</div>-->
                                <!--</div>-->
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-12">
                                        <!--<label for="legenda">¹Tempo: Hora final - Hora inicial.</label><br>-->
                                        <label for="legenda">¹ Tempo Estimado: Tempo estimado para a produção das peças = (número de peças * tempo Unitário) / número de pessoas</label><br>
                                        <label for="legenda">² Concluído: Percentual de peças concluidas.</label><br>
                                        <!--<label for="legenda">Total de Tempo: Soma do resultado de (³≠ Tempo) </label><br>-->
                                        <!--<label for="legenda">Status: Informe: 0 - Parado; 1 - Em Produção; 2 - Finalizado; 3 - Proximo Dia;</label><br>-->
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
                        </div>
                    </div>
                </div>
            </div>
        </div>



<?php require_once "./actionRodape.php"; ?>
    </body>
</html>
