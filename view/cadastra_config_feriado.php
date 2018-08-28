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
                                // url: "alterarLote.php",
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
                            <div class="panel-heading" style="text-align: center">
                                Fomulário de cadastro
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" role="form">
                                            <div class="form-group">
                                                <label for="descricao">Descrição:</label>
                                                <input type="text" id="descricao" name="descricao" class="form-control" required="required" placeholder="descrição"/>
                                            </div>                                    
                                            <div class="form-group">
                                                <label for="data">Data:</label>
                                                <input type="date" id="data" name="data" class="form-control" required="required" />
                                            </div>
<!--                                            <div class="form-group">
                                                <label for="numero">Numero de dias:</label>
                                                <select class="form-control" name="numero" required="required">
                                                    <option value="">Selecione...</option>
                                                    <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        //echo "<option value='$i'>$i dia(s)</option>";
                                                    }
                                                    ?>
                                                </select>


                                                <input type="number" id="numero" name="numero" class="form-control" required="required" value="1" min="1" max="5"/>
                                            </div>                                    -->
                                            <div class="form-group">
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                <?php
                                include_once "../modell/ConfigFeriado.class.php";

//instancia a classe de controle
                                $prod = new ConfigFeriado();

                                $descricao = \filter_input(INPUT_POST, 'descricao');
                                $numero = 1;
                                $data = \filter_input(INPUT_POST, 'data');

                                $cadastro = \filter_input(INPUT_POST, 'cadastrar');


                                if (isset($cadastro)) {
                                    if (empty($descricao)) {
                                        echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                        //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                    } else {
                                        $status = $prod->cadastraConfigFeriado($descricao, $data, $numero);
                                        if ($status == true) {
                                            echo "<div class='alert alert-info alert-dismissable'>
                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                Registro inserido com sucesso.
                                            </div>";
                                        } else {
                                            echo "<div class='alert alert-danger alert-dismissable'>
                                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                    Erro ao inserir o resgistro.
                                                </div>";
                                        }
                                    }
                                }
                                ?>                       
                            </div>
                        </div>
                    </div>
                </div>





                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center;">
                                Listagem
                            </div>

                            <table width="100%" class="table table-stripedtable-bordered table-hover" id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Data</th>                                        
                                        <th>Descrição</th>                                        
                                        <th>Dias</th>
                                    <th><i class="fa fa-trash"></i></th>
                                    </tr>
                                </thead>
                                <tbody>                       

                                    <?php
                                    include_once "../modell/ConfigFeriado.class.php";
                                    $lote = new ConfigFeriado();
                                    $matriz = $lote->listaConfigFeriado();

                                    while ($dados = $matriz->fetchObject()) {
                                        //echo $dados->data_fabri;                                        
                                        $data1 = explode("-", $dados->data);
//                                        $data2 = explode("-", $dados->data_final);


                                        echo "<tr>
                                                    <td title='id_lote'>" . $dados->id_config_feriado . "</td>
                                                    <td>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>
                                                    <td title='descricao' class='editavel'>" . $dados->descricao . "</td>
                                                    <td title='numero' class='editavel'>" . $dados->numero . " dia(s)</td>
                                                       <td>    
							<span class='glyphicon glyphicon-trash' id='deletar' value='deletar'  onclick='deletar(" . $dados->id_config_feriado . ");'></span> 													
                                                    </td>                                                     
                                              </tr>";
                                    }//<button title='finalizar lote' type='submit' id='ativar' value='ativar'  onclick='ativar(" . $dados->id . ");' class='btn btn-default'> </button>
                                    ?>
                                </tbody>
                            </table>

                        </div>                        
                    </div>
                </div>
            </div>
        </div>
        <script src="../ajax/config_feriado/deletar_feriado.js"></script>
        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>
