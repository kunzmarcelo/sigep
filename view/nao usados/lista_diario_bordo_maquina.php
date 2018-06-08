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
                                url: "alterarDiarioBordo.php",
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
                    <h1 class="page-header">Listagem Diário de Bordo</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="panel-heading">
                    <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                    <a href="cadastra_diario_bordo_producao.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    <!--<a href="calculo_diario_bordo_oee_agrupado.php" class="btn btn-info" title="Calulo do OEE"><span class='glyphicon glyphicon-plus'></span> Calcular OEE</a>-->
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
                                <label for="id_maquina" class="tamanho-fonte">Máquina:</label><small> (Campo Obrigatório)</small>
                                <select name="id_maquina" class="form-control" required="required" >                                       
                                    <?php
                                    echo "<option value=''>Selecione ...</option>";
                                    include_once '../modell/Maquina.class.php';
                                    $fun = new Maquina();
                                    $matriz = $fun->listaMaquina();

                                    while ($dados = $matriz->fetchObject()) {
                                        if ($dados->status == true) {
                                            $cod = $dados->id_maquina;
                                            $numero = $dados->numero;
                                            $nome = $dados->descricao;
                                            echo "<option value=" . $cod . ">" . $numero . ' - ' . $nome . "</option>";
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

            if (isset($_POST['enviar'])) {

                include_once '../modell/Maquina.class.php';
                $fun = new Maquina();
                $matriz = $fun->listaUmMaquina($_POST['id_maquina']);

                $dados = $matriz->fetchObject();
                ?>
                <div class="panel panel-primary">            
                    <div class="panel-heading" style="text-align: center;">
                        Diário de Bordo
                    </div>
                    <div class="table-responsive">                    
                        <table class="table table-hover table-bordered" id="tblEditavel">
                            <thead>
                                <tr>
                                    <th colspan="13">
                                        <h4>Máquina: <i><?= $dados->numero; ?> - <?= $dados->descricao; ?></i>
                                        </h4>
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="5"></th>
                                    <th colspan="3"  style="text-align: center"  class='danger'>Dados para paradas<br> NÃO PROGRAMADAS</th>
                                    <th colspan="2" style='text-align: center' class='success'>DADOS PARA PRODUÇÃO</th>
                                </tr>
                                <tr>
                                    <th style='text-align: center'>Data</th>
                                    <th style='text-align: center'>Operador</th>
                                    <th style='text-align: center'>Turno</th>
                                    <th style='text-align: center'>Cod / Produto</th>
                                    <th style='text-align: center'>Função</th>
                                    <th class='danger' style='text-align: center'>Hora Inicial</th>
                                    <th class='danger' style='text-align: center'>Hora Final</th>
                                    <th class='danger' style='text-align: center'>Motivo</th>
                                    <th class='success' style='text-align: center'>N° peças boas</th>
                                    <th class='success' style='text-align: center'>N° peças ruins</th>
                                    <th style='text-align: center'>OBS</th>                                    
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style='text-align: center'>Data</th>
                                    <th style='text-align: center'>Operador</th>
                                    <th style='text-align: center'>Turno</th>
                                    <th style='text-align: center'>Cod / Produto</th>
                                    <th style='text-align: center'>Função</th>
                                    <th class='danger' style='text-align: center'>Hora Inicial</th>
                                    <th class='danger' style='text-align: center'>Hora Final</th>
                                    <th class='danger' style='text-align: center'>Motivo</th>
                                    <th class='success' style='text-align: center'>N° peças boas</th>
                                    <th class='success' style='text-align: center'>N° peças ruins</th>
                                    <th style='text-align: center'>OBS</th>                                    
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                //echo 'chegou';
                                //$id_funcionario = $_POST['id_funcionario'];
                                $id_maquina = $_POST['id_maquina'];
                                $data_ini = $_POST['data'];
                                $data_fim = $_POST['data2'];
                                $data1 = explode("-", $data_ini);
                                $data2 = explode("-", $data_fim);
                                $data1 = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
                                $data2 = $data2[2] . '/' . $data2[1] . '/' . $data2[0];

                                //$data1 = $_POST['data1'];
                                if (empty($id_maquina)) {
                                    echo "<div class='alert alert-danger alert-dismissable'>
                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                Você deve selecionar os campos acima.
                                            </div>";
                                } else {
                                    include_once "../modell/DiarioBordo.class.php";
                                    $diario = new DiarioBordo();
                                    $matriz = $diario->listaTodosDiarioCondicaoDataMaquina($data_ini, $data_fim, $id_maquina);
                                    $TOTALPECASBOAS = 0;
                                    $TOTALPECASRUINS = 0;
                                    while ($dados = $matriz->fetchObject()) {

                                        $DATALOTE = explode("-", $dados->data);
                                        $DATALOTE = $DATALOTE[2] . '/' . $DATALOTE[1];

                                        $nome = explode(" ", $dados->nome);
                                        if ($dados->turno == 'Tarde') {
                                            $turno = '2';
                                        } else {
                                            $turno = '1';
                                        }

                                        $teste = explode(";", $dados->motivo);
                                        $motivo = "<span class='label label-danger'>" . $teste[0] . "</span>";

                                        if ($dados->descricao == '*****PRODUTO****') {
                                            $produto = '-';
                                            $funcao = '-';
                                            $pecas_boas = '-';
                                            $pecas_ruins = '-';
                                        } else {
                                            $produto = $dados->descricao;
                                            $funcao = $dados->funcao;
                                            $pecas_boas = $dados->pecas_boas;
                                            $pecas_ruins = $dados->pecas_ruins;
                                        }
                                        if ($dados->hora_ini == '00:00:00' && $dados->hora_fim == '00:00:00') {
                                            $hora_ini = '-';
                                            $hora_fim = '-';

                                            echo "<tr>                                                        
                                                        <td title='data'><b>" . $DATALOTE . "</b></td>
                                                        <td title='funcionário'>" . $nome[0] . "</td>
                                                        <td title='turno'>" . $turno . "</td>
                                                        <td title='Produto'>" . substr($produto, 0, 18) . "</td>
                                                        <td title='Função'>" . $funcao . "</td>
                                                        <td title='hora_ini' class='danger' style='text-align: center'>" . $hora_ini . "</td>
                                                        <td title='hora_fim' class='danger' style='text-align: center'>" . $hora_fim . "</td>
                                                        <td title='motivo'class='danger'>" . $motivo . "</td>
                                                        <td title='pecas_boas'class='success'><b>" . $pecas_boas . "</b></td>
                                                        <td title='pecas_ruins' class='success'><b>" . $pecas_ruins . "</b></td>
                                                        <td title='obs'>" . $dados->obs . "</td>
                                                    </tr>";
                                        } else {
                                            $hora_ini = $dados->hora_ini;
                                            $hora_fim = $dados->hora_fim;
                                            echo "<tr class='warning'>
                                                        <td title='data'><b>" . $DATALOTE . "</b></td>
                                                        <td title='turno'>" . $nome[0] . "</td>
                                                        <td title='turno'>" . $turno . "</td>                                                        
                                                        <td title='Produto'>" . substr($produto, 0, 18) . "</td>
                                                        <td title='Função'>" . $funcao . "</td>
                                                        <td title='hora_ini'class='danger' style='text-align: center'>" . $hora_ini . "</td>
                                                        <td title='hora_fim'class='danger' style='text-align: center'>" . $hora_fim . "</td>
                                                        <td title='motivo'class='danger'>" . $motivo . "</td>
                                                        <td title='pecas_boas'class='success'><b>" . $pecas_boas . "</b></td>
                                                        <td title='pecas_ruins'class='success'><b>" . $pecas_ruins . "</b></td>
                                                        <td title='obs'>" . $dados->obs . "</td>                                                        
                                                    </tr>";
                                        }
                                        $TOTALPECASBOAS += $pecas_boas;
                                        $TOTALPECASRUINS += $pecas_ruins;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
                if ($id_maquina == 236) {
                    ?>
                    <div class="panel panel-primary">
                        <table class="table table-hover table-bordered" >
                            <thead>
                                <tr>
                                    <th>Período</th>
                                    <th>N° peças Boas</th>
                                    <th>N° peças Ruins</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $data1 . ' à ' . $data2; ?></td>
                                    <td><?= number_format(($TOTALPECASBOAS / 3), 2, ',', ''); ?>¹</td>
                                    <td><?= number_format(($TOTALPECASRUINS), 2, ',', ''); ?></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
            ¹ O resultado é <?=number_format(($TOTALPECASBOAS), 2, ',', ''); ?>/3 = <?= number_format(($TOTALPECASBOAS / 3), 2, ',', ''); ?>
                    <?php
                } else {
                    ?>

                    <div class="panel panel-primary">
                        <table class="table table-hover table-bordered" >
                            <thead>
                                <tr>
                                    <th>Período</th>
                                    <th>N° peças Boas</th>
                                    <th>N° peças Ruins</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $data1 . ' à ' . $data2; ?></td>
                                    <td><?= number_format(($TOTALPECASBOAS), 2, ',', ''); ?></td>
                                    <td><?= number_format(($TOTALPECASRUINS), 2, ',', ''); ?></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <?php
                }
                ?>

            </div>
            <?php
        }

        /* Captação de dados */
        $buffer = ob_get_contents(); // Obtém os dados do buffer interno
        $filename = "code.html"; // Nome do arquivo HTML
        file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
        ?>  

        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>