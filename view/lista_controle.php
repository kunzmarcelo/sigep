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
                                url: "alterarControle.php",
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


                <div class="panel panel-default">            
                    <div class="panel-heading" style="text-align: center;">
                        Listagem
                    </div>
                    <div class="table-responsive">                       
                        <table class="table table-hover" id="tblEditavel">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>N° de controle</th>
                                    <th>Produto</th>
                                    <th>N° peças</th>
                                    <th colspan="2">Início</th>
                                    <th colspan="2">Fim</th>
                                    <th>Intervalo</th>
                                    <th>Desconto</th>
                                    <th><i class="fa fa-trash-o"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include_once "../modell/Producao.class.php";

//instancia a classe de controle
                                $prod = new Producao();
                                $matriz = $prod->listaProducao();
                                while ($dados = $matriz->fetchObject()) {

                                    $data_ini = explode("-", $dados->data_ini);
                                    $data_fim = explode("-", $dados->data_fim);
                                    $intervalo = strtotime($dados->data_fim) - (strtotime($dados->data_ini));
                                    $dias = (int) floor($intervalo / (60 * 60 * 24));

                                    if ($dados->desconto > 0) {
                                        $dias = $dias - $dados->desconto;
                                    }

                                    if ($dados->desconto == 1) {
                                        $desconto = '<b>' . $dados->desconto . ' dia</b>';
                                    } else {
                                        $desconto = '<b>' . $dados->desconto . ' dias</b>';
                                    }


                                    if ($dias == 1) {
                                        $dias = '<b>' . $dias . ' dia</b>';
                                    } else {
                                        $dias = '<b>' . $dias . ' dias</b>';
                                    }

                                    $tempoUniA = explode(":", $dados->hora_ini);
                                    $horaA = (($tempoUniA[0] * 3600) + ($tempoUniA[1] * 60) + ($tempoUniA[2] ));
                                    $tempoUniB = explode(":", $dados->hora_fim);
                                    $horaB = (($tempoUniB[0] * 3600) + ($tempoUniB[1] * 60) + ($tempoUniB[2] ));


                                    $total_segundos1 = $horaB - $horaA;
                                    $horas1 = floor($total_segundos1 / (60 * 60));
                                    $sobra_horas1 = ($total_segundos1 % (60 * 60));
                                    $minutos1 = floor($sobra_horas1 / 60);
                                    $sobra_minutos1 = ($sobra_horas1 % 60);
                                    $segundos1 = $sobra_minutos1;
                                    $resultadoTempoEstimado = $horas1 . 'h ' . $minutos1 . 'm ' . $segundos1 . 's';
// . $dados->hora_ini . ' '
// . $dados->hora_fim . ' '

                                    if ($dados->status == TRUE) {
                                        echo "<tr>
                                                <td title='id_producao'>" . $dados->id_producao . "</td>
                                                <td title='descricao'>" . $dados->numero . "</td>
                                                <td title='produto' class='editavel'>" . $dados->produto . "</td>
                                                <td title='quantidade' class='editavel'>" . $dados->quantidade . "</td>
                                                <td title='data_ini' class='editavel' colspan='2'>" . $data_ini[2] . '/' . $data_ini[1] . '/' . $data_ini[0] . ' ' . $dados->hora_ini . "</td>
                                                <td title='data_fim' class='editavel' colspan='2'>" . $data_fim[2] . '/' . $data_fim[1] . '/' . $data_fim[0] . ' ' . $dados->hora_fim . "</td>
                                                <td title='' >" . $dias . ' ' . $resultadoTempoEstimado . "</td>
                                                <td title='' >" . $desconto . "</td>
                                                    <td>     
							<span class='glyphicon glyphicon-eye-open' id='finalizar' value='finalizar'  onclick='finalizar(" . $dados->id_producao . ");'></span> 													
                                                    </td>
                                                <td>
                                                     <a href='#' id='deletar' value='deletar'  onclick='deletar(" . $dados->id_producao . ");'>
                                                        <i class='fa fa-trash'></i>
                                                     </a>                                                    
                                                 </td>
                                            </tr>";
                                    } else {
                                        echo "<tr>
                                                <td title='id_producao'>" . $dados->id_producao . "</td>
                                                <td title='descricao'>" . $dados->numero . "</td>
                                                <td title='produto' class='editavel'>" . $dados->produto . "</td>
                                                <td title='quantidade' class='editavel'>" . $dados->quantidade . "</td>
                                                <td title='hora_ini' class='editavel' colspan='2'>" . $data_ini[2] . '/' . $data_ini[1] . '/' . $data_ini[0] . ' ' . $dados->hora_ini . "</td>
                                                <td title='hora_fim' class='editavel' colspan='2'>" . $data_fim[2] . '/' . $data_fim[1] . '/' . $data_fim[0] . ' ' . $dados->hora_fim . "</td>
                                                <td title='' >" . $dias . ' ' . $resultadoTempoEstimado . "</td>
                                                <td title='' >" . $desconto . "</td>
                                                     <td>     
							<span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_producao . ");'></span> 													
                                                    </td>
                                                <td>
                                                     <a href='#' id='deletar' value='deletar'  onclick='deletar(" . $dados->id_producao . ");'>
                                                        <i class='fa fa-trash'></i>
                                                     </a>                                                    
                                                 </td>
                                            </tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>               
            </div>
        </div>
        <script src="../ajax/detalhe_funcionario_produto/deletar_producao.js"></script>
        <script src="../ajax/detalhe_funcionario_produto/ativa_producao.js"></script>
        <script src="../ajax/detalhe_funcionario_produto/desativa_producao.js"></script>

<?php require_once "./actionRodape.php"; ?>
    </body>
</html>
