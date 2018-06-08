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
                                                <label for="id_celula" class="tamanho-fonte">Pessoas por célula de trabalho:</label><small> (Campo Obrigatório)</small>
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
                                                <label for="id_produto" class="tamanho-fonte">Produto</label><small> (Campo Obrigatório)</small>
                                                <select name="id_produto" class="form-control" required="required" >                                       
                                                    <?php
                                                    echo "<option value=''>Selecione ...</option>";
                                                    include_once '../modell/Produto.class.php';
                                                    $prod = new Produto();
                                                    $matriz = $prod->listaProduto();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status == true) {
                                                            $cod = $dados->id_produto;
                                                            $descricao = $dados->descricao;
                                                            echo "<option value=" . $cod . ">" . $descricao . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>
                                            <div class="form-group">
                                                <label for="obs">Observação:</label>
                                                <input type="text" id="obs" name="obs" class="form-control" placeholder="Ex.: MC: manga curta; ML: manga Longa"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="data">Data da produção:</label>
                                                <input type="date" id="data" name="data" class="form-control" required="required" />
                                            </div>
                                            <div class="form-group">
                                                <label for="n_pecas">Quantidade de serem produzidas:</label>
                                                <input type="number" id="n_pecas" name="n_pecas" class="form-control" placeholder="numero de peças" required="required" />
                                            </div>
                                            <div class="form-group">
                                                <label for="tempo_unitario">Tempo Unitário do Produto:</label>
                                                <input type="time" id="tempo_unitario" name="tempo_unitario" class="form-control" step='1' min="00:00:00" max="24:00:00" />
                                            </div>
                                            <div class="form-group">
                                                <label for="reajuste">Reajuste em porcentagem para mais:</label>
                                                <select name="reajuste" class="form-control" required="required">                                       
                                                    <?php
                                                    $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
                                                    $data = date('Y-m-d');

                                                    // Varivel que recebe o dia da semana (0 = Domingo, 1 = Segunda ...)
                                                    $diasemana_numero = date('w', strtotime($data));

                                                    // Exibe o dia da semana com o Array
                                                    // echo $diasemana[$diasemana_numero];
//                                            echo "<option value=''><b>Selecione ...</b></option>";
                                                    include_once '../modell/ConfigCelula.class.php';
                                                    $fun = new ConfigCelula();
                                                    $matriz = $fun->listaUmConfig($diasemana[$diasemana_numero]);


                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status == true) {
                                                            $desconto = $dados->desconto;
                                                            $descricao = $dados->dias;
                                                            echo "<option value='$desconto'>" . $descricao . ' ' . $desconto . '% ' . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select> 

<!--                                        <select name="reajuste" class="form-control" required="required">
                                            <option value="05">05</option>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                        </select>-->
                                            </div>
                                            <div class="form-group">
                                                <label for="n_feitas">Quantidade de peças produzidas:</label>
                                                <input type="number" id="n_feitas" name="n_feitas" class="form-control" placeholder="numero de peças"  />
                                            </div>
                                            <div class="form-group">                                            
                                                <label for="abstencao" class="tamanho-fonte">Funcionário que faltou:</label><small> (Campo Obrigatório)</small>
                                                <select name="abstencao" class="form-control" required="required">                                       
                                                    <?php
                                                    echo "<option value='0'><b>Selecione ...</b></option>";
                                                    include_once '../modell/Funcionario.class.php';
                                                    $fun = new Funcionario();
                                                    $matriz = $fun->listaFuncionario();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->ativo == true && $dados->departamento != 'Escritório') {

                                                            $nome = $dados->nome;
                                                            echo "<option value=1>" . $nome . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>
                                            <div class="form-group">
                                                <label for="motivo">Motivo:</label>
                                                <input type="text" id="motivo" name="motivo" class="form-control" />
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <?php
                            include_once "../modell/DetalheCelulaProduto.class.php";
//instancia a classe de controle
                            $prodL = new DetalheCelulaProduto();

                            $id_celula = \filter_input(INPUT_POST, 'id_celula');
                            $id_produto = \filter_input(INPUT_POST, 'id_produto');
                            $data = \filter_input(INPUT_POST, 'data');
                            $pecas_determinadas = \filter_input(INPUT_POST, 'n_pecas');
                            $pecas_finalizadas = \filter_input(INPUT_POST, 'n_feitas');
                            $tempo_unitario = \filter_input(INPUT_POST, 'tempo_unitario');
                            $falta = \filter_input(INPUT_POST, 'abstencao');
                            $motivo_falta = \filter_input(INPUT_POST, 'motivo');
                            $status = '1';
                            $obs = \filter_input(INPUT_POST, 'obs');
                            $margem_erro = \filter_input(INPUT_POST, 'reajuste');
                            $cadastro = \filter_input(INPUT_POST, 'cadastrar');
                            if (isset($cadastro)) {
                                if (empty($id_celula) || empty($id_produto) || empty($pecas_determinadas)) {
                                    echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                    //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                } else {
                                    //var_dump($id_lote, $id_produto, $data, $n_peca,$preco,$observacao);
                                    $status = $prodL->cadastraCelulaProduto($id_celula, $id_produto, $data, $pecas_determinadas, $pecas_finalizadas, $tempo_unitario, $status, $obs, $falta, $motivo_falta, $margem_erro);
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


        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>