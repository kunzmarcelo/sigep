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
                                                <label for="numero">Número do lote:</label>
                                                <input type="text" id="numero" name="numero" class="form-control" placeholder="2001" required="required"  />
                                            </div>
                                            <div class="form-group">
                                                <label for="descricao">Descrição:</label>
                                                <input type="text" id="descricao" name="descricao" class="form-control" required="required" placeholder="descrição do lote"/>
                                            </div>                                    
                                            <div class="form-group">
                                                <label for="data">Data de fábricação do lote:</label>

                                                <input type="date" id="data" name="data" class="form-control" required="required" />

                                            </div>                                    
                                            <div class="form-group">
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                <?php
                                include_once "../modell/Lote.class.php";

//instancia a classe de controle
                                $prod = new Lote();

                                $descricao = \filter_input(INPUT_POST, 'descricao');
                                $numero = \filter_input(INPUT_POST, 'numero');
                                $data = \filter_input(INPUT_POST, 'data');
                                $status = true;
                                $cadastro = \filter_input(INPUT_POST, 'cadastrar');
                                $ano_lote = date('Y');
                                $numero_lote = $numero . '/' . $ano_lote;
                                //echo $numero_lote;
                                if (isset($cadastro)) {
                                    if (empty($descricao)) {
                                        echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                        //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                    } else {
                                        $status = $prod->cadastraLote($numero_lote, $descricao, $data, $status);
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