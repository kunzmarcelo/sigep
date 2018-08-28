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
                                Formulário de cálculo
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form method="post" role="form">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="title" class="tamanho-fonte">Produto</label><small> (Campo Obrigatório)</small>
                                                    <select name="title" class="form-control" required="required" >                                       
                                                        <?php
                                                        echo "<option value='Selecione'>Selecione ...</option>";
                                                        include_once '../modell/Produto.class.php';
                                                        $prod = new Produto();
                                                        $matriz = $prod->listaProduto();

                                                        while ($dados = $matriz->fetchObject()) {
                                                            if ($dados->status == true) {
                                                                $cod = $dados->id_produto;
                                                                $nome = $dados->referencia;
                                                                $descricao = $dados->descricao;
                                                                echo "<option value='" . $descricao . "'>" . $descricao . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>                            
                                                </div>
                                                <div class="form-group">
                                                    <label for="preco_kg">Valor do kg ou metro de tecido:</label>
                                                    <input type="text" id="preco_kg" name="preco_kg" class="form-control" required="required"  placeholder="Ex: 15.50" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="peso_produto">Metragem ou peso do material:</label>
                                                    <input type="text" id="peso_produto" name="peso_produto" class="form-control" required="required" placeholder="Metro = 1.50 ou Peso = 0.250" />
                                                </div>

                                                <div class="form-group">
                                                    <label for="n_peca">Número de peças:</label>
                                                    <input type="number" id="n_peca" name="n_peca" class="form-control" placeholder="" required="required"  />
                                                </div>

                                                <div class="form-group">
                                                    <label for="hora_ini">Hora inicial turno manhã:</label>
                                                    <input type="time" step="2" id="hora_ini" name="hora_ini" class="form-control" placeholder="" required="required" >
                                                </div>
                                            </div>
                                            <div class="col-lg-6">

                                                <div class="form-group">
                                                    <label for="hora_fim">Hora final turno manhã:</label>
                                                    <input type="time" step="2" id="hora_fim" name="hora_fim" class="form-control" placeholder="" required="required" >
                                                </div>

                                                <div class="form-group">
                                                    <label for="hora_ini_tarde">Hora inicial turno tarde:</label>
                                                    <input type="time" step="2" id="hora_ini_tarde" name="hora_ini_tarde" class="form-control" placeholder=""   />
                                                </div>
                                                <div class="form-group">
                                                    <label for="hora_fim_tarde">Hora final turno tarde:</label>
                                                    <input type="time" step="2" id="hora_fim_tarde" name="hora_fim_tarde" class="form-control" placeholder=""   />
                                                </div>
                                                <?php
//                                            include_once "../modell/DetalheProdutoFuncao.php";
//                                            $lote = new DetalheProdutoFuncao();
//                                            $teste = $lote->somaValorMaoObra();
//                                            ******** pucha o valor de mao de obra indireta da tabela de custo de mao de obra ******
//                                            $dados1 = $teste->fetchObject();
//                                            $mao_obra_indireta = $dados1->custoMaoObra;
                                                ?>
                                                <div class="form-group">
                                                    <label for="mao_obra_indireta">Adicionais:</label>
                                                    <input type="text" id="mao_obra_indireta" name="mao_obra_indireta" value="0" class="form-control" >                                        
                                                    <!--<label class="label label-default">Obs.: resultado calculado do banco de dados. Se não quiser somar esse valor é só inserir 0.</label>-->
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Enviar</button>
                                                    <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>
                                                    <button type="button" value="Imprimir" class="btn btn-default" id="btn">Imprimir</button>

                                                </div>
                                            </div>
                                        </form>                                        
                                    </div>



                                    <?php
                                    $title = \filter_input(INPUT_POST, 'title');
                                    $preco_kg = \filter_input(INPUT_POST, 'preco_kg');
                                    $peso_produto = \filter_input(INPUT_POST, 'peso_produto');
                                    $hora_ini = \filter_input(INPUT_POST, 'hora_ini');
                                    $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                                    $hora_ini_tarde = \filter_input(INPUT_POST, 'hora_ini_tarde');
                                    $hora_fim_tarde = \filter_input(INPUT_POST, 'hora_fim_tarde');
                                    $n_peca = \filter_input(INPUT_POST, 'n_peca');
                                    $mao_obra_indireta = \filter_input(INPUT_POST, 'mao_obra_indireta');
                                    $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                    if (isset($cadastro)) {
                                        if (empty($preco_kg) || empty($peso_produto) || empty($hora_ini) || empty($hora_fim) || empty($n_peca)) {
                                            echo "<div class='alert alert-danger alert-dismissable'>
                                                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                        Algum dos campos acima não foi preenchido corretamente.
                                                    </div>";
                                            //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                        } else {
                                            if (empty($hora_ini_tarde) || empty($hora_fim_tarde)) {
                                                $hora_ini_tarde = '00:00:00';
                                                $hora_fim_tarde = '00:00:00';
                                            }
                                            include_once "../modell/ConfigSalario.class.php";
                                            $lote2 = new ConfigSalario();
                                            $matriz2 = $lote2->listaConfigSalarioUm(TRUE);

                                            while ($dados2 = $matriz2->fetchObject()) {
                                                if ($dados2->status_config_salario == true) {
                                                    $custo_mao_obra = ($dados2->valor / ($dados2->hora_semanal * $dados2->dias_semana)) / 60;
                                                } else {
                                                    echo "<script>alert('Opss. Algo deu errado na configuração de sálarios, estou direcionando você para verificação'); window.location.href='./lista_config_salario.php?p=erro';</script>";
                                                }
                                            }

                                            $resultado1 = explode(':', $hora_ini);
                                            $resultado2 = explode(':', $hora_fim);
                                            $resultado3 = explode(':', $hora_ini_tarde);
                                            $resultado4 = explode(':', $hora_fim_tarde);

                                            $resultadoManha = ((($resultado2[0] * 60)) + ($resultado2[1]) + (1 / 60)) - (($resultado1[0] * 60) + ($resultado1[1]) + (1 / 60));
                                            $resultadoTarde = ((($resultado4[0] * 60)) + ($resultado4[1]) + (1 / 60)) - (($resultado3[0] * 60) + ($resultado3[1]) + (1 / 60));

                                            $tempoR = ($resultadoManha - $resultadoTarde);

                                            $mao_obra_peca = (($tempoR * $custo_mao_obra) / $n_peca) + $mao_obra_indireta;
                                            $preco_produto = (($preco_kg * $peso_produto)) + $mao_obra_peca;
                                            //$preco_produto = (($preco_kg * $peso_produto) / 1000) + $mao_obra_peca;
                                            ?>
                                        </div>
                                    </div>

                                </div>
                                <div id="impressao" >
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="text-align: center">
                                            <?php print_r($title); ?>
                                        </div>
                                        <table class="table table-hover" border="1 solid" width="100%" id="tblEditavel" >
                                            <thead>
                                                <tr>                                            
                                                    <th>Valor</th>
                                                    <th>Kg/M</th>
                                                    <th>Início</th>
                                                    <th>Final</th>                                                
                                                    <th>Início</th>
                                                    <th>Final</th>                                                
                                                    <th>N° Peças</th>
                                                    <th>Sálario</th>
                                                    <th>Adicional</th>
                                                    <th>Custo Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>R$ <?= number_format($preco_kg, 2, ',', '.') ?></td>
                                                    <td><?= number_format($peso_produto, 3, ',', '.') ?></td>
                                                    <td><?= $hora_ini ?></td>
                                                    <td><?= $hora_fim ?></td>
                                                    <td><?= $hora_ini_tarde ?></td>
                                                    <td><?= $hora_fim_tarde ?></td>
                                                    <td><?= $n_peca ?></td>
                                                    <td>R$<?= number_format($custo_mao_obra, 3, ',', '.') ?></td>
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


                        </div>
                    </div>
                </div>
            </div>


            <script src="../bootstrap/assets/js/jquery.mask.min.js"></script>

            <script>
                $(document).ready(function () {
                    //                $('.date').mask('11/11/1111');
                    $('#preco_kg').mask('00.00');
                    $('#tempo').mask('00:00:00');
                    $('#hora_ini').mask('00:00:00');
                    $('#hora_fim').mask('00:00:00');
                    $('#hora_ini_tarde').mask('00:00:00');
                    $('#hora_fim_tarde').mask('00:00:00');
                    $('#mao_obra').mask('0.00');
                    //                $('.date_time').mask('99/99/9999 00:00:00');
                    //                $('.cep').mask('99999-999');
                    //                $('.phone').mask('9999-9999');
                    //                $('.phone_with_ddd').mask('(99) 9999-9999');
                    //                $('.phone_us').mask('(999) 999-9999');
                    //                $('.mixed').mask('AAA 000-S0S');
                });
            </script>


            <?php require_once "./actionRodape.php"; ?>


    </body>
</html>
