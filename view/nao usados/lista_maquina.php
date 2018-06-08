<?php
session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
    include_once "../view/funcoes.php"; {
    include_once '../view/funcoes.php';
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
                                url: "alterarMaquina.php",
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
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Máquinas cadastradas</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel-heading">
                        <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                        <a href="cadastra_maquina.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
                    </div>
                    <div class="form-group">
                        <form action="gerarPDF2.php" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-xs-6 col-sm-4">
                                    <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                </div>
                            </div>
                        </form>
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
                            Lista Máquinas cadastradas
                        </div>
                        <div class="table-responsive">                    
                            <table class="table table-hover" id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th><img src='../bibliotecas/img/1486591262_id.png'></th>
                                        <th>Número</th>
                                        <th>Descrição</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>                                
                                <tbody>                       

                                    <?php
                                    include_once "../modell/Maquina.class.php";
                                    $lote = new Maquina();
                                    $matriz = $lote->listaMaquina();

                                    while ($dados = $matriz->fetchObject()) {
                                        if (empty($dados)) {
                                            echo "<div class='alert alert-info alert-dismissable'>
                                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                    Não possui nenhum registro armazenado.
                                                    </div>";
                                        } else {
                                            if ($dados->status == true) {
                                                echo "<tr>
                                                    <td title='id'>" . $dados->id_maquina . "</td>
                                                    <td title='numero' class='editavel'><b>" . $dados->numero . "</b></td>
                                                    <td title='descricao' class='editavel'>" . $dados->descricao . "</td>
                                                    <td>
                                                        <span class='glyphicon glyphicon-eye-open' id='desativar' value='desativar'  onclick='desativar(" . $dados->id_maquina . ");'></span>
                                                    </td>                                                    
                                                </tr>";
                                            } else {
                                                echo "<tr>
                                                    <td title='id'>" . $dados->id_maquina . "</td>
                                                    <td title='numero' class='editavel'><b>" . $dados->numero . "</b></td>                                                    
                                                    <td title='descricao' class='editavel'>" . $dados->descricao . "</td>
                                                    <td>
                                                        <span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_maquina . ");'></span>                                                    
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
                    <?php
                    /* Captação de dados */
                    $buffer = ob_get_contents(); // Obtém os dados do buffer interno
                    $filename = "code.html"; // Nome do arquivo HTML
                    file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
                    ?>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12">
                            <!--<label for="legenda">*A soma das entradas é o (valor a vista + total de cartão + total de parcelas com cartão + total de parcelas).</label>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../ajax/ativar_maquina.js"></script>
        <script src="../ajax/desativar_maquina.js"></script>

        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <!-- For the demo ad only -->   

        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>