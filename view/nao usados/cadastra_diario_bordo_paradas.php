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
            function alteraPonto(valorInput) {

                //alert("Valor original: " + valorInput.val());
                //document.getElementById('funcao').value='<span class="label label-danger">'+ valorInput.val()+'</span>' ; 
                //alert("Valor com virgula: " + valorInput.val().replace(".", ","));
                //$(valorInput).val(valorInput.val().replace("<span class='label label-danger'>"+valorInput.val()+"</span>"));
            }


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
                    <a href="lista_diario_bordo.php" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-th-list"></span> Listar</a>

<!--    <a href="pgAdmin.php?pagina=cadastraEquipe" class="btn btn-info"> <span class='glyphicon glyphicon-plus'></span> Adicionar</a>-->
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading centraliza">
                            Cadastro de Diário de Bordo Paradas
                        </div>        
                        <div class="panel-body">
                            <form method="post" class="form-horizontal">
                                <div class="col-lg-6">
                                    <div class="form-group">                                            
                                        <label for="id_maquina" class="tamanho-fonte">Máquina:</label><small> (Campo Obrigatório)</small>
                                        <select name="id_maquina" class="form-control"  >                                       
                                            <?php
//                                            echo "<option value='0'></option>";
                                            include_once '../modell/Maquina.class.php';
                                            $maquina = new Maquina();
                                            $matriz = $maquina->listaMaquina();

                                            while ($dados = $matriz->fetchObject()) {
                                                if ($dados->status == true) {
                                                    $cod = $dados->id_maquina;
                                                    $nome = $dados->numero;
                                                    $descricao = $dados->descricao;
                                                    echo "<option value=" . $cod . ">" . $nome . ' - ' . $descricao . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>                            
                                    </div>
                                    <div class="form-group">                                            
                                        <label for="id_funcionario" class="tamanho-fonte">Funcionário:</label><small> (Campo Obrigatório)</small>
                                        <select name="id_funcionario" class="form-control" >                                       
                                            <?php
                                            echo "<option value='Selecione'><b>Selecione ...</b></option>";
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
                                        <label for="data">Data:</label><small> (Campo Obrigatório)</small>
                                        <input type="date" id="data" name="data" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="turno" class="tamanho-fonte">Turno:</label><small> (Campo Obrigatório)</small>
                                        <select name="turno" class="form-control" >                                       
                                            <option value="Manhã">Manhã</option>
                                            <option value="Tarde">Tarde</option>
                                        </select>                            
                                    </div>                                   
                                    <div class="form-group">
                                        <label for="hora_ini">Hora inicial:</label>
                                        <input type="text" id="hora_ini" name="hora_ini" class="form-control" placeholder=""  >
                                    </div>
                                    <div class="form-group">
                                        <label for="hora_fim">Hora final:</label>
                                        <input type="text" id="hora_fim" name="hora_fim" class="form-control" placeholder="" >
                                    </div>
                                    <div class="form-group">
                                            <label for="motivo">Motivo Parada:</label><small> (Campo Obrigatório)</small>
                                            <select name="motivo" class="form-control" >                                                
                                                <option value="Falta-Luz;">Falta de Luz</option>
                                                <option value="Falta-Material;">Falta de Material</option>                                                
                                                <option value="Não-Compareceu;">Não Compareceu</option>
                                                <option value="Saida;">Saida do posto de trabalho</option>
                                            </select>
                                            <!--<input type="text" id="motivo" name="motivo" class="form-control" placeholder=""   />-->
                                        </div>                                                                        
                                    <div class="form-group">
                                        <label for="obs">Observação:</label>
                                        <input type="text" id="obs" name="obs" class="form-control" placeholder="Ex: Acabamento"   />
                                    </div>
                                    <div class="form-group">                                   
                                        <button type="submit" name="cadastrar2" value="cadastrar2" class="btn btn-info">Cadastrar</button>
                                        <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    

                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php
                        include_once "../modell/DiarioBordo.class.php";

//instancia a classe de controle
                        $diario = new DiarioBordo();

                        $id_maquina = \filter_input(INPUT_POST, 'id_maquina');
                        $data = \filter_input(INPUT_POST, 'data');
                        $id_funcionario = \filter_input(INPUT_POST, 'id_funcionario');
                        $turno = \filter_input(INPUT_POST, 'turno');
                        $id_produto = 1;
                        $id_funcao = 1;
                        $hora_ini = \filter_input(INPUT_POST, 'hora_ini');
                        $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                        $motivo = \filter_input(INPUT_POST, '');
                        $pecas_boas = NULL;
                        $pecas_ruins = NULL;
                        $obs = \filter_input(INPUT_POST, 'obs');
                        $cadastro = \filter_input(INPUT_POST, 'cadastrar2');

                        if (isset($cadastro)) {
                            //var_dump($turno, $id_maquina, $id_produto, $funcao, $hora_ini, $hora_fim, $motivo, $total_peca, $n_peca_boa, $n_peca_ruim, $obs, $id_funcionario, $data);

                            $status = $diario->cadastraDiarioBordo($id_maquina, $data, $id_funcionario, $turno, $id_produto, $id_funcao, $pecas_boas, $pecas_ruins, $hora_ini, $hora_fim, $motivo, $obs);
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

            <script src="../bootstrap/assets/js/jquery.mask.min.js"></script>

            <script>
            $(document).ready(function () {
                $('#hora_ini').mask('00:00:00');
                $('#hora_fim').mask('00:00:00');
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