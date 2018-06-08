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


        <script type="text/javascript">
//        $(document).ready(function(){
//              $("#vlr_prazo").maskMoney({showSymbol:true, symbol:"R$", decimal:",", thousands:"."});
//              $("#vlr_vista").maskMoney({showSymbol:true, symbol:"R$", decimal:",", thousands:"."});
//              $("#vlr_cartao").maskMoney({showSymbol:true, symbol:"R$", decimal:",", thousands:"."});
//              $("#vlr_parcelas").maskMoney({showSymbol:true, symbol:"R$", decimal:",", thousands:"."});
//              $("#vlr_pagamentos").maskMoney({showSymbol:true, symbol:"R$", decimal:",", thousands:"."});
//        });
        </script>

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
                    <a href="lista_numero_pecas.php" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-th-list"></span> Listar</a>

<!--    <a href="pgAdmin.php?pagina=cadastraEquipe" class="btn btn-info"> <span class='glyphicon glyphicon-plus'></span> Adicionar</a>-->
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading" style="text-align: center;">
                            Cadastro salários do mês de funcionários
                        </div>        
                        <div class="panel-body">
                            <form class="form-horizontal" method="post">                            
                                <div class="col-lg-6">                                                                           
                                    <div class="form-group">
                                        <label for="id_funcionario" class="tamanho-fonte">Funcionário:</label><small> (Campo Obrigatório)</small>
                                        <select name="id_funcionario" class="form-control"required="required" >
                                            <option value="">Selecione...</option>
                                            <?php
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
                                        <label for="data">Data inicial:</label>
                                        <input type="date" name="data" value="" class="form-control" required="required" >
                                    </div>
                                    <div class="form-group">
                                        <label for="data2">Data final:</label>
                                        <input type="date" name="data2" value="" class="form-control" required="required" >
                                    </div>
                                    <div class="form-group">
                                        <label for="n_peca">Numero de Peças:</label>
                                        <input type="number" name="n_peca" value="" class="form-control" required="required" >
                                    </div>
                                    <div class="form-group">
                                        <label for="salario_producao">Salários:</label>
                                        <select name="salario_producao" class="form-control" required="required" >
                                            <option value="">Selecione...</option>
                                            <?php
                                            include_once '../modell/Salario.class.php';
                                            $fun = new Salario();
                                            $matriz = $fun->listaSalario();

                                            while ($dados = $matriz->fetchObject()) {
                                                $cod = $dados->id_salario;
                                                $nome = $dados->valor;
                                                echo "<option value=" . $cod . ">" . $nome . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>                                    
                                    <div class="form-group">
                                        <button name="cadastrar" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Enviar</button>
                                        <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php
                        include_once "../modell/NumeroPecas.class.php";

//instancia a classe de controle
                        $fun = new NumeroPecas();

                        $data_ini = \filter_input(INPUT_POST, 'data');
                        $data_fim = \filter_input(INPUT_POST, 'data2');
                        $n_peca = \filter_input(INPUT_POST, 'n_peca');
                        $salario_producao = \filter_input(INPUT_POST, 'salario_producao');
                        $id_funcionario = \filter_input(INPUT_POST, 'id_funcionario');
                        $id_salario = \filter_input(INPUT_POST, 'salario_producao');;

                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                        if (isset($cadastro)) {
                            if (empty($data_ini)) {
                                echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                            } else {
                                //var_dump($data_ini, $data_fim, $n_peca, $salario_producao, $id_funcionario, $id_salario);
                                $status = $fun->cadastraNumeroPecas($data_ini, $data_fim, $n_peca, $salario_producao, $id_funcionario, $id_salario);
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