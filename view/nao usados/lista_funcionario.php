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
                                url: "alterarFuncionario.php",
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
                        <h1 class="page-header">Funcionários Cadastrados</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->


                <div class="row">
                    <div class="col-lg-12">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
                                <form action="gerarPDF.php" class="form-horizontal">
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
                                Lista de funcinários cadastrados
                            </div>                 
                            <table class="table table-hover" id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th title="produto"><img src='../bibliotecas/img/1486591262_id.png'></th>
                                        <!--<th></th>-->
                                        <th>Nome</th>
                                        <th>Departamento</th>
                                        <th>Status</th>                                       
                                    </tr>
                                </thead>
                                <tbody>                       

                                    <?php
                                    include_once "../modell/Funcionario.class.php";
                                    $lote = new Funcionario();
                                    $matriz = $lote->listaFuncionario();
                                    if (empty($matriz)) {
                                        echo "<div class='alert alert-info alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Não possui nenhum registro armazenado.
                                            </div>";
                                    } else {
                                        while ($dados = $matriz->fetchObject()) {
                                            if ($dados->ativo == true) {
                                                
                                                echo "<tr>
                                                    <td title='id'>" . $dados->id_funcionario . "</td>
                                                    <td title='nome' class='editavel'>" . $dados->nome . "</td>
                                                    <td title='departamento' class='editavel'>" . $dados->departamento . "</td>
                                                        <td>     
							<span class='glyphicon glyphicon-eye-open' id='desativar' value='desativar'  onclick='desativar(" . $dados->id_funcionario . ");'></span> 													
                                                    </td>
                                                </tr>";
                                            } else {
                                                
                                                echo "<tr>
                                                    <td title='id'>" . $dados->id_funcionario . "</td>
                                                    <td title='nome' class='editavel'>" . $dados->nome . "</td>
                                                    <td title='departamento' class='editavel'>" . $dados->departamento . "</td>
                                                    <td>     
							<span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_funcionario . ");'></span> 													
                                                    </td>
                                                </tr>";
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!--</div>-->
                    </div>                    
                </div>
            </div>
        </div>
        <script src="../ajax/desativar_funcionario.js"></script>
        <script src="../ajax/jquery.js"></script>

        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>
