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
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Cálculo OEE</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="panel-heading">
                    <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                    <a href="cadastra_diario_bordo_producao.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    <a href="lista_diario_bordo.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Listar</a>
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
                $id_funcionario = $_POST['id_funcionario'];
                $data_ini = $_POST['data'];
                $data_fim = $_POST['data2'];
                $data1 = explode("-", $data_ini);
                $data2 = explode("-", $data_fim);
                $data1 = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
                $data2 = $data2[2] . '/' . $data2[1] . '/' . $data2[0];

                if ($id_funcionario == '') {
                    echo "<div class='alert alert-danger alert-dismissable'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                Você deve selecionar os campos acima.
                        </div>";
                } else {
                    include_once "../modell/DiarioBordo.class.php";
                    $diario = new DiarioBordo();
                    $nr = $data_ini;
                    ?>
                    <div class="panel panel-primary">            
                        <div class="panel-heading" style="text-align: center;">
                            - Cálculo do OEE (Overall Equipament Effectiveness)
                        </div>                                                    
                        <table class="table table-hover table-bordered" >
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Total de Peças</th>
                                    <th>Total de Peças Boas</th>
                                    <th>Total de Peças Ruins</th>
                                    <th title='Somatório do tempo teórico de agregação de valor ideal'>Σ ¹(TP.Qi)</th>
                                    <th title='Somatório do tempo teórico perdido em peças ruins'>Σ ¹(ΣTP.QNi)</th>
                                    <th title="OEE">OEE</th>
                                    <th title='disponibilidade'>(μ1)</th>
                                    <th title='performance'>(μ2)</th>
                                    <th title='qualidade'>(μ3)</th>
                                </tr>
                            </thead>
                            <tbody class="text_align">                                
                                <?php
                                while ($nr <= $data_fim) {
                                    $pecas_fabricadas = 0;
                                    $pecas_boas = 0;
                                    $pecas_ruin = 0;
                                    $funcionario = 0;
                                    $minutos = 0;
                                    $OEE = 0;
                                    $tempo_calendario = 510; // em minutos  
                                    //$parada_programada = 0;
                                    $disponibilidade = 0;
                                    $performance = 0;
                                    $qualidade = 0;
                                    $tempo_agregado_valor = 0;
                                    $tempo_agregado_valor2 = 0;
                                    $tempo_agregado_valor_ruin = 0;
                                    $tempo_agregado_valor_ruin2 = 0;
                                    //testar quando uma parada não for programada
                                    //se a parada for difernte de almoço intervalo
                                    // echo ($nr . " " . '<br>');
                                    $matriz = $diario->listaTodosDiarioCondicaoAgupadoOEE($nr, $nr, $id_funcionario);

                                    while ($dados = $matriz->fetchObject()) {
                                        $DATALOTE = explode("-", $dados->data);
                                        $DATALOTE = $DATALOTE[2] . '/' . $DATALOTE[1] . '/' . $DATALOTE[0];
                                        if ($dados->motivo != 'Banheiro;' || $dados->motivo != 'falta-luz;' || $dados->motivo != 'Não-Compareceu;') {
                                            $resultado1 = explode(':', $dados->hora_ini);
                                            $minutos1 = ($resultado1[0] * 60) + ($resultado1[1]) + ($resultado1[2] / 60);

                                            $resultado2 = explode(':', $dados->hora_fim);
                                            $minutos2 = ($resultado2[0] * 60) + ($resultado2[1]) + ($resultado2[2] / 60);
                                            $parada_programada = $minutos1 - $minutos2;
                                            //echo $parada_programada.'<br>';
                                            $parada_nao_programada = 0;
                                        } else {
                                            $resultado1 = explode(':', $dados->hora_ini);
                                            $minutos1 = ($resultado1[0] * 60) + ($resultado1[1]) + ($resultado1[2] / 60);
                                            $resultado2 = explode(':', $dados->hora_fim);
                                            $minutos2 = ($resultado2[0] * 60) + ($resultado2[1]) + ($resultado2[2] / 60);
                                            $parada_programada = $minutos1 - $minutos2;

                                            $resultado3 = explode(':', $dados->hora_ini);
                                            $minutos3 = ($resultado3[0] * 60) + ($resultado3[1]) + ($resultado3[2] / 60);
                                            $resultado4 = explode(':', $dados->hora_fim);
                                            $minutos4 = ($resultado4[0] * 60) + ($resultado4[1]) + ($resultado4[2] / 60);
                                            $parada_nao_programada = $minutos3 - $minutos4;
                                        }

                                        if (empty($dados->tempo)) {
                                            $minutos = 0;
                                        } else {
                                            $resultado = explode(':', $dados->tempo);
                                            $minutos = ($resultado[0] * 60) + ($resultado[1] + $resultado[2] / 60);

                                            $tempo_agregado_valor = $dados->n_peca_boa * $minutos;
                                            $tempo_agregado_valor_ruin = $dados->n_peca_ruim * $minutos;
                                            $OEE = ($tempo_agregado_valor) / ($tempo_calendario - $parada_programada) * 100;
                                            $disponibilidade = (($tempo_calendario - $parada_programada - $parada_nao_programada)/$tempo_calendario) * 100;
                                            $performance = ($tempo_agregado_valor + $tempo_agregado_valor_ruin) / ($tempo_calendario - $parada_programada - $parada_nao_programada) * 100;

                                            if ($tempo_agregado_valor == 0) {
                                                $tempo_agregado_valor = 1;
                                                $qualidade = ($tempo_agregado_valor) / ($tempo_agregado_valor + $tempo_agregado_valor_ruin) * 100;
                                            } else {
                                                $qualidade = ($tempo_agregado_valor) / ($tempo_agregado_valor + $tempo_agregado_valor_ruin) * 100;
                                            }
                                        }

                                        $pecas_fabricadas += $dados->total_peca;
                                        $pecas_boas += $dados->n_peca_boa;
                                        $pecas_ruin += $dados->n_peca_ruim;
                                        $funcionario = $dados->nome;
                                        $tempo_agregado_valor2 += $tempo_agregado_valor;
                                        $tempo_agregado_valor_ruin2 += $tempo_agregado_valor_ruin;
                                    }

                                    if ($pecas_fabricadas != 0) {

                                        $oee2 = ($tempo_agregado_valor2) / ($tempo_calendario - $parada_programada) * 100;
                                        $disponibilidade2 = ($tempo_calendario - $parada_programada - $parada_nao_programada) / ($tempo_calendario - $parada_programada) * 100;
                                        $performance2 = ($tempo_agregado_valor2 + $tempo_agregado_valor_ruin2) / ($tempo_calendario - $parada_programada) * 100;

                                        if ($tempo_agregado_valor2 == 0) {
                                            $tempo_agregado_valor2 = 1;
                                            $qualidade2 = ($tempo_agregado_valor2) / ($tempo_agregado_valor2 + $tempo_agregado_valor_ruin2) * 100;
                                        } else {
                                            $qualidade2 = ($tempo_agregado_valor2) / ($tempo_agregado_valor2 + $tempo_agregado_valor_ruin2) * 100;
                                        }

                                        echo "<tr>
                                            <td>" . $DATALOTE . "</td>                                    
                                            <td class='success'>" . $pecas_fabricadas . "</td>
                                            <td class='success'>" . $pecas_boas . "</td>
                                            <td class='success'>" . $pecas_ruin . "</td>
                                            <td class='warning' title='Somatório do tempo de agregação de valor ideal'>" . number_format($tempo_agregado_valor2, 0, ',', '') . " </td>
                                            <td class='warning' title='Somatório do tempo teórico perdido em peças ruins'> " . number_format($tempo_agregado_valor_ruin2, 0, ',', '') . " </td>
                                            <td class='warning' title='OEE'>" . number_format($oee2, 2, ',', '') . " %</td>
                                            <td class='danger' title='disponibilidade'>" . number_format($disponibilidade2, 2, ',', '') . " %</td>
                                            <td class='danger' title='performance'>" . number_format($performance2, 2, ',', '') . " %</td>
                                            <td class='danger' title='qualidade'>" . number_format($qualidade2, 2, ',', '') . " %</td>
                                        </tr>";
                                    }
                                    $nr++;
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>


                <?php
                include_once "../modell/DiarioBordo.class.php";
                $diario = new DiarioBordo();
                $matriz = $diario->listaTodosDiarioCondicaoDataProduzidos($data_ini, $data_fim, $id_funcionario);
                if (empty($matriz)) {
                    echo "<div class='alert alert-info alert-dismissable'>
                                                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                        Não possui nenhum registro armazenado.
                                                    </div>";
                } else {
                    $pecas_fabricadas = 0;
                    $pecas_boas = 0;
                    $pecas_ruin = 0;
                    $pecas_fabricadasM = 0;
                    $pecas_boasM = 0;
                    $pecas_ruinM = 0;
                    $funcionario = 0;
                    $total = 0;

                    while ($dados = $matriz->fetchObject()) {
                        $DATALOTE = explode("-", $dados->data);
                        $DATALOTE = $DATALOTE[2] . '/' . $DATALOTE[1] . '/' . $DATALOTE[0];
                        if ($dados->turno == 1) {
                            $turno = 'Manhã';
                        } else {
                            $turno = 'Tarde';
                        }
                        if ($dados->turno == 2) {
                            $turno = $turno;
                            $pecas_fabricadas += $dados->total_peca;
                            $pecas_boas += $dados->n_peca_boa;
                            $pecas_ruin += $dados->n_peca_ruim;
                        } else {
                            $turnoM = $turno;
                            $pecas_fabricadasM += $dados->total_peca;
                            $pecas_boasM += $dados->n_peca_boa;
                            $pecas_ruinM += $dados->n_peca_ruim;
                        }
                        $funcionario = $dados->nome;
                        $total += $dados->custo_funcao * $dados->total_peca;
                    }
                }
                ?>            


                <!-- CALCULO DE PRODUÇÃO E SALÁRIO DE FUNCIONARIOS-->
                <div class="panel panel-primary">                    
                    <div class="panel panel-primary">                                
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th>Funcionário</th>
                                    <th>Turno</th>
                                    <th>Total de Peças</th>
                                    <th>Total de Peças Boas</th>
                                    <th>Total de Peças Ruins</th>                                    
                                </tr>
                            </thead>                            
                            <tbody>
                                <tr>                                    
                                    <td><?= $funcionario ?></td>                                    
                                    <td><?= $turno; ?></td>
                                    <td><?= $pecas_fabricadas; ?></td>                                    
                                    <td><?= $pecas_boas; ?></td>
                                    <td><?= $pecas_ruin; ?></td>

                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <div class="panel panel-primary">                                
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th>Funcionário</th>
                                    <th>Turno</th>
                                    <th>Total de Peças</th>
                                    <th>Total de Peças Boas</th>
                                    <th>Total de Peças Ruins</th>                                    
                                </tr>
                            </thead>                            
                            <tbody>
                                <tr>                                    
                                    <td><?= $funcionario ?></td>                                    
                                    <td><?= $turnoM; ?></td>
                                    <td><?= $pecas_fabricadasM; ?></td>                                    
                                    <td><?= $pecas_boasM; ?></td>                                    
                                    <td><?= $pecas_ruinM; ?></td>                                    
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <div class="panel panel-primary">                                
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th>Período</th>                                    
                                    <th>Total de Peças</th>
                                    <th>Total de Peças Boas</th>
                                    <th>Total de Peças Ruins</th>
                                    <th>Total</th>
                                </tr>
                            </thead>                            
                            <tbody>
                                <tr>                                    
                                    <td><?= $data1 . ' à ' . $data2; ?></td>                                    
                                    <td><?= $pecas_fabricadas + $pecas_fabricadasM; ?></td>                                    
                                    <td><?= $pecas_boas + $pecas_boasM; ?></td>                                    
                                    <td><?= $pecas_ruin + $pecas_ruinM; ?></td>
                                    <td><b>R$ <?= number_format(($total * 1.25), 2, ',', ''); ?></b></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-sm-12">
                        <label for="legenda">¹Somatório do tempo de agregação de valor ideal (ΣTP.Qi)</label><br>
                        <label for="legenda">¹Somatório do tempo teórico perdido em peças ruins (ΣTP.QNi)</label><br>
                        <label for="legenda">¹Overall Equipament Effectiveness (OEE)</label><br>
                        <label for="legenda">¹Disponibilidade(μ1)</label><br>
                        <label for="legenda">¹Performance (μ2)</label><br>
                        <label for="legenda">¹Qualidade (μ3)</label><br>
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
        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>