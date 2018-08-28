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
                                url: "alterarConfigCelula.php",
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
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">
                                <?php
                                $url = $_SERVER['REQUEST_URI'];
                                $part = explode("/", $url);
                                $part[3];

                                include_once '../modell/BancoDadosPDO.class.php';
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
                                        <form method="post" role="form">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="data_ini">Data inicial:</label>
                                                    <input type="date" id="data_ini" name="data_ini" class="form-control"  required="required"   />
                                                </div>
                                                <div class="form-group">
                                                    <label for="data_fim">Data Final:</label>
                                                    <input type="date" id="data_fim" name="data_fim" class="form-control"  required="required"   />
                                                </div>
                                                <div class="form-group">
                                                    <label for="hora_ini">Hora Inicial:</label>
                                                    <input type="time" id="hora_ini" name="hora_ini" class="form-control"  required="required"   />
                                                </div>

                                                <div class="form-group">
                                                    <label for="hora_fim">Hora Final:</label>
                                                    <input type="time" id="hora_fim" name="hora_fim" class="form-control"  required="required"   />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="hora_des">Tempo de Intervalos:</label>
                                                    <input type="time" id="hora_des" name="hora_des" class="form-control"  required="required"   />
                                                </div>                                    
                                                <div class="form-group">
                                                    <label for="desconto">Reajuste de Tempo para Mais:</label>
                                                    <select name="desconto" class="form-control" required="required">
                                                        <option value="">Selecione...</option>
                                                        <?php 
                                                        for($i=1; $i<=30; $i++)
                                                            echo "<option value='$i'>$i%</option>"
                                                        ?>
                                                    </select>

                                                    <!--<input type="number" id="desconto" name="desconto" class="form-control"  required="required"   />-->
                                                </div>                                    
                                                <div class="form-group">
                                                    <label for="dias">Dia da semana:</label>
                                                    <select name="dias" class="form-control" required="required">
                                                        <option value="">Selecione...</option>
                                                        <option value="Segunda">Segunda - Feira</option>
                                                        <option value="Terça">Terça - Feira</option>
                                                        <option value="Quarta">Quarta - Feira</option>
                                                        <option value="Quinta">Quinta - Feira</option>
                                                        <option value="Sexta">Sexta - Feira</option>
                                                        <option value="Sábado">Sábado</option>
                                                        <option value="Domingo">Domingo</option>
                                                    </select>
                                                    <!--<input type="text" id="dias" name="dias" class="form-control"  required="required"   />-->
                                                </div>
                                                <div class="form-group">
                                                    <label for="equivalencia">Equivalência:</label>
                                                    <input type="text" id="equivalencia" name="equivalencia" value="1 dia" class="form-control" disabled="disabled" />
                                                </div>                                    
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                    <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                                </div> 
                                            </div> 
                                        </form>



                                        <?php
                                        include_once "../modell/ConfigCelula.class.php";

