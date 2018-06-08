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
            ?>
            <div class="panel panel-primary">            
                <div class="panel-heading" style="text-align: center;">
                    Diário de Bordo
                </div>
                <div class="table-responsive">                    
                    <table class="table table-hover" id="tblEditavel">
                        <thead>
                            <tr>                                
                                <th>Funcionário</th>
                                <th>Número do Posto de Trabalho</th>
                                <th>Total Peças Boas por Posto</th>
                                <th>Total Peças Ruins por Posto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_POST['enviar'])) {
                                $data_ini = $_POST['data'];
                                $data_fim = $_POST['data2'];
                                $data1 = explode("-", $data_ini);
                                $data2 = explode("-", $data_fim);
                                $data1 = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
                                $data2 = $data2[2] . '/' . $data2[1] . '/' . $data2[0];

                                //$data1 = $_POST['data1'];
                                if ($data_ini == '' || $data_ini == '') {
                                    echo "<div class='alert alert-danger alert-dismissable'>
                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                Você deve selecionar os campos acima.
                                            </div>";
                                } else {
                                    include_once "../modell/DiarioBordo.class.php";
                                    $diario = new DiarioBordo();
                                    $matriz = $diario->listaTodosMaquinasFuncionarios($data_ini, $data_fim);
                                    if (empty($matriz)) {
                                        echo "<div class='alert alert-info alert-dismissable'>
                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                Não possui nenhum registro armazenado.
                                            </div>";
                                    } else {
                                        while ($dados = $matriz->fetchObject()) {
                                            echo "<tr>
                                                    <td>" . $dados->nome . "</td>
                                                    <td>" . $dados->numero . "</td>                                                        
                                                    <td><b>" . number_format($dados->PECASBOAS,2,',','.') . "</b></td>                                                        
                                                    <td><b>" . number_format($dados->PECASRUINS,2,',','.') . "</b></td>                                                        
                                                </tr>";
                                        }
                                    }
                                }
                                ?>
                        </tbody>
                        </table>
                    </div>
                </div>                
            </div>
            <?php
        }
        /* Captação de dados */
        $buffer = ob_get_contents(); // Obtém os dados do buffer interno
        $filename = "code.html"; // Nome do arquivo HTML
        file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
        ?>  


        <script src="../ajax/deletar_diario_bordo.js"></script>
        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>