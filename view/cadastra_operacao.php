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
                                    <form method="post" role="form">
                                        <div class="col-lg-6">
                                            <div class="form-group"> 
                                                <label for="id_produto" class="tamanho-fonte">Produto:</label><small> (Campo Obrigatório)</small>

                                                <select name="id_produto" class="form-control" required="required" >                                       
                                                    <?php
                                                    echo "<option value=''>Selecione</option>";
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
                                                <label for="funcao">Informe a Operação:</label>
                                                <input type="text" id="funcao" name="funcao" class="form-control" placeholder="Ex: Acabamento" required="required"  />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="tempo">Tempo integral de produção:</label>
                                                <input type="time" id="tempo" name="tempo" class="form-control" step='1' min="00:00:00" max="23:59:00"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="id_setor">Setor de operação:</label>

                                                <select name="id_setor" class="form-control" required="required" >                                       
                                                    <?php
                                                    echo "<option value=''>Selecione</option>";
                                                    include_once '../modell/Setores.class.php';
                                                    $fun = new Setores();
                                                    $matriz = $fun->listaSetor();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status_setor == true) {
                                                            $cod = $dados->id_setor;
                                                            $nome = $dados->descricao_setor;
                                                            echo "<option value='" . $cod . "'>" . $nome . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">                                   
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <?php
                            include_once "../modell/Operacao.class.php";

//instancia a classe de controle
                            $fun = new Operacao();

                            $operacao = \filter_input(INPUT_POST, 'funcao');
                            $custo_operacao = \filter_input(INPUT_POST, 'custo_funcao');
                            $tempo_operacao = \filter_input(INPUT_POST, 'tempo');
                            $setor_operacao = \filter_input(INPUT_POST, 'id_setor');
                            $status_operacao = true;
                            $id_produto = \filter_input(INPUT_POST, 'id_produto');
                            $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                            if (isset($cadastro)) {
                                if (empty($operacao) || empty($id_produto) || $id_produto == 'SELECIONE') {
                                    echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                    //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                } else {
                                    //var_dump($funcao, $custo_funcao, $tempo, $status, $id_produto);
                                    $status = $fun->cadastraOperacao($operacao, $custo_operacao, $tempo_operacao, $status_operacao, $id_produto, $setor_operacao);
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
        </div>
    </div>

<?php require_once "./actionRodape.php"; ?>
</body>
</html>