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
                                url: "alterarCelula.php",
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
                            <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#anotacoes">
                                <span class='glyphicon glyphicon-list-alt'></span> Anotações
                            </button>
                        </div>

                        <div class="panel-heading">
                            <form class="form-horizontal" method="post">                                
                                <div class="form-group">                                
                                    <div class="col-xs-6">
                                        <label for="data">Data inicial:</label>
                                        <input type="date" name="data" value="" class="form-control" required="required" >
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-xs-6">
                                        <label for="id_celula" class="tamanho-fonte">Pessoas por célula de trabalho:</label><small> (Campo Obrigatório)</small>
                                        <select name="id_celula" class="form-control" required="required" onchange="this.form.submit()">                                       
                                            <?php
                                            echo "<option value=''>Selecione ...</option>";
                                            include_once '../modell/CelulaTrabalho.class.php';
                                            $lote = new CelulaTrabalho();
                                            $matriz = $lote->listaCelula();

                                            while ($dados = $matriz->fetchObject()) {
                                                if ($dados->status_celula == TRUE) {
                                                    $cod = $dados->id_celula;
                                                    $pessoas_celula = $dados->pessoas_celula;
                                                    $funcionarios = $dados->funcionarios;
                                                    echo "<option value=" . $cod . ">" . $pessoas_celula . ' - ' . $funcionarios . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                            </form>
                        </div>


                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center;">
                               Lstagem
                            </div>
                            <div class="table-responsive">                    
                                <table class="table table-hover" id="tblEditavel">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Descrição</th>                                
                                            <th>Data do Ocorrido</th>                                
                                        </tr>
                                    </thead>
                                    <tbody>                       

                                        <?php
                                        $data_ini = \filter_input(INPUT_POST, 'data');
                                        $id_celula = \filter_input(INPUT_POST, 'id_celula');
                                        if (empty($id_celula) || empty($data_ini)) {
                                            echo" <div class='alert alert-warning' role='alert'>
                                               <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Selecione todos campos acima.</h4>
                                            </div>";
                                        } else {
                                            include_once '../modell/Anotacao.class.php';
                                            $newAnotacao = new Anotacao();
                                            $matriz3 = $newAnotacao->listaAnotacao($id_celula, $data_ini);

                                            while ($dados = $matriz3->fetchObject()) {
                                                $data1 = explode("-", $dados->data);
                                                if ($dados->status == true) {
                                                    echo "<tr>
                                                    <td title='id'>" . $dados->id_anotacao . "</td>                                           
                                                    <td title='id' >" . $dados->descricao . "</td>
                                                    <td title='data' class='editavel'>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>

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
        </div>

        <!-- MODAL DE ANOTAÇÕES -->
        <div class="modal fade" id="anotacoes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Cadastro de Anotações</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="form-horizontal">
                            <div class="panel-body">                                                
                                <div class="form-group">
                                    <label for="data">Data do ocorrido:</label>
                                    <input type="date" id="data" name="data" class="form-control"  required="required"   />
                                </div>                                    
                                <div class="form-group">
                                    <label for="id_celula" class="tamanho-fonte">Célula de Trabalho:</label><small> (Campo Obrigatório)</small>
                                    <select name="id_celula" class="form-control" required="required" >                                       
                                        <?php
                                        echo "<option value=''>Selecione ...</option>";
                                        include_once '../modell/CelulaTrabalho.class.php';
                                        $lote = new CelulaTrabalho();
                                        $matriz = $lote->listaCelula();

                                        while ($dados = $matriz->fetchObject()) {
                                            if ($dados->status_celula == TRUE) {
                                                $id_celula = $dados->id_celula;
                                                $pessoas_celula = $dados->pessoas_celula;
                                                $funcionarios = $dados->funcionarios;
                                                echo "<option value=" . $id_celula . ">" . $pessoas_celula . ' - ' . $funcionarios . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>                            
                                </div>                               
                                <div class="form-group">
                                    <label for="descricao">Descrição da obeservação:</label>
                                    <textarea id="descricao" name="descricao" class="form-control" required="required" maxlength="250" rows="5"> </textarea>
                                    <div id="characterLeft"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>                 
                                </div>
                            </div>
                        </form>

                        <?php
                        include_once "../modell/Anotacao.class.php";

//instancia a classe de controle
                        $prod = new Anotacao();

                        $descricao = \filter_input(INPUT_POST, 'descricao');
                        $data = \filter_input(INPUT_POST, 'data');
                        $id_celula = \filter_input(INPUT_POST, 'id_celula');
                        $status = TRUE;
                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                        if (isset($cadastro)) {
                            if (empty($descricao)) {
                                echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                            } else {
                                $status = $prod->cadastraAnotacao($descricao, $data, $id_celula, $status);
                                if ($status == true) {
                                    echo "<script> alert('Anotação inserida com sucesso.');</script>";
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

<?php require_once "./actionRodape.php"; ?>


    </body>
</html>
