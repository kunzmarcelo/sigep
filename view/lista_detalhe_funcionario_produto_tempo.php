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
                                url: "alterarDetalheFuncionarioProduto.php",
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
                        <div class="panel-heading">
                            <div class="col-lg-6">                                
                                <form class="form-horizontal" method="post">                                
                                    <div class="form-group">                                            
                                        <label for="id_funcionario" class="tamanho-fonte">Funcionário:</label><small> (Campo Obrigatório)</small>
                                        <select name="id_funcionario" class="form-control" required="required">                                       
                                            <?php
                                            echo "<option value=''><b>Selecione ...</b></option>";
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
                                        <label for="data_ini">Data de Início da produção:</label>
                                        <input type="date" id="data_ini" name="data_ini" value="<?= date('Y-m-01') ?>" class="form-control" required="required" />
                                    </div>
                                    <div class="form-group">
                                        <label for="data_fim">Data de fim da produção:</label>
                                        <input type="date" id="data_fim" name="data_fim" value="<?= date('Y-m-t') ?>" class="form-control" required="required" />
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="enviar" value="Enviar" class="btn btn-primary">Enviar</button>
                                        <!--<input type="date" id="data_fim" name="data_fim" value="<?= date('Y-m-t') ?>" class="form-control" required="required" onchange="this.form.submit()" />-->
                                    </div>
                                </form>

                                <div class="form-group">
                                    <form action="gerarPDF.php" class="form-horizontal">
                                        <div class="form-group">
                                            <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                        </div>
                                    </form>
                                </div>                              
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center;">
                                Listagem
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">                     
                                <table class="table table-hover" id="tblEditavel">
                                    <thead>
                                        <tr>                                        
                                            <th>#</th> 
                                            <th>N° Controle</th>
                                            <!--<th>Funcionário</th>-->
                                            <th>Produto</th>
                                            <th>Operação</th>                                        
<!--                                            <th>Inicio</th>
                                            <th>Fim</th>-->
                                            <th>Qtd.</th>
<!--                                            <th>Etimado</th>-->
                                            <th>Prod.</th> 
                                            <!--<th>Falt</th>--> 
                                            <th>Tabalhado</th> 
                                            <th>Valor</th> 
                                            <th><i class="fa fa-trash-o"></i></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>                                        
                                            <th>#</th> 
                                            <th>N° Controle</th>
                                            <!--<th>Funcionário</th>-->
                                            <th>Produto</th>
                                            <th>Operação</th>                                        
<!--                                            <th>Inicio</th>
                                            <th>Fim</th>-->
                                            <th>Qtd.</th>
