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
                    <div class="col-lg-12">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
                                <form action="gerarPDF2.php" class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-xs-6 col-sm-4">
                                            <button type="submit" class="btn btn-default">Exportar para PDF</button>
                                            <!--<input type="submit" value="Salvar em PDF" class="btn btn-default">-->
                                        </div>
                                    </div>
                                </form>
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
                            <div class="panel-heading" style="text-align: center">
                                Listagem
                            </div>
                            <table width="100%" class="table table-striped table-bordered table-hover"  id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th>#</th>                                            
                                        <th>Operação</th>
                                        <th>Tempo de ciclo</th>
                                        <th>¹Setores</th>
                                        <th>R$ minuto</th>
                                        <th>Média</th>
                                        <th><i class='fa fa-low-vision'></i></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>                                            
                                        <th>Operação</th>
                                        <th>Tempo de ciclo</th>
                                        <th>¹Setores</th>
                                        <th>R$ minuto</th>
                                        <th>Média</th>
                                        <th><i class='fa fa-low-vision'></i></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    include_once "../modell/Operacao.class.php";
                                    $lote = new Operacao();
                                    $matriz = $lote->listaOperacao();
//$matriz = $lote->listaTdosFuncaoLigacao();
                                    $somaTempo = 0;
                                    $media_final = 0;
                                    $total_funcoes = 0;
                                    $custo_mao_obra = 0.25;

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
                                                    <td title='custo_operacao' class='editavel'>R$ " . number_format($dados->custo_operacao, 2, ',', '') . "</td>
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
                                                    <td title='custo_operacao' class='editavel'>R$ " . number_format($dados->custo_operacao, 2, ',', '') . "</td>
                                                    <td title='custo médio'>R$ " . number_format($media, 2, ',', '') . "</td>
                                                    <td>
                                                       <span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_operacao . ");'></span>
                                                    </td>
                                                </tr>";
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>                                                        
                        </div>
                        <!--</div>-->
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

        <script src="../ajax/operacao/finalizar_operacao.js"></script>      
        <script src="../ajax/operacao/ativa_operacao.js"></script>
<?php require_once "./actionRodape.php"; ?>

    </body>
</html>