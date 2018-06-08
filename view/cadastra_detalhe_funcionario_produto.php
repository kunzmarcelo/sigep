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
                                                <label for="id_producao" class="tamanho-fonte">Produção Determinada:</label><small> (Campo Obrigatório)</small>
                                                <select name="id_producao" class="form-control" required="required">                                       
                                                    <?php
                                                    echo "<option value=''><b>Selecione ...</b></option>";
                                                    include_once '../modell/Producao.class.php';
                                                    $fun = new Producao();
                                                    $matriz = $fun->listaProducao();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status == TRUE) {

                                                            $cod = $dados->id_producao;
                                                            $nome = $dados->numero;
                                                            $quantidade = $dados->quantidade;
                                                            $produto = $dados->produto;
                                                            echo "<option value=" . $cod . ">" . $nome . ' - ' . $produto . ' - ' . $quantidade . " peças</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>
                                            <div class="form-group"> 
                                                <label for="id_produto" class="tamanho-fonte">Produto:</label><small> (Campo Obrigatório)</small>
                                                <select name="id_produto" id="id_produto" class="form-control" required="required">
                                                    <option value="">Selecione...</option>
                                                    <?php
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
                                            </div>
                                            <div class="form-group"> 
                                                <label for="id_operacao" class="tamanho-fonte">Função:</label><small> (Campo Obrigatório)</small>
                                                <select name="id_operacao" id="id_operacao" class="form-control" required="required">
                                                    <option value="">Selecione um produto...</option>
                                                </select>
                                            </div>

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
                                                <label for="peca_produzida">Quantidade de peças produzidas:</label>
                                                <input type="number" id="peca_produzida" name="peca_produzida" class="form-control" placeholder="numero de peças"  />
                                            </div>
                                            <!--                                            <div class="form-group">
                                                                                            <label for="data_ini">Data de Início da produção:</label>
                                                                                            <input type="date" id="data_ini" name="data_ini" value="<?= date('Y-m-d'); ?>" class="form-control" required="required" />
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="data_fim">Data de fim da produção:</label>
                                                                                            <input type="date" id="data_fim" name="data_fim" value="<?= date('Y-m-d'); ?>" class="form-control" required="required" />
                                                                                        </div>-->

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                            </div>
                                        </form>


                                        <?php
                                        include_once "../modell/DetalheFuncionarioProduto.php";
//instancia a classe de controle
                                        $prodL = new DetalheFuncionarioProduto();


                                        $id_funcionario = \filter_input(INPUT_POST, 'id_funcionario');
                                        $id_produto = \filter_input(INPUT_POST, 'id_produto');
                                        $id_operacao = \filter_input(INPUT_POST, 'id_operacao');
                                        $id_producao = \filter_input(INPUT_POST, 'id_producao');
                                        $peca_produzida = \filter_input(INPUT_POST, 'peca_produzida');
//                                        $data_ini = \filter_input(INPUT_POST, 'data_ini');
//                                        $data_fim = \filter_input(INPUT_POST, 'data_fim');

                                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');
                                        if (isset($cadastro)) {
//                                            if (!isset($_COOKIE[$id_operacao])) {
//                                                echo "Cookie named '" . $id_operacao . "' is not set!";
//                                            } else {
//                                                echo "Cookie '" . $id_operacao . "' is set!<br>";
//                                                echo "Value is: " . $_COOKIE[$id_operacao];
//                                            }





                                            if (empty($id_funcionario) || empty($id_produto) || empty($peca_produzida)) {
                                                echo "<div class='alert alert-danger alert-dismissable'>
                                                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                        Algum dos campos acima não foi preenchido corretamente.
                                                    </div>";
                                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                            } else {
                                                //var_dump($id_lote, $id_produto, $data, $n_peca,$preco,$observacao);
                                                $status = $prodL->cadastraDetalheFuncionarioProduto($id_funcionario, $id_produto, $id_operacao, $id_producao, $peca_produzida);
                                                if ($status == true) {
                                                    echo "<div class='alert alert-info alert-dismissable'>
                                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                            Registro inserido com sucesso
                                                        </div>";

                                                    //echo "<script> alert('Registro inserido com sucesso.');</script>";
                                                } else {
                                                    echo "<div class='alert alert-danger alert-dismissable'>
                                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                            Erro ao inserir o registro.
                                                        </div>";
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<?php require_once "./actionRodape.php"; ?>

        <script type="text/javascript">
            $(function () {
                $('#id_produto').change(function () {
                    if ($(this).val()) {
                        $('#id_operacao').hide();
                        $('.carregando').show();
                        $.getJSON('operacao.ajax.php?search=', {id_produto: $(this).val(), ajax: 'true'}, function (j) {
                            var options = '<option value="1">Selecione ...</option>';
                            for (var i = 0; i < j.length; i++) {
                                if (j[i].operacao != null) {
                                    options += '<option value="' + j[i].id_operacao + '">' + j[i].operacao + '</option>';
                                }
                            }
                            $('#id_operacao').html(options).show();
                            $('.carregando').hide();
                        });
                    } else {
                        $('#id_operacao').html('<option value="">– Selecione... –</option>');
                    }
                });
            });
        </script>

    </body>
</html>