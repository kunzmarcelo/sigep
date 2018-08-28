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
                                url: "alterarCelula.php",
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
                                                    <label for="funcionarios">Nome das pessoas que atuam nessa célula:</label>
                                                    <input type="text" id="funcionarios" name="funcionarios" class="form-control" placeholder="João/Maria/Tereza/etc...">
                                                </div>                                  
                                                <div class="form-group">
                                                    <label for="pessoas_celula">Número de pessoas por célula de trabalho:</label>
                                                    <input type="number" id="pessoas_celula" name="pessoas_celula" class="form-control" placeholder="" required="required"  />
                                                </div> 
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="numero_celula">Número da célula de trabalho:</label>
                                                    <input type="number" id="numero_celula" name="numero_celula" class="form-control" placeholder="" required="required"  />
                                                </div>

                                                <div class="form-group">
                                                    <label for="setor" class="tamanho-fonte">Setor de atuação dessa célula:</label><small> (Campo Obrigatório)</small>
                                                    <select name="setor" class="form-control" required="required" >                                       
                                                        <?php
                                                        echo "<option value=''>Selecione ...</option>";
                                                        include_once '../modell/Setores.class.php';
                                                        $lote = new Setores();
                                                        $matriz = $lote->listaSetor();

                                                        while ($dados = $matriz->fetchObject()) {
                                                            if ($dados->status_setor == TRUE) {
                                                                $cod = $dados->id_setor;
                                                                $n_lote = $dados->descricao_setor;
                                                                $obs = $dados->obs;
                                                                echo "<option value=" . $n_lote . ">" . $n_lote . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>                            
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
                                        include_once "../modell/CelulaTrabalho.class.php";

//instancia a classe de controle
                                        $prod = new CelulaTrabalho();

                                        $pessoas_celula = \filter_input(INPUT_POST, 'pessoas_celula');
                                        $funcionarios = \filter_input(INPUT_POST, 'funcionarios');
                                        $numero_celula = \filter_input(INPUT_POST, 'numero_celula');
                                        $setor = \filter_input(INPUT_POST, 'setor');
                                        $data = date('Y-m-d');
                                        $status_celula = TRUE;
//                       
                                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                        if (isset($cadastro)) {
                                            if (empty($pessoas_celula)) {
                                                echo "<div class='col-lg-12'>
                                                            <div class='alert alert-danger alert-dismissable'>
                                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                                Algum dos campos acima não foi preenchido corretamente.
                                                            </div>
                                                        </div>";
                                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                            } else {
                                                $status = $prod->cadastraCelula($pessoas_celula, $data, $numero_celula, $funcionarios, $setor, $status_celula);
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

                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align: center">
                        Listagem
                    </div>                  
                    <table class="table table-hover" id="tblEditavel">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Célula</th>
                                <th>Qtd.</th>
                                <th>Nomes</th>
                                <th>Setor</th>
                                <th>Data Cadastro</th>                                        
                                <th><i class="fa fa-low-vision"></i></th>
                            </tr>
                        </thead>
                        <tbody>                       

                            <?php
                            include_once "../modell/CelulaTrabalho.class.php";
                            $lote = new CelulaTrabalho();
                            $matriz = $lote->listaCelula();

                            while ($dados = $matriz->fetchObject()) {
                                //echo $dados->data_fabri;                                        
                                $data1 = explode("-", $dados->data);
                                if ($dados->status_celula == TRUE) {
                                    echo "<tr>
                                            <td title='id_celula'>" . $dados->id_celula . "</td>                                                   
                                            <td title='numero_celula' class='editavel'>" . $dados->numero_celula . "</td>
                                            <td title='pessoas_celula' class='editavel'>" . $dados->pessoas_celula . "</td>                                                   
                                            <td title='funcionarios' class='editavel'>" . $dados->funcionarios . "</td>
                                            <td title='setor' class='editavel'>" . $dados->setor . "</td>
                                            <td title='data de cadastro' >" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>
                                            <td>     
                                                <span class='glyphicon glyphicon-eye-open' id='desativar' value='desativar'  onclick='desativar(" . $dados->id_celula . ");'></span> 													
                                            </td>
                                                </tr>";
                                } else {
                                    echo "<tr>
                                            <td title='id_celula'>" . $dados->id_celula . "</td>                                                   
                                            <td title='numero_celula' class='editavel'>" . $dados->numero_celula . "</td>
                                            <td title='pessoas_celula' class='editavel'>" . $dados->pessoas_celula . "</td>                                                   
                                            <td title='funcionarios' class='editavel'>" . $dados->funcionarios . "</td>
                                            <td title='setor' class='editavel'>" . $dados->setor . "</td>
                                            <td title='data de cadastro' >" . $data1[2] . '/' . $data1[1] . '/' . $data1[0] . "</b></td>
                                            <td>     
                                                <span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_celula . ");'></span> 													
                                            </td>
                                           </tr>";
                                }
                            }//<button title='finalizar lote' type='submit' id='ativar' value='ativar'  onclick='ativar(" . $dados->id . ");' class='btn btn-default'> </button>
                            ?>
                        </tbody>
                    </table>
                </div>
                <!--</div>-->
            </div>

        </div>

        <script src="../ajax/celula/ativa_celula.js"></script>

        <?php require_once "./actionRodape.php"; ?>
    </body>
</html>