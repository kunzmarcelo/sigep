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
                                url: "alterarConfig.php",
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
            <?php require_once './actionfonteMenu.php'; ?>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Configurações de metas anual</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-12">                    
                    <div class="panel-heading">
                        <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                        <!--<a href="cadastra_config.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>-->
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                            <span class='glyphicon glyphicon-plus'></span> Adicionar
                        </button>
                    </div>
                    <div class="panel panel-primary">            
                        <div class="panel-heading" style="text-align: center;">
                            Configurações de metas anual
                        </div>
                        <div class="table-responsive">                    
                            <table class="table table-hover" id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th><img src='../bibliotecas/img/1486591262_id.png'></th>
                                        <th>Faturamento Mês</th>
                                        <th>Produção Mês</th>
                                        <th>Data Ini</th>
                                        <th>Data Fim</th>
                                        <th><span class="glyphicon glyphicon-info-sign" title="Visualizar lote"></span></th>                                        
                                    </tr>
                                </thead>
                                <tbody>                       

                                    <?php
                                    include_once "../modell/Config.class.php";
                                    $lote = new Config();
                                    $matriz = $lote->listaConfig();

                                    while ($dados = $matriz->fetchObject()) {
                                        //echo $dados->data_fabri;                                        
                                        $data1 = explode("-", $dados->data_ini);
                                        $data2 = explode("-", $dados->data_fim);
//                                        $data2 = explode("-", $dados->data_final);

                                        if ($dados->status == true) {
                                            echo "<tr>
                                                    <td title='id_config'>" . $dados->id_config . "</td>
                                                    <td title='meta_faturamento' class='editavel'>" . number_format($dados->meta_faturamento, 2, ',', '.') . "</td>
                                                    <td title='meta_producao' class='editavel'>" . number_format($dados->meta_producao, 0, ',', '.') . "</td>
                                                    <td title='data_ini' class='editavel'>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>
                                                    <td title='data_fim' class='editavel'>" . $data2[2] . '/' . $data2[1] . '/' . $data2[0] . "</b></td>                                                    
                                                    <td>     
							<span class='glyphicon glyphicon-eye-open' id='finalizar' value='finalizar'  onclick='finalizar(" . $dados->id_config . ");'></span> 													
                                                    </td>
                                              </tr>";
                                        } else {
                                            echo "<tr>
                                                    <td title='id_config'>" . $dados->id_config . "</td>
                                                    <td title='meta_faturamento' class='editavel'>" . number_format($dados->meta_faturamento, 2, ',', '.') . "</td>
                                                    <td title='meta_producao' class='editavel'>" . number_format($dados->meta_producao, 0, ',', '.') . "</td>
                                                   <td title='data_ini' class='editavel'>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>
                                                    <td title='data_fim' class='editavel'>" . $data2[2] . '/' . $data2[1] . '/' . $data2[0] . "</b></td>                                                 
                                                    <td>     
							<span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_config . ");'></span> 													
                                                    </td>
                                              </tr>";
                                        }
                                    }//<button title='finalizar lote' type='submit' id='ativar' value='ativar'  onclick='ativar(" . $dados->id . ");' class='btn btn-default'> </button>
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!--</div>-->
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12">
                            <!--<label for="legenda">*A soma das entradas é o (valor a vista + total de cartão + total de parcelas com cartão + total de parcelas).</label>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Configurações de metas anual</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="form-horizontal">                                       
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="meta_faturamento">Meta de faturamento:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">R$</span>
                                        <input type="number" id="meta_faturamento" name="meta_faturamento" class="form-control" required="required"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="meta_producao">Meta de produção:</label>
                                    <input type="number" id="meta_producao" name="meta_producao" class="form-control"  required="required"  />
                                </div>                                    
                                <div class="form-group">
                                    <label for="data_ini">Data inicial:</label>
                                    <input type="date" id="data_ini" name="data_ini" class="form-control"  required="required"   />
                                </div>                                    
                                <div class="form-group">
                                    <label for="data_fim">Data Final:</label>
                                    <input type="date" id="data_fim" name="data_fim" class="form-control"  required="required"   />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                </div>
                            </div>
                        </form>

                        <?php
                        include_once "../modell/Config.class.php";

//instancia a classe de controle
                        $prod = new Config();

                        $meta_faturamento = \filter_input(INPUT_POST, 'meta_faturamento');
                        $meta_producao = \filter_input(INPUT_POST, 'meta_producao');
                        $data_ini = \filter_input(INPUT_POST, 'data_ini');
                        $data_fim = \filter_input(INPUT_POST, 'data_fim');
                        $hora_ini = \filter_input(INPUT_POST, 'hora_ini');
                        $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                        $hora_des = \filter_input(INPUT_POST, 'hora_des');
                        $desconto = \filter_input(INPUT_POST, 'desconto');
                        $status = TRUE;
                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                        if (isset($cadastro)) {
                            if (empty($meta_faturamento)) {
                                echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                            } else {
                                $status = $prod->cadastraConfig($meta_faturamento, $meta_producao, $data_ini, $data_fim, $hora_ini, $hora_fim, $hora_des, $status, $desconto);
                                if ($status == true) {
                                    echo "<script> alert('Registro inserido com sucesso.');</script>";
                                } else {
                                    echo "<script> alert('Erro ao inserir registro.');</script>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>


        <script src="../ajax/config.js"></script>

<!--<script src="../ajax/jquery.js"></script>-->
        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <!-- For the demo ad only -->   

        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>
