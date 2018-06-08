<?php
session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
    include_once "../view/funcoes.php"; {
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


    </head>
    <body>
        <div id="wrapper">
            <?php require_once './actionfonteMenu.php'; ?>
            <div id="page-wrapper">
                <div class="row">
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
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center">
                                Fomulário de cálculo
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" role="form">
                                            <div class="form-group"> 
                                                <label for="id_produto" class="tamanho-fonte">Produto:</label><small> (Campo Obrigatório)</small>
                                                <select name="id_produto" id="id_produto" class="form-control" required="required">
                                                    <option value="">Selecione...</option>
                                                    <?php
                                                    include_once '../modell/Produto.class.php';
                                                    $fun = new Produto();
                                                    $matriz = $fun->listaProduto();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status == true) {
                                                            $cod = $dados->id_produto;
                                                            $nome = $dados->descricao;
                                                            echo "<option value=" . $nome . ">" . $nome . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>
                                            <div class="form-group">
                                                <label for="tempo">Tempo unitário:</label>
                                                <input type="time" id="tempo" name="tempo" class="form-control" step='1' min="00:00:00" max="24:00:00" />
                                            </div>
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
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                            </div> 

                                        </form>
                                    </div>

                                    <?php
                                    $titulo = \filter_input(INPUT_POST, 'id_produto');
                                    $n_pessoas = \filter_input(INPUT_POST, 'id_celula');
                                    $tempo = \filter_input(INPUT_POST, 'tempo'); //                            
                                    $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                    if (isset($cadastro)) {
                                        if (empty($tempo)) {
                                            echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                            //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                        } else {

                                            $hora_ini = '07:30:00';
                                            $hora_fim = '11:55:00';
                                            $hora_ini_tarde = '13:30:00';
                                            $hora_fim_tarde = '17:55:00';

                                            $resultado1 = explode(':', $hora_ini);
                                            $resultado2 = explode(':', $hora_fim);
                                            $resultado3 = explode(':', $hora_ini_tarde);
                                            $resultado4 = explode(':', $hora_fim_tarde);
                                            $tempo = explode(':', $tempo);
//print_r($tempo);

                                            $resultadoManha = (($resultado2[0] * 60) + $resultado2[1] + ($resultado2[2] / 60)) - (($resultado1[0] * 60) + $resultado1[1] + ($resultado1[2] / 60));
                                            $resultadoTarde = (($resultado4[0] * 60) + $resultado4[1] + ($resultado4[2] / 60)) - (($resultado3[0] * 60) + $resultado3[1] + ($resultado3[2] / 60));
                                            $Valorminutos = $resultadoManha + $resultadoTarde - 30;

                                            $tempoR = (($tempo[0] * 60) + $tempo[1] + ($tempo[2] / 60));

                                            $pecasDia = ($Valorminutos / $tempoR) * $n_pessoas;
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label> A produção será  de:</label>                                   
                                            <div class='alert alert-info alert-dismissable'>
                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                <?= "<label>" . number_format($pecasDia, 2, ',', '.') . " peças/dia de $titulo</label>" ?>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" style="text-align: center">
                                                Dados
                                            </div>
                                            <table class="table table-hover" id="tblEditavel">
                                                <thead>
                                                    <tr>                                                   
                                                        <th>Quantidade</th>
                                                        <th>Tempo Unitário</th>
                                                        <th>Tempo Calendário</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>                                                   
                                                        <td> <?= $n_pessoas ?> Pessoas</td>
                                                        <td> 
                                                            <?php
                                                            $total_segundos = (($tempo[0] * 3600) + $tempo[1] * 60 + ($tempo[2]));


                                                            $horas = floor($total_segundos / (60 * 60));
                                                            $sobra_horas = ($total_segundos % (60 * 60));
                                                            $minutos = floor($sobra_horas / 60);
                                                            $sobra_minutos = ($sobra_horas % 60);
                                                            $segundos = $sobra_minutos;
                                                            ?>
                                                            <?= "$horas hora (s), $minutos minuto (s) e $segundos segundo (s)" ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $total = ($resultadoManha + $resultadoTarde - 30) * 60;
                                                            $horas = floor($total / 3600);
                                                            $minutos = floor(($total - ($horas * 3600)) / 60);
                                                            $segundos = floor($total % 60);
                                                            echo "$horas hora (s), $minutos minuto (s) e $segundos segundo (s)¹";
                                                            ?>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <small>¹ Tempo liquido, descontado 30 min de intervalo </small>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="../bootstrap/assets/js/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function () {
            //                $('.date').mask('11/11/1111');
            $('#tempo').mask('00:00:00');
            $('#mao_obra').mask('0.00');
            //                $('.date_time').mask('99/99/9999 00:00:00');
            //                $('.cep').mask('99999-999');
            //                $('.phone').mask('9999-9999');
            //                $('.phone_with_ddd').mask('(99) 9999-9999');
            //                $('.phone_us').mask('(999) 999-9999');
            //                $('.mixed').mask('AAA 000-S0S');
        });
    </script>

    <?php require_once "./actionRodape.php"; ?>

</body>
</html>