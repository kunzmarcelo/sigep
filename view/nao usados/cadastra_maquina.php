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
                    <a href="lista_maquina.php" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-th-list"></span> Listar</a>

<!--    <a href="pgAdmin.php?pagina=cadastraEquipe" class="btn btn-info"> <span class='glyphicon glyphicon-plus'></span> Adicionar</a>-->
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading centraliza">
                            Cadastro de Máquinas
                        </div>        
                        <div class="panel-body">
                            <form method="post" class="form-horizontal">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="numero">Número da máquina:</label>
                                        <input type="numer" id="numero" name="numero" class="form-control" placeholder="Ex: Acabamento" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="descricao">Descrição da máquina:</label>
                                        <input type="text" id="descricao" name="descricao" class="form-control" placeholder="Ex: Acabamento" required="required"  />
                                    </div>
                                                                           
                                    <!--
                                    <div class="col-xs-6 col-sm-4">
                                        <label for="tempo_producao">Tempo integral de produção:</label>
                                        <input type="text" id="tempo_producao" name="tempo_producao" class="form-control" placeholder="vlr_vista"   />
                                    </div>   -->

                                    <div class="form-group">                                   
                                        <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                        <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    

                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php
                        include_once "../modell/Maquina.class.php";

//instancia a classe de controle
                        $fun = new Maquina();

                        $numero = \filter_input(INPUT_POST, 'numero');
                        $descricao = \filter_input(INPUT_POST, 'descricao');
                        $status = TRUE;
                        
                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                        if (isset($cadastro)) {
                            if (empty($descricao) ) {
                                echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                            } else {
                                $status = $fun->cadastraMaquina($numero, $descricao, $status);
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