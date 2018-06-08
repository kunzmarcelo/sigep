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
                                url: "alterarOperacao.php",
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
                    <div class="col-lg-6">
                        <div class="form-group">
                            <form role="form" method="post">                            
                                <label for="id_produto" class="tamanho-fonte">Produto:</label><small> (Campo Obrigatório)</small>
                                <select name="id_produto" id="id_produto" class="form-control" required="required" onchange="this.form.submit()">                                       
                                    <?php
                                    echo "<option value=''>Selecione ...</option>";
                                    include_once '../modell/Produto.class.php';
                                    $fun = new Produto();
                                    $matriz = $fun->listaProduto();

                                    while ($dados = $matriz->fetchObject()) {
                                        if ($dados->status == true) {
                                            $cod = $dados->id_produto;
                                            $nome = $dados->descricao;
                                            echo "<option value=" . $cod . ">" . $nome . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </form>
                        </div>                              

                        <div class="col-lg-6">
                            <div class="form-group">
                                <form action="gerarPDF.php" class="form-horizontal">
                                    <button type="submit" class="btn btn-default">Exportar para PDF</button>
                                    <!--<input type="submit" value="Salvar em PDF" class="btn btn-default">-->
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


                <div class="panel panel-default">
                    <div class="table-responsive">
                        <div class="panel-heading" style="text-align: center">
                            Listagem
                        </div>
                        <table width="100%" class="table table-striped table-bordered table-hover" id="tblEditavel">
                            <thead>
                                <tr>
                                    <th>#</th>                                            
                                    <th>Operação</th>
                                    <th>Tempo de ciclo</th>
                                    <th>¹Setores</th>                               
                                    <th>Valor</th>
                                    <th><i class='fa fa-low-vision'></i></th>
                                </tr>
                            </thead>                                    
                            <tbody>
                                <?php
                                $id_produto = \filter_input(INPUT_POST, 'id_produto');
//                                    $id_produto = \filter_input(INPUT_POST, 'id_produto');
//                                    $enviar = \filter_input(INPUT_POST, 'enviar');
//
//                                    if (isset($enviar)) {
                                //$id_produto = \filter_input(INPUT_POST, 'id_produto');
                                if (empty($id_produto)) {
                                    echo" <div class='alert alert-warning' role='alert'>
                                               <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Selecione um produto.</h4>
                                            </div>";
                                } else {
                                    include_once "../modell/Operacao.class.php";
                                    $lote = new Operacao();
                                    $matriz = $lote->listaOperacaoProduto($id_produto);
//$matriz = $lote->listaTdosFuncaoLigacao();
                                    $somaTempo = 0;
                                    $media_final = 0;
                                    $total_funcoes = 0;
                                    $custo_mao_obra = 0.25; //0.25 CUSTO DE MÃO DE OBRA POR MINUTO 
                                    $setor = 0;

                                    while ($dados = $matriz->fetchObject()) {
                                        $tempo_producao2 = explode(":", $dados->tempo_operacao);
                                        $calculo0 = ($tempo_producao2[0] * 60) * $custo_mao_obra;
                                        $calculo1 = $tempo_producao2[1] * $custo_mao_obra;
                                        $calculo2 = ($tempo_producao2[2] / 60 ) * $custo_mao_obra;

                                        $media = $calculo0 + $calculo1 + $calculo2;


                                        if ($dados->setor_operacao == 'Inicio') {
                                            $setor = "<span class='label label-default'>Início</span>";
                                        } elseif ($dados->setor_operacao == 'Costura') {
                                            $setor = "<span class='label label-info'>Costura</span>";
                                        } elseif ($dados->setor_operacao == 'Acabamento') {
                                            $setor = "<span class='label label-primary'>Acabamento</span>";
                                        } elseif ($dados->setor_operacao == 'Finalização') {
                                            $setor = "<span class='label label-success'>Finalização</span>";
                                        }

                                        if ($dados->status_operacao == true) {
                                            echo "<tr>
                                                    <td title='id_operacao'>" . $dados->id_operacao . "</td>                                                    
                                                    <td title='operacao' class='editavel'><b> " . $dados->operacao . "</b></td>
                                                    <td title='tempo_operacao' class='editavel'> " . $dados->tempo_operacao . "</td>
                                                    <td title='setor_operacao' class='editavel'> " . $setor . "</td>
                                                    <td title='custo médio'>R$ " . number_format($media, 2, ',', '') . "</td>
                                                    <td>
							<span class='glyphicon glyphicon-eye-open' id='finalizar' value='finalizar'  onclick='finalizar(" . $dados->id_operacao . ");'></span>
                                                    </td>
                                                </tr>";
                                        } else {
                                            echo "<tr>
                                                    <td title='id_operacao'>" . $dados->id_operacao . "</td>                                                    
                                                    <td title='operacao' class='editavel'><b> " . $dados->operacao . "</b></td>
                                                    <td title='tempo_operacao' class='editavel'> " . $dados->tempo_operacao . "</td>
                                                    <td title='setor_operacao' class='editavel'> " . $setor . "</td>
                                                    <td title='custo médio'>R$ " . number_format($media, 2, ',', '') . "</td>
                                                    <td>
                                                       <span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_operacao . ");'></span>
                                                    </td>
                                                </tr>";
                                        }
                                        if ($dados->status_operacao == true) {
                                            $tempo_funcao = \explode(":", $dados->tempo_operacao);
                                            $horas1 = $tempo_funcao[0] * 3600;
                                            $horas2 = $tempo_funcao[1] * 60;
                                            $horas3 = $tempo_funcao[2];


                                            $somaTempo += $horas1 + $horas2 + $horas3;
                                            $produto = $dados->descricao;
                                            $id_produto = $dados->id_produto;
                                            $media_final += $media;
                                            $total_funcoes += $dados->custo_operacao;
                                        }
                                    }

                                    $total_segundos = $somaTempo;


                                    $horas = floor($total_segundos / (60 * 60));
                                    $sobra_horas = ($total_segundos % (60 * 60));
                                    $minutos = floor($sobra_horas / 60);
                                    $sobra_minutos = ($sobra_horas % 60);
                                    $segundos = $sobra_minutos;


                                    // CALCULO DE VARIAÇÃO DE TEMPO PARA MAIS E PARA MENOS
                                    $porcentagem = 10; //>0%
                                    $total_segundos1 = $total_segundos + (($porcentagem / 100) * $total_segundos);
                                    $horas1 = floor($total_segundos1 / (60 * 60));
                                    $sobra_horas1 = ($total_segundos1 % (60 * 60));
                                    $minutos1 = floor($sobra_horas1 / 60);
                                    $sobra_minutos1 = ($sobra_horas1 % 60);
                                    $segundos1 = $sobra_minutos1;
                                    $resultadoTempoEstimadoParaMais = $horas1 . ' hora(s), ' .  $minutos1 . ' minuto(s) e ' .  $segundos1.' segundo(s)';
                                    
                                    
                                   
                                    $total_segundosMenos = $total_segundos - (($porcentagem / 100) * $total_segundos);
                                    $horasMenos = floor($total_segundosMenos / (60 * 60));
                                    $sobra_horasMenos = ($total_segundosMenos % (60 * 60));
                                    $minutosMenos = floor($sobra_horasMenos / 60);
                                    $sobra_minutosMenos = ($sobra_horasMenos % 60);
                                    $segundosMenos = $sobra_minutosMenos;
                                    $resultadoTempoEstimadoParaMenos = $horasMenos . ' hora(s), ' . $minutosMenos . ' minuto(s) e ' . $segundosMenos.' segundo(s)';
                                    ?>

                                </tbody>
                            </table>

                                    <?php if (!empty($produto)) { ?>
                        <hr>
                                <table class="table table-hover">
                                    <thead style="text-align: right">
                                        <tr>
                                            <th>#</th>
                                            <th>Produto</th>                                            
                                            <th>Soma do tempo de ciclo</th>
                                            <th>Mais 10%</th>
                                            <th>Menos 10%</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>                                    
                                    <tbody>
                                        <tr>
                                            <td><?= $id_produto?></td>
                                            <td><?= $produto ?></td>
                                            <td><b><i><?= $horas . ' hora(s), ' . $minutos . ' minuto(s) e ' . $segundos . ' segundo(s)'; ?></i></b></td>
                                            <td title="variação para mais"><?= $resultadoTempoEstimadoParaMais; ?></td>
                                            <td title="variação para menos"><?= $resultadoTempoEstimadoParaMenos; ?></td>
                                            <td>R$ <?= number_format($media_final, 2, ',', ''); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
        <?php
    }
}
/* Captação de dados */
$buffer = ob_get_contents(); // Obtém os dados do buffer interno
$filename = "code.html"; // Nome do arquivo HTML
file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
?>
                        <table class="table table-hover">
                            <thead>
                                <tr>                                            
                                    <th colspan="5" style="text-align: center;">Opções</th>
                                </tr>
                            </thead>                                    
                            <tbody>
                                <tr>
                                    <td>
                                        <button type='submit' value='fechar_todos' class='btn btn-default'  onclick='fechar_todos("<?= $id_produto ?>")'>Ocultar Todos</button>
                                    </td> 
                                    <td>
                                        <button type='submit' value='mostrar_acabamentos' class='btn btn-primary'  onclick='mostrar_acabamentos("<?= $id_produto ?>")'>Somar Acabamento</button>
                                    </td>
                                    <td>
                                        <button type='submit' value='mostrar_costura' class='btn btn-info'  onclick='mostrar_costura("<?= $id_produto ?>")'>Somar Costura</button>
                                    </td>
                                    <td>
                                        <button type='submit' value='mostrar_finalizacao' class='btn btn-success'  onclick='mostrar_finalizacao("<?= $id_produto ?>")'>Somar Finalização</button>
                                    </td>
                                    <td>
                                        <button type='submit' value='mostrar_inicio' class='btn btn-github'  onclick='mostrar_inicio("<?= $id_produto ?>")'>Somar Início</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <script src="../ajax/operacao/finalizar_operacao.js"></script>
        <script src="../ajax/operacao/fechar_todos.js"></script>
        <script src="../ajax/operacao/ativa_operacao.js"></script>
        <script src="../ajax/operacao/mostrar_todos.js"></script>
        <script src="../ajax/operacao/mostrar_inicio.js"></script>
        <script src="../ajax/operacao/mostrar_finalizacao.js"></script>
        <script src="../ajax/operacao/mostrar_acabamentos.js"></script>
        <script src="../ajax/operacao/mostrar_costura.js"></script>
<?php require_once "./actionRodape.php"; ?>
    </body>
</html>