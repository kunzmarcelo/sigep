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
                                url: "alterarConfig.php",
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
                                    <div class="col-lg-6">
                                        <form method="post" role="form">                                                                         
                                            <div class="form-group">
                                                <label for="meta_faturamento">Meta de faturamento:</label>
                                                <input type="number" id="meta_faturamento" name="meta_faturamento" class="form-control" required="required"  />
                                            </div>
                                            <div class="form-group">
                                                <label for="meta_producao">Meta de produção:</label>
                                                <input type="number" id="meta_producao" name="meta_producao" class="form-control"  required="required"  />
                                            </div>                                    
                                            <div class="form-group">
                                                <label for="data_ini">Data inicial:</label>
                                                <input type="date" id="data_ini" name="data_ini" class="form-control"  required="required"   />
                                            </div>                                    
                                            <div class="form-group">
                                                <label for="data_fim">Data Final:</label>
                                                <input type="date" id="data_fim" name="data_fim" class="form-control"  required="required"   />
                                            </div>                                    

                                            <div class="form-group">
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                            </div>                                
                                        </form>
                                        <?php
                                        include_once "../modell/Config.class.php";

//instancia a classe de controle
                                        $prod = new Config();

                                        $meta_faturamento = \filter_input(INPUT_POST, 'meta_faturamento');
                                        $meta_producao = \filter_input(INPUT_POST, 'meta_producao');
                                        $data_ini = \filter_input(INPUT_POST, 'data_ini');
                                        $data_fim = \filter_input(INPUT_POST, 'data_fim');
                                        $hora_ini = \filter_input(INPUT_POST, 'hora_ini');
                                        $hora_fim = \filter_input(INPUT_POST, 'hora_fim');
                                        $hora_des = \filter_input(INPUT_POST, 'hora_des');
                                        $desconto = \filter_input(INPUT_POST, 'desconto');
                                        $status = TRUE;
                                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                        if (isset($cadastro)) {
                                            if (empty($meta_faturamento)) {
                                                echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                            } else {
                                                $status = $prod->cadastraConfig($meta_faturamento, $meta_producao, $data_ini, $data_fim, $hora_ini, $hora_fim, $hora_des, $status, $desconto);
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
                                        <th>Faturamento Mês</th>
                                        <th>Produção Mês</th>
                                        <th>Data Ini</th>
                                        <th>Data Fim</th>
                                       <th><i class="fa fa-low-vision"></i></th>
                                    </tr>
                                </thead>
                                <tbody>                       

                                    <?php
                                    include_once "../modell/Config.class.php";
                                    $lote = new Config();
                                    $matriz = $lote->listaConfig();

                                    while ($dados = $matriz->fetchObject()) {
                                        //echo $dados->data_fabri;                                        
                                        $data1 = explode("-", $dados->data_ini);
                                        $data2 = explode("-", $dados->data_fim);
//                                        $data2 = explode("-", $dados->data_final);

                                        if ($dados->status == true) {
                                            echo "<tr>
                                                    <td title='id_config'>" . $dados->id_config . "</td>
                                                    <td title='meta_faturamento' class='editavel'>" . number_format($dados->meta_faturamento, 2, ',', '.') . "</td>
                                                    <td title='meta_producao' class='editavel'>" . number_format($dados->meta_producao, 0, ',', '.') . "</td>
                                                    <td title='data_ini' class='editavel'>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>
                                                    <td title='data_fim' class='editavel'>" . $data2[2] . '/' . $data2[1] . '/' . $data2[0] . "</b></td>                                                    
                                                    <td>     
							<span class='glyphicon glyphicon-eye-open' id='finalizar' value='finalizar'  onclick='finalizar(" . $dados->id_config . ");'></span> 													
                                                    </td>
                                              </tr>";
                                        } else {
                                            echo "<tr>
                                                    <td title='id_config'>" . $dados->id_config . "</td>
                                                    <td title='meta_faturamento' class='editavel'>" . number_format($dados->meta_faturamento, 2, ',', '.') . "</td>
                                                    <td title='meta_producao' class='editavel'>" . number_format($dados->meta_producao, 0, ',', '.') . "</td>
                                                   <td title='data_ini' class='editavel'>" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>
                                                    <td title='data_fim' class='editavel'>" . $data2[2] . '/' . $data2[1] . '/' . $data2[0] . "</b></td>                                                 
                                                    <td>     
							<span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_config . ");'></span> 													
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
        </div>

        <script src="../ajax/config.js"></script>
<?php require_once "./actionRodape.php"; ?>    
    </body>
</html>


