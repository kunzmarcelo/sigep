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
                                url: "alterarLote.php",
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
                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center;">
                                Listagem
                            </div>
                                            
                                <table width="100%" class="table table-striped table-bordered table-hover" id="tblEditavel">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Lote</th>
                                            <th>Descrição</th>                                        
                                            <th>Data do cadastro</th>                                        
                                            <th><i class="fa fa-low-vision"></i></th>                                        
                                        </tr>
                                    </thead>
                                    <tbody>                       

                                        <?php
                                        include_once "../modell/Lote.class.php";
                                        $lote = new Lote();
                                        $matriz = $lote->listaLote();

                                        while ($dados = $matriz->fetchObject()) {
                                            //echo $dados->data_fabri;                                        
                                            $data1 = explode("-", $dados->data);
//                                        $data2 = explode("-", $dados->data_final);

                                            if ($dados->status == false) {
                                                echo "<tr>
                                                    <td title='id_lote'>" . $dados->id_lote . "</td>
                                                    <td title='numero' >" . $dados->numero . "</td>
                                                    <td title='descricao'>" . $dados->descricao . "</td>
                                                    <td>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>					
                                                    <td>
                                                       <span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_lote . ");'></span>                                                       
                                                    </td>
                                              </tr>";
                                            } else {
                                                echo "<tr>
                                                    <td title='id_lote'>" . $dados->id_lote . "</td>
                                                    <td title='numero' class='editavel'>" . $dados->numero . "</td>
                                                    <td title='descricao' class='editavel'>" . $dados->descricao . "</td>
                                                    <td>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>
                                                        <td>     
							<span class='glyphicon glyphicon-eye-open' id='finalizar' value='finalizar'  onclick='finalizar(" . $dados->id_lote . ");'></span> 													
                                                    </td>
                                                     
                                              </tr>";
                                            }
                                        }//<button title='finalizar lote' type='submit' id='ativar' value='ativar'  onclick='ativar(" . $dados->id . ");' class='btn btn-default'> </button>
                                        ?>
                                    </tbody>
                                </table>
                         
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

        <script src="../ajax/finalizar_lote.js"></script>
        <script src="../ajax/ativa_lote.js"></script>
<?php require_once "./actionRodape.php"; ?>
    </body>
</html>
