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
                                    <form method="post" role="form">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="descricao">Nome do produto:</label>
                                                <input type="text" id="descricao" name="descricao" class="form-control" placeholder="Ex: jaqueta de tactel" required="required"  />
                                            </div>
                                            <div class="form-group">
                                                <label for="preco">Preço de Venda:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">R$</div>
                                                    <input type="text" id="preco" name="preco" class="form-control" placeholder="Ex: 1.50"   />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="tecido">Metragem de tecido:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">m</div>
                                                    <input type="text" id="tecido" name="tecido" class="form-control" placeholder="Ex: 1.45"   />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="custo_tecido">Custo do metro de tecido:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">R$</div>
                                                    <input type="text" id="custo_tecido" name="custo_tecido" class="form-control" placeholder="Ex: 1.35"   />
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="descricao2">Nome do sub produto:</label>
                                                <input type="text" id="descricao2" name="descricao2" class="form-control" placeholder="Ex: forro"   />
                                            </div>
                                            <div class="form-group">
                                                <label for="tecido2">Metragem sub produto:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">m</div>
                                                    <input type="text" id="tecido2" name="tecido2" class="form-control" placeholder="Ex: 1.8"   />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="custo_tecido2">Custo de tecido sub produto:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">R$</div>
                                                    <input type="text" id="custo_tecido2" name="custo_tecido2" class="form-control" placeholder="Ex: 1.50"   />
                                                </div>
                                            </div>
                                            <div class="form-group">                                        
                                                <label for="tempo_producao">Tempo integral de produção:</label>
                                                <input type="time" id="tempo_producao" name="tempo_producao" step="2" class="form-control" min="00:00:00" max="20:00:00"   />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                            </div>
                                        </div>
                                    </form>

                                    <?php
                                    include_once "../modell/Produto.class.php";

//instancia a classe de controle
                                    $prod = new Produto();

                                    $descricao = \filter_input(INPUT_POST, 'descricao');
//                        $composicao = \filter_input(INPUT_POST, 'composicao');
                                    $preco = \filter_input(INPUT_POST, 'preco');
//                            $referencia = \filter_input(INPUT_POST, 'referencia');
                                    $material = \filter_input(INPUT_POST, 'tecido');
                                    $custo_material = \filter_input(INPUT_POST, 'custo_tecido');
                                    $tempo_producao = \filter_input(INPUT_POST, 'tempo_producao');
//                           
                                    $descricao2 = \filter_input(INPUT_POST, 'descricao2');
                                    $material2 = \filter_input(INPUT_POST, 'tecido2');
                                    $custo_material2 = \filter_input(INPUT_POST, 'custo_tecido2');
                                    $update_date = date("Y-m-d");
                                    $status = true;
                                    $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                    if (isset($cadastro)) {
                                        if (empty($descricao)) {
                                             echo "<div class='col-lg-12'>
                                                        <div class='alert alert-danger alert-dismissable'>
                                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                            Algum dos campos acima não foi preenchido corretamente.
                                                        </div>
                                                        </div>";
                                             } else {
                                            $status = $prod->cadastraProduto($descricao, $preco, $material, $custo_material, $tempo_producao, $descricao2, $material2, $custo_material2, $update_date, $status);
                                            if ($status == true) {
                                                echo "<div class='col-lg-12'>
                                                            <div class='alert alert-info alert-dismissable'>
                                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                                Registro inserido com sucesso.
                                                            </div>
                                                            </div>";
                                                } else {
                                                    echo "<div class='col-lg-12'>
                                                            <div class='alert alert-danger alert-dismissable'>
                                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                                Erro ao inserir o resgistro.
                                                            </div>
                                                            </div>";
                                            }
                                        }
                                    }
                                    ?>
                                    <!--</div>-->
                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!--    <script src="../bootstrap/assets/js/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function () {
            //                $('.date').mask('11/11/1111');
            $('#tempo_producao').mask('00:00:00');
            $('#mao_obra').mask('0.00');
            //                $('.date_time').mask('99/99/9999 00:00:00');
            //                $('.cep').mask('99999-999');
            //                $('.phone').mask('9999-9999');
            //                $('.phone_with_ddd').mask('(99) 9999-9999');
            //                $('.phone_us').mask('(999) 999-9999');
            //                $('.mixed').mask('AAA 000-S0S');
        });
    </script>-->
        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>