<!--                                            <th>Etimado</th>-->
                                            <th>Prod.</th> 
                                            <!--<th>Falt</th>--> 
                                            <th>Tabalhado</th> 
                                            <th>Valor</th> 
                                            <th><i class="fa fa-trash-o"></i></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>                       
                                        <?php
                                        $id_funcionario = \filter_input(INPUT_POST, 'id_funcionario');
                                        $data_ini = \filter_input(INPUT_POST, 'data_ini');
                                        $data_fim = \filter_input(INPUT_POST, 'data_fim');
                                        $enviar = \filter_input(INPUT_POST, 'enviar');


                                        if (isset($enviar)) {

                                            if (empty($id_funcionario)) {
                                                echo" <div class='alert alert-warning' role='alert'>
                                                       <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Selecione os campos acima.</h4>
                                                    </div>";
                                            } else {
                                                //$numero = \filter_input(INPUT_POST, 'numero');
                                                //var_dump($id_funcionario, $data_ini, $data_fim);
                                                include_once "../modell/DetalheFuncionarioProduto.php";
                                                $lote = new DetalheFuncionarioProduto();
                                                $matriz = $lote->listaDetalheFuncionarioProdutoCondicaoDatas($id_funcionario, $data_ini, $data_fim);
                                                $somaTempoHora = 0;
                                                $somaTempoMinu = 0;
                                                $somaTempoSegu = 0;
                                                $funcionario = 0;
                                                $somarMedia = 0;
                                                $custo_mao_obra = 0.25; //0.25 CUSTO DE MÃO DE OBRA POR MINUTO 
                                                //$custo_mao_obra = 0.093; //0.25 CUSTO DE MÃO DE OBRA POR MINUTO 
                                                while ($dados = $matriz->fetchObject()) {
//                                                $data1 = explode("-", $dados->data_ini);
//                                                $data2 = explode("-", $dados->data_fim);
                                                    $nome = explode(" ", $dados->nome);

                                                    $faltam = $dados->quantidade - $dados->peca_produzida;
                                                    if ($faltam > 0) {
                                                        $faltam = $faltam . '*';
                                                    } else {
                                                        $faltam = '-';
                                                    }

//                                                if ($data2[2] > $data1[2]) {
//                                                    $data2[0] = $data2[0].'*';
//                                                }

                                                    $tempoUni = explode(":", $dados->tempo_operacao);
                                                    $tempo3 = (($tempoUni[0] * 3600) + ($tempoUni[1] * 60) + ($tempoUni[2] ));
                                                    $tempoTotal = ($dados->peca_produzida * $tempo3) / 1;


                                                    $total_segundos1 = $tempoTotal;
                                                    $horas1 = floor($total_segundos1 / (60 * 60));
                                                    $sobra_horas1 = ($total_segundos1 % (60 * 60));
                                                    $minutos1 = floor($sobra_horas1 / 60);
                                                    $sobra_minutos1 = ($sobra_horas1 % 60);
                                                    $segundos1 = $sobra_minutos1;
                                                    $resultadoTempoEstimado = $horas1 . ':' . $minutos1 . ':' . $segundos1;

                                                    $somaTempoHora += $horas1;
                                                    $somaTempoMinu += $minutos1;
                                                    $somaTempoSegu += $segundos1;

                                                    $funcionario = $dados->nome;


                                                    if ($dados->peca_produzida < $dados->quantidade) {
                                                        $dados->peca_produzida = $dados->peca_produzida . '*';
                                                    }

                                                    $tempo_producao2 = explode(":", $resultadoTempoEstimado);
                                                    $calculo0 = ($tempo_producao2[0] * 60) * $custo_mao_obra;
                                                    $calculo1 = $tempo_producao2[1] * $custo_mao_obra;
                                                    $calculo2 = ($tempo_producao2[2] / 60 ) * $custo_mao_obra;

                                                    $media = $calculo0 + $calculo1 + $calculo2;
                                                    echo "<tr>                                                            
                                                            <td title='id'>" . $dados->id . "</td>                                                            
                                                            <td title='Numero de controle'><b>" . $dados->numero . "</b></td>                                                            
                                                            <td title='id_produto' class='editavel'>" . $dados->descricao . "</td>
                                                            <td title='id_operacao' class='editavel'><b>" . $dados->operacao . "</b></td>
                                                            <td title='peca_produzida' class='editavel'>" . $dados->quantidade . "</td>
                                                            <td title='peca_produzida' class='editavel'>" . $dados->peca_produzida . "</td>
                                                            <td title='pecas faltantes' ><b>" . $resultadoTempoEstimado . "</b></td>
                                                            <td title='pecas faltantes' ><b>R$ " . number_format($media, 2, ',', '') . "</b></td>
                                                            <td>
                                                                <a href='#' id='deletar' value='deletar'  onclick='deletar(" . $dados->id . ");'>
                                                                   <i class='fa fa-trash'></i>
                                                                </a>                                                    
                                                            </td>
                                                      </tr>";
                                                    $somarMedia += $media;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover" id="tblEditavel">
                                            <thead>
                                                <tr>
                                                    <th>Tempo Calendario</th>
                                                    <th>Tempo liquido</th>
                                                    <!--<th>¹TCLM</th>-->
                                                    <th>Funcionário</th>
                                                    <th>Período</th>
                                                    <!--<th>Trabalhado no mês</th>-->
                                                    <th>Tempo Trab.</th>
                                                    <th>Total</th>
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
                                                        }
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


                                                    <td>
                                                        <?php echo $funcionario; ?>

                                                    </td>
                                                    <td>
                                                        <?php
                                                        $data1 = explode("-", \filter_input(INPUT_POST, 'data_ini'));
                                                        $data2 = explode("-", \filter_input(INPUT_POST, 'data_fim'));

                                                        echo $data1[2] . '/' . $data1[1] . ' à ' . $data2[2] . '/' . $data2[1] . '/' . $data2[0];
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $total_segundos1 = (($somaTempoHora * 3600) + ($somaTempoMinu * 60) + ($somaTempoSegu));
                                                        $horas1 = floor($total_segundos1 / (60 * 60));
                                                        $sobra_horas1 = ($total_segundos1 % (60 * 60));
                                                        $minutos1 = floor($sobra_horas1 / 60);
                                                        $sobra_minutos1 = ($sobra_horas1 % 60);
                                                        $segundos1 = $sobra_minutos1;
                                                        echo "<span><b> $horas1:$minutos1:$segundos1 <b></span>";
                                                        ?>
                                                    </td>

                                                    
                                                    <td>
                                                        <?php echo 'R$ ' . number_format($somarMedia, 2, ',', '.'); ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php
                                }
                            }
                            ?>



                        </div>
                        <div class="form-group">
                            <p>¹TCLM = Tempo Calendário Liquido Mês</p>
                        </div>
                        <?php
                        /* Captação de dados */
                        $buffer = ob_get_contents(); // Obtém os dados do buffer interno
                        $filename = "code.html"; // Nome do arquivo HTML
                        file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <script src="../ajax/detalhe_funcionario_produto/deletar_detalhe_funcionario_produto.js"></script>
        <script src="../ajax/jquery.js"></script>
        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>
