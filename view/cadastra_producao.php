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
                                    <div class="col-lg-12">
                                        <form method="post" role="form">
                                            <div class="col-lg-6">
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
                                                                echo "<option value= '$dados->descricao' >" . $nome . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>                            
                                                </div>                                            

                                                <div class="form-group">
                                                    <label for="quantidade">Quantidade de peças:</label>
                                                    <input type="number" id="quantidade" name="quantidade" class="form-control"  required="required" min="0" placeholder="Ex: 10"/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="numero">Número de controle:</label>
                                                    <input type="number" id="numero" name="numero" class="form-control"  required="required" placeholder="Ex: 003"/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="data_ini">Data de inicio da produção:</label>
                                                    <input type="date" id="data_ini" name="data_ini" class="form-control"  required="required"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="data_fim">Data final da produção:</label>
                                                    <input type="date" id="data_fim" name="data_fim" class="form-control"  required="required"/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="hora_ini">Hora inicial da produção:</label>
                                                    <input type="time" id="hora_ini" name="hora_ini" class="form-control" step="2" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="hora_fim">Hora final da produção:</label>
                                                    <input type="time" id="hora_fim" name="hora_fim" class="form-control" step="2" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="desconto">Desconto de dias da semana:</label>
                                                    <select class="form-control" name="desconto" id="desconto">
                                                        <option value="0">Nenhum</option>
                                                        <option value="1">Sábado</option>
                                                        <option value="2">Sábado e Domingo</option>
                                                        <option value="1">1 dia de Feriádo</option>
                                                        <option value="2">2 dias de Feriádo</option>
                                                        <option value="3">3 dias de Feriádo</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                    <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                                </div>
                                            </div>

                                        </form>

                                        <?php
                                        include_once "../modell/Producao.class.php";

//instancia a classe de controle
                                        $prod = new Producao();

                                        $produto = \filter_input(INPUT_POST, 'id_produto');
                                        $quantidade = \filter_input(INPUT_POST, 'quantidade');
                                        $numero = \filter_input(INPUT_POST, 'numero');
                                        $data_ini = \filter_input(INPUT_POST, 'data_ini');
                                        $data_fim = \filter_input(INPUT_POST, 'data_fim');
                                        $hora_ini = \filter_input(INPUT_POST, 'hora_ini');
                                        $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                                        $desconto = \filter_input(INPUT_POST, 'desconto');
                                        $status = TRUE;

//                       
                                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                        if (isset($cadastro)) {
                                            if (empty($produto)) {
                                                echo "<div class='col-lg-12'>
                                                        <div class='alert alert-danger alert-dismissable'>
                                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                            Algum dos campos acima não foi preenchido corretamente.
                                                        </div>
                                                        </div>";
                                                
                                            } else {
                                                $status = $prod->cadastraProducao($numero, $quantidade, $produto, $data_ini, $data_fim, $hora_ini, $hora_fim, $desconto, $status);

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
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <script src="../ajax/setor/ativa_setor.js"></script>
<?php require_once "./actionRodape.php"; ?>
    </body>
</html>