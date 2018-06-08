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
        <meta name="author" content="Kunz, Marcelo 2016">

        <title>Bem Vindos</title>

        <!-- Bootstrap Core CSS -->
        <link href="../bootstrap/assets/css/bootstrap.css" rel="stylesheet">
        <link href="../bootstrap/assets/css/picture.css" rel="stylesheet">


        <link rel="stylesheet" type="text/css" href="../bootstrap/assets/css/default.css">
        <link rel="stylesheet" type="text/css" href="../bootstrap/assets/css/component.css">

        <script src="../bootstrap/assets/js/modernizr.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
        <script src="../bootstrap/assets/js/jquery.maskMoney.js"></script>



    </head>
    <body>
        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <!--<h1 class="page-header">Controle de Faturamento da Fábrica</h1>-->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="panel-heading">
                    <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                    <a href="lista_meta_diaria.php" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-th-list"></span> Listar</a>

<!--    <a href="pgAdmin.php?pagina=cadastraEquipe" class="btn btn-info"> <span class='glyphicon glyphicon-plus'></span> Adicionar</a>-->
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading centraliza">
                            Cadastro de produção diária
                        </div>        
                        <div class="panel-body">
                            <form method="post" class="form-horizontal">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="data_producao">Data do processo:</label>
                                        <input type="date" id="data_producao" name="data_producao" class="form-control" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="id_produto" class="tamanho-fonte">Produto</label><small> (Campo Obrigatório)</small>
                                        <select name="id_produto" class="form-control" required="required" >                                       
                                            <?php
                                            echo "<option value=''>Selecione ...</option>";
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
                                        <label for="n_pecas">Número de peças:</label>
                                        <input type="number" id="n_pecas" name="n_pecas" class="form-control" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="tempo_unitario">Tempo Unitário:</label>
                                        <input type="time" id="tempo_unitario" name="tempo_unitario" class="form-control" step='1' min="00:00:00" max="20:00:00" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="tempo_estimado">Tempo estimado para produção:</label>
                                        <input type="time" id="tempo_estimado" name="tempo_estimado" class="form-control" step='1' min="00:00:00" max="20:00:00" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="desconto">Desconto em peças:</label>
                                        <input type="number" id="desconto" name="desconto" class="form-control" step='1' min="00:00:00" max="20:00:00" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="n_pessoas">Pessoal Envolvido:</label>
                                        <input type="number" id="n_pessoas" name="n_pessoas" class="form-control" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="n_pecas_feitas">Número de peças produzidas:</label>
                                        <input type="number" id="n_pecas_feitas" name="n_pecas_feitas" class="form-control" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="tempo_producao">Tempo de Fabricação do produto:</label>
                                        <input type="time" id="tempo_producao" name="tempo_producao" class="form-control" step='1' min="00:00:00" max="20:00:00" required="required"  />
                                    </div>
                                    <div class="form-group">                                   
                                        <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                        <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    

                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php
                        include_once "../modell/MetaDiaria.class.php";
                        $fun = new MetaDiaria();
                        $data_producao = \filter_input(INPUT_POST, 'data_producao');
                        $n_pecas = \filter_input(INPUT_POST, 'n_pecas');
                        $id_produto = \filter_input(INPUT_POST, 'id_produto');
                        $tempo_unitario = \filter_input(INPUT_POST, 'tempo_unitario');
                        $n_pessoas = \filter_input(INPUT_POST, 'n_pessoas');
                        $tempo_estimado = \filter_input(INPUT_POST, 'tempo_estimado');
                        $desconto = \filter_input(INPUT_POST, 'desconto');
                        $n_pecas_feitas = \filter_input(INPUT_POST, 'n_pecas_feitas');
                        $tempo_producao = \filter_input(INPUT_POST, 'tempo_producao');
                        $status = TRUE;
                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                        if (isset($cadastro)) {
                            if (empty($data_producao) || empty($n_pecas)) {
                                echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                            } else {
                                //var_dump($data_producao, $n_pecas, $id_produto, $tempo_unitario, $n_pessoas, $tempo_estimado, $desconto, $n_pecas_feitas, $tempo_producao, $status);
                                $status = $fun->cadastraMeta($data_producao, $n_pecas, $id_produto, $tempo_unitario, $n_pessoas, $tempo_estimado, $desconto, $n_pecas_feitas, $tempo_producao, $status);
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
            <script src="../bootstrap/assets/js/toucheffects.js"></script>
            <!-- For the demo ad only -->   

            <script src="../bootstrap/assets/js/bootstrap.js"></script>
            <script src="../bootstrap/assets/js/jquery-1.10.2.min.js"></script>
            <script src="../bootstrap/assets/js/jquery-3.1.1.min.js"></script>
            <script src="../bootstrap/assets/js/jquery.maskMoney.js"></script>
    </body>
</html>