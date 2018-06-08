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
                    <h1 class="page-header">Listagem Diário de Bordo</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!--<div class="row">-->

            <div class="panel-heading">
                <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                <a href="cadastra_diario_bordo_producao.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
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
                    Diário de Bordo - Salário
                </div>
                <div class="table-responsive">                    
                    <table class="table table-hover" id="tblEditavel">
                        <thead>
                            <tr>
                                <th>Máquina</th>
                                <th>Data da Produção</th>
                                <th>Produto</th>
                                <th>N° peças boas</th>
                                <th>N° peças ruins</th>
                                <th>Valor</th>
                                <th>SubTotal boas</th>
                                <th>SubTotal ruins</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_POST['enviar'])) {
                                //echo 'chegou';
                                //$id_funcionario = $_POST['id_funcionario'];
                                $id_funcionario = $_POST['id_funcionario'];
                                $data_ini = $_POST['data'];
                                $data_fim = $_POST['data2'];
                                $data1 = explode("-", $data_ini);
                                $data2 = explode("-", $data_fim);
                                $data1 = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
                                $data2 = $data2[2] . '/' . $data2[1] . '/' . $data2[0];

                                //$data1 = $_POST['data1'];
                                if ($id_funcionario == '') {
                                    echo "<div class='alert alert-danger alert-dismissable'>
                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                Você deve selecionar os campos acima.
                                            </div>";
                                } else {
                                    include_once "../modell/DiarioBordo.class.php";
                                    $diario = new DiarioBordo();
                                    $matriz = $diario->calculaSalario($data_ini, $data_fim, $id_funcionario);
                                    if (empty($matriz)) {
                                        echo "<div class='alert alert-info alert-dismissable'>
                                                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                        Não possui nenhum registro armazenado.
                                                    </div>";
                                    } else {
                                        $pecas_boas = 0;
                                        $pecas_ruin = 0;
                                        $TOTALPECASBOAS = 0;
                                        $TOTALPECASRUINS = 0;
                                        while ($dados = $matriz->fetchObject()) {

                                            if ($dados->descricao != '*****PRODUTO****') {
                                                $DATALOTE = explode("-", $dados->data);
                                                $DATALOTE = $DATALOTE[2] . '/' . $DATALOTE[1] . '/' . $DATALOTE[0];
                                                echo "<tr>
                                                    <td><b>" . $dados->id_maquina . "</b></td>
                                                    <td>" . $DATALOTE . "</td>
                                                    <td>" . $dados->descricao . "</td>
                                                    <td class='warning'>" . $dados->pecas_boas . "</td>
                                                    <td class='warning'>" . $dados->pecas_ruins . "</td>
                                                    <td class='warning'>" .  number_format($dados->custo_funcao, 2, ',', '') . "</td>
                                                    <td class='success' title='peças boas * valor'>R$ " . number_format($dados->TOTALPECASBOAS, 2, ',', '') . "</td>
                                                    <td class='danger' title='peças ruins * valor'>R$ " . number_format($dados->TOTALPECASRUINS, 2, ',', '') . "</td>                                                   
                                                </tr>";
                                                $pecas_boas += $dados->pecas_boas;
                                                $pecas_ruin += $dados->pecas_ruins;
                                                $funcionario = $dados->nome;
                                                $TOTALPECASBOAS += $dados->TOTALPECASBOAS;
                                                $TOTALPECASRUINS += $dados->TOTALPECASRUINS;
                                            }
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
                    <table class="table table-hover table-bordered" >
                        <thead>
                            <tr>
                                <th>Funcionário</th>
                                <th>Período</th>
                                <th>TotalPeças Boas</th>
                                <th>TotalPeças Ruins</th>
                                <th>Total Boas</th>
                                <th>Total Ruins</th>
                                <th>Liquido¹</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $funcionario ?></td>
                                <td><?= $data1 . ' à ' . $data2; ?></td>
                                <td><?= $pecas_boas; ?></td>
                                <td><?= $pecas_ruin; ?></td>
                                <td>R$ <?= number_format($TOTALPECASBOAS, 2, ',', ''); ?></td>
                                <td>R$ <?= number_format($TOTALPECASRUINS, 2, ',', ''); ?></td>                              
                                <td><b>R$ <?= number_format($TOTALPECASBOAS - $TOTALPECASRUINS, 2, ',', ''); ?></b></td>
                            </tr>                                
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-sm-12">                        
                        <label for="legenda">¹Liquido: é SubTotal boas - SubTotal Ruins</label><br>
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
        <script src="../ajax/deletar_diario_bordo.js"></script>
        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>