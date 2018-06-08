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
                                            <th>Funcionário</th>
                                            <th>Produto</th>
                                            <th>Operação</th>                                        
<!--                                            <th>Inicio</th>
                                            <th>Fim</th>-->
                                            <th>Det.</th>
                                            <th>Prod.</th> 
                                            <th>Falt</th> 
                                            <th><i class="fa fa-trash-o"></i></th>
                                        </tr>
                                    </thead>
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
                                                    if ($dados->peca_produzida < $dados->quantidade) {
                                                        $dados->peca_produzida = $dados->peca_produzida . '*';
                                                    }

                                                    echo "<tr>                                                            
                                                        <td title='id'>" . $dados->id . "</td>                                                            
                                                        <td title='Numero de controle'><b>" . $dados->numero . "</b></td>                                                            
                                                        <td title='id_funcionario' class='editavel'><b>" . substr("$dados->nome", 0, 10) . "</b></td>                                                            
                                                        <td title='id_produto' class='editavel'>" . $dados->descricao . "</td>
                                                        <td title='id_operacao' class='editavel'><b>" . $dados->operacao . "</b></td>
                                                        <td title='quantidade determinada' >" . $dados->quantidade . "</td>                                                            
                                                        <td title='peca_produzida' class='editavel'>" . $dados->peca_produzida . "</td>
                                                        <td title='pecas faltantes' ><b>" . $faltam . "</b></td>
                                                        <td>
                                                            <a href='#' id='deletar' value='deletar'  onclick='deletar(" . $dados->id . ");'>
                                                               <i class='fa fa-trash'></i>
                                                            </a>                                                    
                                                        </td>
                                                      </tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php
                                }
                            }
                            ?>


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
        </div>

        <!--<script src="../ajax/detalhe_funcionario_produto/deletar_detalhe_funcionario_produto.js"></script>-->
        <script src="../ajax/jquery.js"></script>
<?php require_once "./actionRodape.php"; ?>
    </body>
</html>
