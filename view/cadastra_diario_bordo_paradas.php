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
                                                    <label for="id_posto" class="tamanho-fonte">Selecione o Posto de Trabalho:</label><small> (Campo Obrigatório)</small>
                                                    <select name="id_posto" class="form-control" required="required">
                                                        <option value="">Selecione...</option>
                                                        <?php
//                                            echo "<option value='0'></option>";
                                                        include_once '../modell/PostoTrabalho.class.php';
                                                        $maquina = new PostoTrabalho();
                                                        $matriz = $maquina->listaPosto();

                                                        while ($dados = $matriz->fetchObject()) {
                                                            if ($dados->status == true) {
                                                                $cod = $dados->id_posto;
                                                                $nome = $dados->numero;
                                                                $descricao = $dados->descricao;
                                                                echo "<option value=" . $cod . ">" . $nome . ' - ' . $descricao . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>                            
                                                </div>
                                                <div class="form-group">
                                                    <label for="data">Data:</label><small> (Campo Obrigatório)</small>
                                                    <input type="date" id="data" name="data" class="form-control" required="required"/>
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
                                                    <label for="turno" class="tamanho-fonte">Turno:</label><small> (Campo Obrigatório)</small>
                                                    <select name="turno" class="form-control" required="required">                                       
                                                        <option value="">Selecione...</option>
                                                        <option value="Manhã">Manhã</option>
                                                        <option value="Tarde">Tarde</option>
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
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group"> 
                                                    <label for="id_operacao" class="tamanho-fonte">Função:</label><small> (Campo Obrigatório)</small>
                                                    <select name="id_operacao" id="id_operacao" class="form-control" required="required">
                                                        <option value="">Selecione um produto...</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="hora_ini">Hora de Ínicio da Parada:</label>
                                                    <input type="time" id="hora_ini" name="hora_ini" class="form-control" placeholder="" step='1' min="00:00:00" max="23:59:00"  />
                                                </div>
                                                <div class="form-group">
                                                    <label for="hora_fim">Hora Final da Parada:</label>
                                                    <input type="time" id="hora_fim" name="hora_fim" class="form-control" placeholder="" step='1' min="00:00:00" max="23:59:00" />
                                                </div>                                    
                                                <div class="form-group">
                                                    <label for="motivo">Motivo:</label>
                                                    <input type="text" id="motivo" name="motivo" class="form-control" placeholder="Ex: Acabamento"   />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">                                   
                                                    <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                    <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                                    <!--                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">
                                                                                                        Cadastrar paradas
                                                                                                    </button>-->
                                                </div>
                                            </div>

                                        </form>



                                        <?php
                                        include_once "../modell/DiarioBordoParadas.class.php";

//instancia a classe de controle
                                        $diario = new DiarioBordoParadas();



                                        $id_posto = \filter_input(INPUT_POST, 'id_posto');
                                        $data_trabalhada = \filter_input(INPUT_POST, 'data');
                                        $turno = \filter_input(INPUT_POST, 'turno');
                                        $id_produto = \filter_input(INPUT_POST, 'id_produto');
                                        $id_operacao = \filter_input(INPUT_POST, 'id_operacao');
                                        $hora_ini = \filter_input(INPUT_POST, '00:00:00');
                                        $hora_fim = \filter_input(INPUT_POST, '00:00:00');
                                        $motivo = \filter_input(INPUT_POST, '');
                                        $hora_ini = \filter_input(INPUT_POST, 'hora_ini');
                                        $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                                        $id_funcionario = \filter_input(INPUT_POST, 'id_funcionario');

                                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');


                                        if (isset($cadastro)) {
                                            //var_dump($turno, $id_posto, $id_produto, $funcao, $hora_ini, $hora_fim, $motivo, $total_peca, $hora_ini, $hora_fim, $obs, $id_funcionario, $data);
                                            $status = $diario->cadastraDiarioBordoParadas($id_posto, $data_trabalhada, $id_funcionario, $turno, $id_produto, $id_operacao, $hora_ini, $hora_fim, $motivo);
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


<!--            <script src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load('jquery', '1.3');
</script>-->
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