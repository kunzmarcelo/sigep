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
                                                <label for="hora_ini">Horário de inicio:</label>
                                                <input type="time" id="hora_ini" name="hora_ini" class="form-control" value="07:30:00" step='1' min="00:00:00" max="23:59:00" required="required"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="hora_fim">Horário final:</label>
                                                <input type="time" id="hora_fim" name="hora_fim" class="form-control" value="17:55:00" step='1' min="00:00:00" max="23:59:00" required="required"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="hora_int">Tempo de Intervalo</label>
                                                <input type="time" id="hora_int" name="hora_int" class="form-control" value="02:05:00" step='1' min="00:00:00" max="23:59:00" required="required"/>
                                            </div>                                   
                                            <div class="form-group">
                                                <label for="tempo">Tempo unitário:</label>
                                                <input type="time" id="tempo" name="tempo" class="form-control" step='1' min="00:00:00" max="23:59:00" required="required"/>
                                            </div>
                                            <div class="form-group">



                                                <label for="n_pessoas" class="tamanho-fonte">Quantidade de Funcionários:</label><small> (Campo Obrigatório)</small>
                                                <select name="n_pessoas" class="form-control" required="required" >
                                                    <option value="">Selecione...</option>
                                                    <option value="1">1 Funcionários</option>
                                                    <option value="2">2 Funcionários</option>
                                                    <option value="3">3 Funcionários</option>
                                                    <option value="4">4 Funcionários</option>
                                                    <option value="5">5 Funcionários</option>
                                                    <option value="6">6 Funcionários</option>
                                                    <option value="7">7 Funcionários</option>
                                                    <option value="8">8 Funcionários</option>
                                                    <option value="9">9 Funcionários</option>
                                                </select>

                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Calcular</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                            </div> 

                                        </form>
                                    </div>

                                    <?php
                                    $titulo = \filter_input(INPUT_POST, 'id_produto');
                                    $hora_ini = \filter_input(INPUT_POST, 'hora_ini');
                                    $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                                    $hora_int = \filter_input(INPUT_POST, 'hora_int');
                                    $n_pessoas = \filter_input(INPUT_POST, 'n_pessoas');
                                    $cadastro = \filter_input(INPUT_POST, 'cadastrar');
//                            $n_peca = \filter_input(INPUT_POST, 'n_peca');
                                    $hora_uni = \filter_input(INPUT_POST, 'tempo'); // 
                                    if (isset($cadastro)) {
                                        if (empty($hora_fim) || empty($n_pessoas)) {
                                            echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                        </div>";
//                                            Algum dos campos acima não foi preenchido corretamente.
                                            //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                        } else {
                                            //transformar em segundos
                                            $hora_ini1 = explode(":", $hora_ini);
                                            $hora_fim1 = explode(":", $hora_fim);
                                            $hora_int1 = explode(":", $hora_int);
                                            $hora_uni1 = explode(':', $hora_uni);

                                            if (empty($hora_ini1[2])) {
                                                $hora_ini1[2] = '00';
                                            }
                                            if (empty($hora_fim1[2])) {
                                                $hora_fim1[2] = '00';
                                            }
                                            if (empty($hora_int1[2])) {
                                                $hora_int1[2] = '00';
                                            }
                                            if (empty($hora_uni1[2])) {
                                                $hora_uni1[2] = '00';
                                            }
                                            $tempo1 = (($hora_ini1[0] * 3600) + ($hora_ini1[1] * 60) + ($hora_ini1[2]));
                                            $tempo2 = (($hora_fim1[0] * 3600) + ($hora_fim1[1] * 60) + ($hora_fim1[2]));
                                            $tempo3 = (($hora_int1[0] * 3600) + ($hora_int1[1] * 60) + ($hora_int1[2]));
                                            $tempoR = (($hora_uni1[0] * 3600) + ($hora_uni1[1] * 60) + ($hora_uni1[2]));

                                            $resultado_tempo = ($tempo2 - $tempo1) - $tempo3;

                                            // realiza o calculo de peças produzidas de acordo com o tempo informado no formulário
                                            $pecas = ($resultado_tempo / $tempoR) * $n_pessoas;
                                            ?>

                                        </div>
                                        <div class="form-group">
                                            <label> A produção será  de:</label>                                   
                                            <div class='alert alert-info alert-dismissable'>
                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                <b>
                                                    <?= number_format($pecas, 2, ',', '.'); ?> peças de <?= $titulo ?>
                                                </b>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" style="text-align: center">
                                                    Dados
                                                </div>
                                                <table class="table table-hover" id="tblEditavel">
                                                    <thead>
                                                        <tr>
                                                            <th>Hora Inicial</th>
                                                            <th>Hora Final</th>
                                                            <th>Intervalo</th>                                                    
                                                            <th>Funcionários</th>
                                                            <th>Tempo Unitário</th>
                                                            <th>Tempo Calendário</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><?= $hora_ini ?></td>
                                                            <td><?= $hora_fim ?></td>
                                                            <td><?= $hora_int ?></td>

                                                            <td> <?= $n_pessoas ?> Funcionários</td>
                                                            <td> 
                                                                <?php
                                                                $total_segundos = $tempoR;

                                                                $horas = floor($total_segundos / (60 * 60));
                                                                $sobra_horas = ($total_segundos % (60 * 60));
                                                                $minutos = floor($sobra_horas / 60);
                                                                $sobra_minutos = ($sobra_horas % 60);
                                                                $segundos = $sobra_minutos;
                                                                echo "$horas hora (s), $minutos minuto (s) e $segundos segundo (s)"
                                                                ?>
                                                            </td>
                                                            <td> 
                                                                <?php
                                                                $total_segundos = $resultado_tempo;

                                                                $horas = floor($total_segundos / (60 * 60));
                                                                $sobra_horas = ($total_segundos % (60 * 60));
                                                                $minutos = floor($sobra_horas / 60);
                                                                $sobra_minutos = ($sobra_horas % 60);
                                                                $segundos = $sobra_minutos;
                                                                echo "<label> $horas hora (s), $minutos minuto (s) e $segundos segundo (s)¹</label>"
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <small>¹ Tempo liquido</small><br>
                                                <small>¹ Tempo calendário = ((Hora final - Hora inicial) - Intervalo)</small>
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
        </div>
    <script src="../bootstrap/assets/js/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function () {
            //                $('.date').mask('11/11/1111');
            $('#tempo').mask('00:00:00');
            $('#hora_fim').mask('00:00:00');
            $('#hora_ini').mask('00:00:00');
            $('#hora_int').mask('00:00:00');
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