//instancia a classe de controle
                                        $prod = new ConfigCelula();


                                        $data_ini = \filter_input(INPUT_POST, 'data_ini');
                                        $data_fim = \filter_input(INPUT_POST, 'data_fim');
                                        $hora_ini = \filter_input(INPUT_POST, 'hora_ini');
                                        $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                                        $hora_des = \filter_input(INPUT_POST, 'hora_des');
                                        $desconto = \filter_input(INPUT_POST, 'desconto');
                                        $dias_semana = '1';
                                        $dias = \filter_input(INPUT_POST, 'dias');
                                        $status = TRUE;
                                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                        if (isset($cadastro)) {
                                            if (empty($data_ini)) {
                                                echo "<div class='col-lg-12'>
                                                            <div class='alert alert-danger alert-dismissable'>
                                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                                Algum dos campos acima não foi preenchido corretamente.
                                                            </div>
                                                        </div>";
                                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                            } else {
                                                $status = $prod->cadastraConfig($data_ini, $data_fim, $hora_ini, $hora_fim, $hora_des, $status, $desconto, $dias_semana, $dias);
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
                    <div class="panel panel-default">
                        <div class="panel-heading" style="text-align: center">
                            Listagem
                        </div>
                        <table class="table table-hover" id="tblEditavel">
                            <thead>
                                <tr>
                                    <th>#</th>                                                                             
                                    <th>Início</th>
                                    <th>Final</th>
                                    <th>Início</th>
                                    <th>Final</th>
                                    <th>Desconto</th>
                                    <th>Reajuste</th>
                                    <th>Equivalência</th>
                                    <th>Dias</th>
                                    <th><i class="fa fa-low-vision"></i></th>
                                    <th><i class="fa fa-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody>                       

                                <?php
                                include_once "../modell/ConfigCelula.class.php";
                                $lote = new ConfigCelula();
                                $matriz = $lote->listaConfig();

                                while ($dados = $matriz->fetchObject()) {
                                    //echo $dados->data_fabri;                                        
                                    $data1 = explode("-", $dados->data_ini);
                                    $data2 = explode("-", $dados->data_fim);
//                                        $data2 = explode("-", $dados->data_final);

                                    if ($dados->status == true) {
                                        echo "<tr>
                                                    <td title='id_config'>" . $dados->id_config . "</td>                                                   
                                                    <td title='data_ini' class='editavel'><b>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>
                                                    <td title='data_fim' class='editavel'><b>" . $data2[2] . '/' . $data2[1] . '/' . $data2[0] . "</b></td>
                                                    <td title='hora_ini' class='editavel'>" . $dados->hora_ini . "</td>
                                                    <td title='hora_fim' class='editavel'>" . $dados->hora_fim . "</td>
                                                    <td title='hora_des' class='editavel'>" . $dados->hora_des . "</td>
                                                    <td title='desconto' class='editavel'>" . $dados->desconto . "%</td>
                                                    <td title='dias_semana' class='editavel'>" . $dados->dias_semana . " dia</td>
                                                    <td title='dias' class='editavel'>" . $dados->dias . "</td>
                                                    <td>     
							<span class='glyphicon glyphicon-eye-open' id='finalizar' value='finalizar'  onclick='finalizar(" . $dados->id_config . ");'></span> 													
                                                    </td>
                                                    <td>     
							<span class='glyphicon glyphicon-trash' id='deletar' value='deletar'  onclick='deletar(" . $dados->id_config . ");'></span> 													
                                                    </td>
                                              </tr>";
                                    } else {
                                        echo "<tr>
                                                    <td title='id_config'>" . $dados->id_config . "</td>                                                   
                                                    <td title='data_ini' class='editavel'><b>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>
                                                    <td title='data_fim' class='editavel'><b>" . $data2[2] . '/' . $data2[1] . '/' . $data2[0] . "</b></td>
                                                    <td title='hora_ini' class='editavel'>" . $dados->hora_ini . "</td>
                                                    <td title='hora_fim' class='editavel'>" . $dados->hora_fim . "</td>
                                                    <td title='hora_des' class='editavel'>" . $dados->hora_des . "</td>
                                                    <td title='desconto' class='editavel'>" . $dados->desconto . "%</td>
                                                    <td title='dias_semana' class='editavel'>" . $dados->dias_semana . " dia</td>
                                                    <td title='dias' class='editavel'>" . $dados->dias . "</td>
                                                    <td>    
							<span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_config . ");'></span> 													
                                                    </td>
                                                    <td>    
							<span class='glyphicon glyphicon-trash' id='deletar' value='deletar'  onclick='deletar(" . $dados->id_config . ");'></span> 													
                                                    </td>
                                              </tr>";
                                    }
                                }//<button title='finalizar lote' type='submit' id='ativar' value='ativar'  onclick='ativar(" . $dados->id . ");' class='btn btn-default'> </button>
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <script src="../ajax/config_celula/config_celula.js"></script>
        <script src="../ajax/config_celula/deletar_config_celula.js"></script>
        <?php require_once "./actionRodape.php"; ?>      
    </body>
</html>