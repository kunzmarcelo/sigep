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
                    <a href="lista_salario.php" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-th-list"></span> Listar</a>

<!--    <a href="pgAdmin.php?pagina=cadastraEquipe" class="btn btn-info"> <span class='glyphicon glyphicon-plus'></span> Adicionar</a>-->
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading centraliza">
                            Cadastro de Salários
                        </div>        
                        <div class="panel-body">
                            <form method="post" class="form-horizontal">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="valor">Salário:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></div>
                                            <input type="text" id="valor" name="valor" class="form-control" placeholder="Ex: Acabamento" required="required" />

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="n_funcionarios">Número de funcionários</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
                                            <input type="number" id="n_funcionarios" name="n_funcionarios" class="form-control" required="required"/>
                                        </div>                                       
                                    </div>                                       
                                    <div class="form-group">
                                        <label for="dias_trabalhados">Dias de trabalho mês:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                                        <input type="number" id="dias_trabalhados" name="dias_trabalhados" class="form-control" required="required"/>
                                    </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_inicio">Hora de início de turno de trabalho:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div>
                                            <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" min="00:00:00" step="any" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_fim">Hora final do turno de trabalho:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div>
                                            <input type="time" id="hora_fim" name="hora_fim" class="form-control" min="00:00:00" step="any" required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group">                                   
                                        <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                        <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    

                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php
                        include_once "../modell/ControleSalarial.class.php";

//instancia a classe de controle
                        $fun = new ControleSalarial();

                        $valor = \filter_input(INPUT_POST, 'valor');
                        $n_funcionarios = \filter_input(INPUT_POST, 'n_funcionarios');
                        $dias_trabalhados = \filter_input(INPUT_POST, 'dias_trabalhados');
                        $hora_inicio = \filter_input(INPUT_POST, 'hora_inicio');
                        $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                        $status_salario = TRUE;
                        $data_cadastro = date('Y-m-d');
//                            $tempo_medio = \filter_input(INPUT_POST, 'tempo_medio');
//                            $tempo_producao = \filter_input(INPUT_POST, 'tempo_producao');
                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                        if (isset($cadastro)) {
//                            if (empty($n_funcionarios)) {
//                                echo "<div class='alert alert-danger alert-dismissable'>
//                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
//                                            Algum dos campos acima não foi preenchido corretamente.
//                                        </div>";
//                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
//                            } else {
                            $status = $fun->cadastraSalario($valor, $n_funcionarios, $dias_trabalhados, $hora_inicio, $hora_fim, $status_salario, $data_cadastro);
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
                            // }
                        }
                        ?>


                    </div>
                </div>
            </div>
            
            <script src="../bootstrap/assets/js/jquery.mask.min.js"></script>

        <script>
            $(document).ready(function () {
                //                $('.date').mask('11/11/1111');
                $('#valor').mask('0000.00');
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