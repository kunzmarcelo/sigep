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
                </div>
                <div class="panel-heading">
                    <div class="form-group">
                        <form action="gerarPDF2.php" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-xs-6 col-sm-4">
                                    <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- /.col-lg-4 -->
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading centraliza">
                            Calculo de Produção
                        </div>        
                        <div class="panel-body">
                            <form method="post" class="form-horizontal">
                                <div class="col-lg-4">                                                                           
                                    <div class="form-group">
                                        <label for="title">Informe um titulo:</label>
                                        <input type="text" id="title" name="title" class="form-control" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="preco_kg">Valor do kg de tecido:</label>
                                        <input type="text" id="preco_kg" name="preco_kg" class="form-control" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="peso_produto">Peso do Produto em gramas:</label>
                                        <input type="text" id="peso_produto" name="peso_produto" class="form-control" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="n_peca">Número de peças:</label>
                                        <input type="number" id="n_peca" name="n_peca" class="form-control" placeholder="" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_ini">Hora inicial turno manhã:</label>
                                        <input type="text" id="hora_ini" name="hora_ini" class="form-control" placeholder=""  >
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_fim">Hora final turno manhã:</label>
                                        <input type="text" id="hora_fim" name="hora_fim" class="form-control" placeholder="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_ini_tarde">Hora inicial turno tarde:</label>
                                        <input type="text" id="hora_ini_tarde" name="hora_ini_tarde" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_fim_tarde">Hora final turno tarde:</label>
                                        <input type="text" id="hora_fim_tarde" name="hora_fim_tarde" class="form-control" >
                                    </div>
                                  <!--  <div class="form-group">
                                        <label for="hora_alternativo_1">Hora hora_alternativo_1:</label>
                                        <input type="text" id="hora_alternativo_1" name="hora_alternativo_1" class="form-control" value="13:30:00"    />
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_alternativo_2">Hora hora_alternativo_2:</label>
                                        <input type="text" id="hora_alternativo_2" name="hora_alternativo_2" class="form-control" value="17:55:00"   />
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_alternativo_3">Hora hora_alternativo_3:</label>
                                        <input type="text" id="hora_alternativo_3" name="hora_alternativo_3" class="form-control" value="07:30:00"    />
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_alternativo_4">Hora hora_alternativo_4:</label>
                                        <input type="text" id="hora_alternativo_4" name="hora_alternativo_4" class="form-control" value="11:55:00"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_alternativo_5">Hora hora_alternativo_5:</label>
                                        <input type="text" id="hora_alternativo_5" name="hora_alternativo_5" class="form-control" value="13:30:00"    />
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_alternativo_6">Hora hora_alternativo_6:</label>
                                        <input type="text" id="hora_alternativo_6" name="hora_alternativo_6" class="form-control" value="17:55:00"    />
                                    </div> -->
                                    <?php
                                    include_once "../modell/DetalheProdutoFuncao.php";
                                    $lote = new DetalheProdutoFuncao();
                                    $teste = $lote->somaValorMaoObra();
