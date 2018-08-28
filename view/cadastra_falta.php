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

        <script type="text/javascript">
            $(document).ready(function () {
                $('#tblEditavel tbody tr td.editavel').dblclick(function () {
                    if ($('td > input').length > 0) {
                        return;
                    }
                    var conteudoOriginal = $(this).text();
                    var novoElemento = $('<input/>', {type: 'text', value: conteudoOriginal});
                    $(this).html(novoElemento.bind('blur keydown', function (e) {
                        var keyCode = e.which;
                        var conteudoNovo = $(this).val();
                        if (keyCode == 13 && conteudoNovo != '' && conteudoNovo != conteudoOriginal) {
                            var objeto = $(this);
                            $.ajax({
                                type: "POST",
                                url: "alterarFalta.php",
                                data: {
                                    id: $(this).parents('tr').children().first().text(),
                                    campo: $(this).parent().attr('title'),
                                    valor: conteudoNovo
                                },
                                success: function (result) {
                                    objeto.parent().html(conteudoNovo)
                                    $('body').append(result);
                                    location.reload();
                                },
                            })
                        }
                        if (e.type == "blur") {
                            $(this).parent().html(conteudoOriginal);
                        }
                    }));

                    $(this).children().select();
                })
            })
        </script>

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
                                                    <label for="id_funcionario" class="tamanho-fonte">Funcionário:</label><small> (Campo Obrigatório)</small>
                                                    <select name="id_funcionario" class="form-control" required="required">                                       
                                                        <?php
                                                        echo "<option value=''><b>Selecione ...</b></option>";
                                                        include_once '../modell/Funcionario.class.php';
                                                        $fun = new Funcionario();
                                                        $matriz = $fun->listaFuncionario();

                                                        while ($dados = $matriz->fetchObject()) {
                                                            if ($dados->ativo == true) {
                                                                $cod = $dados->id_funcionario;
                                                                $nome = $dados->nome;
                                                                echo "<option value=" . $cod . ">" . $nome . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>                            
                                                </div>
                                                <div class="form-group">
                                                    <label for="data">Data:</label>
                                                    <input type="date" id="data" name="data" class="form-control" required="required" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="hora_ini">Hora de Início:</label>
                                                    <input type="time" id="hora_ini" name="hora_ini" class="form-control" step='1' min="00:00:00" max="23:59:00" required="required"  />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="hora_fim">Hora Final:</label>
                                                    <input type="time" id="hora_fim" name="hora_fim" class="form-control" step='1' min="00:00:00" max="23:59:00" required="required"  />
                                                </div>
                                                <div class="form-group">
                                                    <label for="motivo">Motivo da falta:</label>
                                                    <input type="text" id="motivo" name="motivo" class="form-control"  required="required"/>
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
                                        include_once "../modell/ControleFalta.class.php";

//instancia a classe de controle
                                        $prod = new ControleFalta();

                                        $id_funcionario = \filter_input(INPUT_POST, 'id_funcionario');
                                        $data = \filter_input(INPUT_POST, 'data');
                                        $hora_ini = \filter_input(INPUT_POST, 'hora_ini');
                                        $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                                        $motivo = \filter_input(INPUT_POST, 'motivo');
                                        $status = true;
//                       
                                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                        if (isset($cadastro)) {
                                            if (empty($motivo)) {
                                               echo "<div class='col-lg-12'>
                                                            <div class='alert alert-danger alert-dismissable'>
                                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                                Algum dos campos acima não foi preenchido corretamente.
                                                            </div>
                                                        </div>";
                                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                            } else {
                                                $status = $prod->cadastraFalta($id_funcionario, $data, $hora_ini, $hora_fim, $motivo, $status);
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
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <!-- /.panel-heading -->


                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center">
                                Listagem
                            </div>         
                            <table class="table table-hover" id="tblEditavel">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Funcionário</th>
                                        <th>Data</th>
                                        <th>Hora Ini</th>
                                        <th>Hora fim</th>
                                        <th>Motivo</th>
                                        <th>Tempo</th>
                                        <th><i class="fa fa-trash"></i></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    include_once "../modell/ControleFalta.class.php";
                                    $lote = new ControleFalta();
                                    $matriz = $lote->listaFalta();

                                    while ($dados = $matriz->fetchObject()) {
                                        //echo $dados->data_fabri;                                        
                                        $data1 = explode("-", $dados->data);
                                        $data = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
//                                        $data2 = explode("-", $dados->data_final);
                                        //if ($dados->status == true) {

                                        $hora_ini1 = explode(":", "$dados->hora_ini");
                                        $hora_fim1 = explode(":", $dados->hora_fim);
//                                        $hora_int1 = explode(":", "02:05:00");

                                        $tempo1 = (($hora_ini1[0] * 3600) + ($hora_ini1[1] * 60) + ($hora_ini1[2]));
                                        $tempo2 = (($hora_fim1[0] * 3600) + ($hora_fim1[1] * 60) + ($hora_fim1[2]));
                                        //$tempo3 = (($hora_int1[0] * 3600) + ($hora_int1[1] * 60) + ($hora_int1[2]));


                                        $resultado_tempo = ($tempo2 - $tempo1);
//                                        $resultado_tempo = ($tempo2 - $tempo1) - $tempo3;
                                        $total_segundos = $resultado_tempo;

                                        $horas = floor($total_segundos / (60 * 60));
                                        $sobra_horas = ($total_segundos % (60 * 60));
                                        $minutos = floor($sobra_horas / 60);
                                        $sobra_minutos = ($sobra_horas % 60);
                                        $segundos = $sobra_minutos;

                                        if ($horas < 10) {
                                            $horas = "0$horas";
                                        } else {
                                            $horas = $horas;
                                        }

                                        $tempo = "$horas:$minutos:0$segundos";


                                        echo "<tr>
                                                    <td title='id_falta'>" . $dados->id_falta . "</td>
                                                    <td title='funcionário'>" . $dados->nome . "</td>
                                                    <td title='data'>" . $data . "</b></td>
                                                    <td title='hora_ini' class='editavel'>" . $dados->hora_ini . "</td>
                                                    <td title='hora_fim' class='editavel'>" . $dados->hora_fim . "</td>
                                                    <td title='motivo' class='editavel'>" . $dados->motivo . "</td>
                                                    <td title='tempo'><b>" . $tempo . "</b></td>
                                             <td>     
							<span class='glyphicon glyphicon-trash' id='deletar' value='deletar'  onclick='deletar(" . $dados->id_falta . ");'></span> 													
                                                    </td>
                                              </tr>";
                                    }//<button title='finalizar lote' type='submit' id='ativar' value='ativar'  onclick='ativar(" . $dados->id . ");' class='btn btn-default'> </button>
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../ajax/falta/deletar_falta.js"></script>

        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>