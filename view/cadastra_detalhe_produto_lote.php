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
                                                <label for="id_lote" class="tamanho-fonte">Lote:</label><small> (Campo Obrigatório)</small>
                                                <select name="id_lote" class="form-control" required="required" >                                       
                                                    <?php
                                                    echo "<option>Selecione ...</option>";
                                                    include_once '../modell/Lote.class.php';
                                                    $lote = new Lote();
                                                    $matriz = $lote->listaLote();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status == TRUE) {
                                                            $cod = $dados->id_lote;
                                                            $n_lote = $dados->numero;
                                                            $descricao = $dados->descricao;
                                                            echo "<option value=" . $cod . ">" . $descricao . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>

                                            <div class="form-group">
                                                <label for="id_produto" class="tamanho-fonte">Produto</label><small> (Campo Obrigatório)</small>
                                                <select name="id_produto" class="form-control" required="required" >                                       
                                                    <?php
                                                    echo "<option >Selecione ...</option>";
                                                    include_once '../modell/Produto.class.php';
                                                    $prod = new Produto();
                                                    $matriz = $prod->listaProduto();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        $cod = $dados->id_produto;
                                                        $descricao = $dados->descricao;
                                                        echo "<option value=" . $cod . ">" . $descricao . "</option>";
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>
                                            <div class="form-group">
                                                <label for="q_peca">Numero de peças</label>
                                                <input type="number" id="q_peca" name="q_peca" class="form-control" placeholder="numero de peças" required="required"  />
                                            </div>
                                            <div class="form-group">
                                                <label for="preco">Preço de Venda:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">R$</div>
                                                    <input type="number" step="any" id="preco" name="preco" class="form-control" placeholder="Ex: 1.50" required="required"  />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="data">Data da produção:</label>
                                                <input type="date" id="data" name="data" class="form-control" required="required" />
                                            </div>
                                            <div class="form-group">
                                                <label for="observacao">Observação:</label>
                                                <input type="text" id="observacao" name="observacao" class="form-control" placeholder="EX: MC - Manga Curta; ML - Manga Long"   />
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                            </div>

                                        </form>


                                        <?php
                                        include_once "../modell/DetalheLoteProduto.class.php";

//instancia a classe de controle
                                        $prodL = new DetalheLoteProduto();

                                        $id_lote = \filter_input(INPUT_POST, 'id_lote');
                                        $id_produto = \filter_input(INPUT_POST, 'id_produto');
                                        $n_peca = \filter_input(INPUT_POST, 'q_peca');
                                        $data = \filter_input(INPUT_POST, 'data');
                                        $preco = \filter_input(INPUT_POST, 'preco');
                                        $observacao = \filter_input(INPUT_POST, 'observacao');
                                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');
                                        if (isset($cadastro)) {
                                            if (empty($id_lote) || empty($id_produto) || empty($n_peca)) {
                                                echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                            } else {
                                                //var_dump($id_lote, $id_produto, $data, $n_peca,$preco,$observacao);
                                                $status = $prodL->cadastraLoteProduto($id_lote, $id_produto, $data, $n_peca, $preco, $observacao);
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
            </div>
        </div>
        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>
