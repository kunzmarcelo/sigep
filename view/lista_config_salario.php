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

                <!-- Custom CSS -->


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

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
                            //echo $part[3];

                            include_once '../modell/Produto.class.php';
                            $con = new BancoDadosPDO();
                            $titulo = $con->listarUm("menu_filho", "link like '$part[3]'");
                            $resultado = $titulo->fetchObject();



                            echo $resultado->nome;
                            ?>


                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

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
                                            <label for="valor">Valor de Salário:</label>
                                            <input type="number" id="valor" name="valor" class="form-control" required="required"  />
                                        </div>
                                        <div class="form-group">
                                            <label for="n_funcionario">Número de Funcionários:</label>
                                            <input type="number" id="n_funcionario" name="n_funcionario" class="form-control"  required="required"  />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="hora_semanal">Horas Semanal:</label>
                                            <input type="number" id="hora_semanal" name="hora_semanal" class="form-control"  required="required"   />
                                        </div>                                    

                                        <div class="form-group">
                                            <label for="dias_semana">Dias Trabalhados:</label>
                                            <input type="number" id="dias_semana" name="dias_semana" class="form-control"  required="required"   />
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
                                include_once "../modell/ConfigSalario.class.php";

//instancia a classe de controle
                                $prod = new ConfigSalario();

                                $valor = \filter_input(INPUT_POST, 'valor');
                                $n_funcionario = \filter_input(INPUT_POST, 'n_funcionario');
                                $hora_semanal = \filter_input(INPUT_POST, 'hora_semanal');
                                $data = date('Y-m-d');
                                $dias_semana = \filter_input(INPUT_POST, 'dias_semana');
                                $status_config_salario = TRUE;
                                
                                $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                if (isset($cadastro)) {
                                    if (empty($valor)) {
                                        echo "<div class='col-lg-12'>
                                                            <div class='alert alert-danger alert-dismissable'>
                                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                                Algum dos campos acima não foi preenchido corretamente.
                                                            </div>
                                                        </div>";
                                        //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                    } else {
                                        $status = $prod->cadastraConfigSalario($valor, $n_funcionario, $hora_semanal, $data, $dias_semana, $status_config_salario);
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










                <div class="panel panel-default">            
                    <div class="panel-heading" style="text-align: center;">
                        Listagem
                    </div>
                    <div class="table-responsive">                       
                        <table class="table table-hover" id="tblEditavel">
                            <thead>
                                <tr>
                                    <th><img src='../bibliotecas/img/1486591262_id.png'></th>
                                    <th>Data Cad.</th>
                                    <th>Salário</th>
                                    <th>N° de func.</th>
                                    <th>Hs. Semanais</th>
                                    <th>Dias</th>
                                    <th>Hs. Mensal</th>
                                    <th>Custo h.</th>
                                    <th>H * n° func.</th>
                                    <th>Valor hora n° func</th>
                                    <th>Custo min.</th>
                                    <th><i class='fa fa-low-vision'></i></th>
                                </tr>
                            </thead>                                
                            <tbody>                       

                                <?php
                                include_once "../modell/ConfigSalario.class.php";
                                $lote = new ConfigSalario();
                                $matriz = $lote->listaConfigSalario();
                                $total = 0;
                                $valor_dia = 0;
                                $total_dia = 0;
                                while ($dados = $matriz->fetchObject()) {

                                    $DATALOTE = explode("-", $dados->data);
                                    // $data = $DATALOTE[2] . '/' . $DATALOTE[1];
                                    $data = $DATALOTE[2] . '/' . $DATALOTE[1] . '/' . $DATALOTE[0];

                                    $horas_semanais = $dados->hora_semanal * $dados->dias_semana;
                                    $custo_hora = $dados->valor / $horas_semanais;
                                    $custo_minuto = $custo_hora / 60;

                                    // $custo_minuto2 = ($dados->valor/($dados->hora_semanal*$dados->dias_semana))/60;

                                    $custo_funcionario = $custo_hora * $dados->n_funcionario;
                                    $custo_minuto_n_funcionario = $custo_funcionario / 60;

                                    if ($dados->status_config_salario == true) {
                                        echo "<tr>
                                                    <td title='id_config_salario'>" . $dados->id_config_salario . "</td>
                                                    <td title='data de cadastro'>" . $data . "</td>
                                                    <td title='valor' class='editavel'><b> R$ " . number_format($dados->valor, 2, ',', '.') . "</b></td>
                                                    <td title='n_funcionario' class='editavel'>" . $dados->n_funcionario . "</td>
                                                    <td title='hora_semanal' class='editavel'>" . $dados->hora_semanal . ":00:00</td>
                                                    <td title='dias_semana' class='editavel'>" . $dados->dias_semana . "</td>
                                                    <td title='horas_semanais' class='editavel'>" . $horas_semanais . ":00:00</td>
                                                    <td title='salário / horas semanais' class='editavel'>R$ " . number_format($custo_hora, 2, ',', '.') . "h</td>
                                                    <td title='Custo da hora x n_funcionarios' class='editavel'>R$ " . number_format($custo_funcionario, 2, ',', '.') . "</td>                                                    
                                                    <td title='H * n° func. /60' class='editavel'>R$ " . number_format($custo_minuto_n_funcionario, 2, ',', '.') . "</td>
                                                    <td title='custa da hora / 60 minutos' class='editavel'>R$ " . number_format($custo_minuto, 3, ',', '.') . "h</td>
                                                        <td>
							<span class='glyphicon glyphicon-eye-open' id='finalizar' value='finalizar'  onclick='finalizar(" . $dados->id_config_salario . ");'></span>
                                                    </td>
                                                </tr>";
                                    } else {
                                        echo "<tr>
                                                    <td title='id_config_salario'>" . $dados->id_config_salario . "</td>
                                                    <td title='data de cadastro'>" . $data . "</td>
                                                    <td title='valor' class='editavel'><b> R$ " . number_format($dados->valor, 2, ',', '.') . "</b></td>
                                                    <td title='n_funcionario' class='editavel'>" . $dados->n_funcionario . "</td>
                                                    <td title='hora_semanal' class='editavel'>" . $dados->hora_semanal . ":00:00</td>
                                                    <td title='dias_semana' class='editavel'>" . $dados->dias_semana . "</td>
                                                    <td title='horas_semanais' class='editavel'>" . $horas_semanais . ":00:00</td>
                                                    <td title='salário / horas semanais' class='editavel'>R$ " . number_format($custo_hora, 2, ',', '.') . "h</td>
                                                    <td title='Custo da hora x n_funcionarios' class='editavel'>R$ " . number_format($custo_funcionario, 2, ',', '.') . "</td>                                                    
                                                    <td title='H * n° func. /60' class='editavel'>R$ " . number_format($custo_minuto_n_funcionario, 2, ',', '.') . "</td>
                                                    <td title='custa da hora / 60 minutos' class='editavel'>R$ " . number_format($custo_minuto, 3, ',', '.') . "h</td>
                                                        <td>
							<span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_config_salario . ");'></span>
                                                    </td>
                                                </tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!--</div>-->
                </div>
                <?php
                if ($part[3] == 'lista_config_salario.php?p=erro') {
                    ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-red">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-exclamation-circle fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge">Erro</div>
                                            <!--<div>Aviso!</div>-->
                                        </div>                                        
                                    </div>
                                </div>

                                <a href="#">
                                    <div class="panel-footer">
                                        <span class="pull-left">Oops. Algo deu errado. Você deve ter desativado a configuração, ative-a ou cadastre nova configuração.</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>

                            </div>
                        </div>
                    </div>



                    <?php
                }
                /* Captação de dados */
                $buffer = ob_get_contents(); // Obtém os dados do buffer interno
                $filename = "code.html"; // Nome do arquivo HTML
                file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
                ?>
                <div class="form-group">
                    <div class="col-xs-12 col-sm-12">
                        <!--                            <label for="legenda">¹Total mês = Valor do salário * N° de funcionários.</label><br>
                                                    <label for="legenda">²Total dia = Total mês / Dias trabalhados.</label><br>
                                                    <label for="legenda">³Valor dia = Total dia / Tempo calendário.</label><br>-->
                    </div>
                </div>
            </div>
        </div>
        <script src="../ajax/config_salario/config_salario.js"></script>


        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>