//                                            ******** pucha o valor de mao de obra indireta da tabela de custo de mao de obra ******
                                    $dados1 = $teste->fetchObject();
                                    $mao_obra_indireta = $dados1->custoMaoObra;
                                    ?>
                                    <div class="form-group">
                                        <label for="mao_obra_indireta">Mão de obra indireta:</label>
                                        <input type="text" id="mao_obra_indireta" name="mao_obra_indireta" class="form-control" value="<?= $mao_obra_indireta ?>" >                                        
                                        <label class="label label-default">Obs.: resultado calculado do banco de dados.<br> Se não quiser somar esse valor é só inserir 0.</label>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Enviar</button>
                                        <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                    </div>

                                </div>
                            </form>
                        </div>


                        <div class="panel-body">
                            <?php
                            $title = \filter_input(INPUT_POST, 'title');
                            $preco_kg = \filter_input(INPUT_POST, 'preco_kg');
                            $peso_produto = \filter_input(INPUT_POST, 'peso_produto');
                            $hora_ini = \filter_input(INPUT_POST, 'hora_ini');
                            $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                            $hora_ini_tarde = \filter_input(INPUT_POST, 'hora_ini_tarde');
                            $hora_fim_tarde = \filter_input(INPUT_POST, 'hora_fim_tarde');
                           // $hora_alternativo_1 = \filter_input(INPUT_POST, 'hora_alternativo_1');
                           // $hora_alternativo_2 = \filter_input(INPUT_POST, 'hora_alternativo_2');
                           // $hora_alternativo_3 = \filter_input(INPUT_POST, 'hora_alternativo_3');
                           // $hora_alternativo_4 = \filter_input(INPUT_POST, 'hora_alternativo_4');
                           // $hora_alternativo_5 = \filter_input(INPUT_POST, 'hora_alternativo_5');
                           // $hora_alternativo_6 = \filter_input(INPUT_POST, 'hora_alternativo_6');
                            $n_peca = \filter_input(INPUT_POST, 'n_peca');
                            $mao_obra_indireta = \filter_input(INPUT_POST, 'mao_obra_indireta');
                            $cadastro = \filter_input(INPUT_POST, 'cadastrar');


                            if (empty($hora_ini_tarde) || empty($hora_fim_tarde) ) {
                                $hora_ini_tarde = '00:00:00';
                                $hora_fim_tarde = '00:00:00';
                               //$hora_alternativo_1 = '00:00:00';
                                //$hora_alternativo_2 = '00:00:00';
                                //$hora_alternativo_3 = '00:00:00';
                               // $hora_alternativo_4 = '00:00:00';
                                //$hora_alternativo_5 = '00:00:00';
                               // $hora_alternativo_6 = '00:00:00';
                            }
                            if (isset($cadastro)) {
                                if (empty($preco_kg) || empty($peso_produto) || empty($hora_ini) || empty($hora_fim) || empty($n_peca)) {
                                    echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                    //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                } else {
                                    $resultado1 = explode(':', $hora_ini);
                                    $resultado2 = explode(':', $hora_fim);
                                    $resultado3 = explode(':', $hora_ini_tarde);
                                    $resultado4 = explode(':', $hora_fim_tarde);
                                   // $resultado5 = explode(':', $hora_alternativo_1);
                                   // $resultado6 = explode(':', $hora_alternativo_2);
                                   // $resultado7 = explode(':', $hora_alternativo_3);
                                   // $resultado8 = explode(':', $hora_alternativo_4);
                                   // $resultado9 = explode(':', $hora_alternativo_5);
                                   // $resultado0 = explode(':', $hora_alternativo_6);


                                    $resultadoManha = (($resultado2[0] * 60) + ($resultado2[1] ) + ($resultado2[2] / 60)) - (($resultado1[0] * 60) + ($resultado1[1]) + ($resultado1[2] / 60));
                                    $resultadoTarde = (($resultado4[0] * 60) + ($resultado4[1] ) + ($resultado4[2] / 60)) - (($resultado3[0] * 60) + ($resultado3[1]) + ($resultado3[2] / 60));
                                   // $resultadoAlter = (($resultado6[0] * 60) + ($resultado6[1] ) + ($resultado6[2] / 60)) - (($resultado5[0] * 60) + ($resultado5[1]) + ($resultado5[2] / 60));
                                    //$resultadoAlte2 = (($resultado8[0] * 60) + ($resultado8[1] ) + ($resultado8[2] / 60)) - (($resultado7[0] * 60) + ($resultado7[1]) + ($resultado7[2] / 60));
                                    //$resultadoAlte3 = (($resultado0[0] * 60) + ($resultado0[1] ) + ($resultado0[2] / 60)) - (($resultado9[0] * 60) + ($resultado9[1]) + ($resultado9[2] / 60));

                                    
                                    //echo $resultadoManha.' - '. $resultadoTarde .' - '.  $resultadoAlter .' - '.  $resultadoAlte2 .' - '.  $resultadoAlte3;
                                    
                                   // - $resultadoAlter - $resultadoAlte2 - $resultadoAlte3 echo $teste = ($resultadoManha* 0.15) + ($resultadoTarde * 0.15) + ( $resultadoAlter * 0.15) +  ($resultadoAlte2 * 0.15) +  ($resultadoAlte3* 0.15);
                                    $tempoR = ($resultadoManha - $resultadoTarde );

                                    $mao_obra_peca = (($tempoR * 0.15) / $n_peca) + $mao_obra_indireta;
                                    $preco_produto = ($preco_kg * $peso_produto) + $mao_obra_peca;
                                    ?>
                                </div>
                            </div>
                            <?php
                            ob_start(); // Ativa o buffer de saida do PHP

                            function CriaCodigo() { //Gera numero aleatorio
                                for ($i = 0; $i < 40; $i++) {
                                    $tempid = strtoupper(uniqid(rand(), true));
                                    $finalid = substr($tempid, -12);
                                    return $finalid;
                                }
                            }
                            ?>
                            <div class="panel panel-primary">
                                <div class="panel-heading centraliza">
        <?= $title ?>
                                </div>
                                <table class="table table-hover" >
                                    <thead>
                                        <tr>                                            
                                            <th>Preço do Kg</th>
                                            <th>Peso:</th>
                                            <th>Hora início:</th>
                                            <th>Hora final:</th>                                                
                                            <th>Hora início:</th>
                                            <th>Hora final:</th>                                                
                                            <th>N° Peças:</th>
                                            <th>Custo Fixo:</th>
                                            <th>Total:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>R$ <?= number_format($preco_kg, 2, ',', '.') ?></td>
                                            <td><?= number_format($peso_produto, 2, ',', '.') ?> Kg/m</td>
                                            <td><?= $hora_ini ?></td>
                                            <td><?= $hora_fim ?></td>
                                            <td><?= $hora_ini_tarde ?></td>
                                            <td><?= $hora_fim_tarde ?></td>                                            
                                            <td><?= $n_peca ?></td>
                                            <td>R$ <?= number_format($mao_obra_indireta, 2, ',', '.') ?></td>
                                            <td class="alert alert-info">
                                                <p class="alert-link">
                                                    R$ <?= number_format($preco_produto, 2, ',', '.') ?>
                                                </p>
                                            </td>  
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <?php
                    /* Captação de dados */
                    $buffer = ob_get_contents(); // Obtém os dados do buffer interno
                    $filename = "code.html"; // Nome do arquivo HTML
                    file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
                    ?>

                </div>
            </div>
        </div>

        <script src="../bootstrap/assets/js/jquery.mask.min.js"></script>

        <script>
            $(document).ready(function () {
                //                $('.date').mask('11/11/1111');
                $('#tempo').mask('00:00:00');
                $('#hora_ini').mask('00:00:00');
                $('#hora_fim').mask('00:00:00');
                $('#hora_ini_tarde').mask('00:00:00');
                $('#hora_fim_tarde').mask('00:00:00');
                $('#hora_alternativo_1').mask('00:00:00');
                $('#hora_alternativo_2').mask('00:00:00');
                $('#hora_alternativo_3').mask('00:00:00');
                $('#hora_alternativo_4').mask('00:00:00');
                $('#hora_alternativo_5').mask('00:00:00');
                $('#hora_alternativo_6').mask('00:00:00');
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
