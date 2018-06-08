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

    </head>
    <body>
        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"> Anotações das Células</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="panel-heading">
                    <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                    <!--<a href="lista_config.php" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-th-list"></span> Listar</a>-->

<!--    <a href="pgAdmin.php?pagina=cadastraEquipe" class="btn btn-info"> <span class='glyphicon glyphicon-plus'></span> Adicionar</a>-->
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading" style="text-align: center;">
                            Anotações das Células
                        </div>        
                        <div class="panel-body">
                            <form method="post" class="form-horizontal">
                                <div class="col-lg-6">                                                                           
                                    <div class="form-group">
                                        <label for="data">Data do ocorrido:</label>
                                        <input type="date" id="data" name="data" class="form-control"  required="required"   />
                                    </div>                                    
                                    <div class="form-group">
                                        <label for="id_celula" class="tamanho-fonte">Célula de Trabalho:</label><small> (Campo Obrigatório)</small>
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
                                        <label for="descricao">Descrição da obeservação:</label>
                                        <textarea id="descricao" name="descricao" class="form-control" required="required" maxlength="250" rows="5"> </textarea>
                                        <div id="characterLeft"></div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                        <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                    </div> 
                                </div>
                            </form>
                        </div>
                        <?php
                        include_once "../modell/Anotacao.class.php";

//instancia a classe de controle
                        $prod = new Anotacao();

                        $descricao = \filter_input(INPUT_POST, 'descricao');
                        $data = \filter_input(INPUT_POST, 'data');
                        $id_celula = \filter_input(INPUT_POST, 'id_celula');
                        $status = TRUE;
                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                        if (isset($cadastro)) {
                            if (empty($descricao)) {
                                echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                            } else {
                                $status = $prod->cadastraAnotacao($descricao, $data, $id_celula, $status);
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

        <script src="../bootstrap/assets/js/jquery.mask.min.js"></script>

        <script>
            $('#characterLeft').text('250 caracteres aceitos');
            $('#descricao').keyup(function () {
                var max = 250;
                var len = $(this).val().length;
                if (len >= max) {
                    $('#characterLeft').text(' Você atingiu o limite de caracteres');
                } else {
                    var ch = max - len;
                    $('#characterLeft').text(ch + ' caracteres');
                }
            });




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
        </script>

        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <!-- For the demo ad only -->   

        <script src="../bootstrap/assets/js/bootstrap.js"></script>       
    </body>
</html>