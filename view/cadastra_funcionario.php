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
                                url: "alterarFuncionario.php",
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
                                                    <label for="nome">Nome</label>
                                                    <input type="text" id="nome" name="nome" class="form-control" placeholder="Nome Funcionário" required="required"  />
                                                </div>
                                                <div class="form-group">
                                                    <label for="departamento">Departamento:</label>
                                                    <select name="departamento" class="form-control">
                                                        <!--<option value="Selecione">Selecione ...</option>-->
                                                        <option value="Fábrica">Fábrica</option>
                                                        <option value="Escritório">Escritório</option>
                                                    </select>
                                                    <!--<input type="text" id="data_fabri" name="data_fabri" class="form-control" placeholder="vlr_vista"   />-->
                                                </div>
                                            </div>
                                            <div class="col-lg-6">

                                                <div class="form-group">
                                                    <label for="id_setor">Setor de operação:</label>

                                                    <select name="id_setor" class="form-control" required="required" >                                       
                                                        <?php
                                                        echo "<option value=''>Selecione</option>";
                                                        include_once '../modell/Setores.class.php';
                                                        $fun = new Setores();
                                                        $matriz = $fun->listaSetor();

                                                        while ($dados = $matriz->fetchObject()) {
                                                            if ($dados->status_setor == true) {
                                                                $cod = $dados->id_setor;
                                                                $nome = $dados->descricao_setor;
                                                                echo "<option value='" . $cod . "'>" . $nome . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>                                                
                                                </div>
                                                <!--                                                <div class="form-group">                                            
                                                                                                    <label for="ativo">Status:</label>
                                                                                                    <select name="ativo" class="form-control">
                                                                                                        <option value="Selecione">Selecione ...</option>
                                                                                                        <option value="1" active>Ativo</option>
                                                                                                        <option value="0">Desativado</option>
                                                                                                    </select>
                                                                                                    <input type="text" id="data_fabri" name="data_fabri" class="form-control" placeholder="vlr_vista"   />
                                                                                                </div>-->
                                                <div class="form-group">
                                                    <label for="sexo">Sexo:</label>
                                                    <select name="sexo" class="form-control">
                                                        <!--<option value="Selecione">Selecione ...</option>-->
                                                        <option value="Woman">Feminino</option>
                                                        <option value="Man">Masculino</option>
                                                    </select>
                                                    <!--<input type="text" id="data_fabri" name="data_fabri" class="form-control" placeholder="vlr_vista"   />-->
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
                                        include_once "../modell/Funcionario.class.php";

//instancia a classe de controle
                                        $fun = new Funcionario();

                                        $nome = \filter_input(INPUT_POST, 'nome');
                                        $departamento = \filter_input(INPUT_POST, 'departamento');
                                        $ativo = TRUE;
                                        $sexo = \filter_input(INPUT_POST, 'sexo');
                                        $id_setor = \filter_input(INPUT_POST, 'id_setor');
                                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');
                                        $update_date = 'NULL';
                                        if (isset($cadastro)) {
                                            if (empty($nome)) {
                                                echo "<div class='col-lg-12'>
                                                        <div class='alert alert-danger alert-dismissable'>
                                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                            Algum dos campos acima não foi preenchido corretamente.
                                                        </div>
                                                        </div>";
                                            } else {
                                                $status = $fun->cadastraFuncionario($nome, $departamento, $ativo, $sexo, $update_date, $id_setor);
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
                                <th>Nome</th>
                                <th>Departamento</th>
                                <th>Setor</th>
                                <th><i class="fa fa-low-vision"></i></th>
                            </tr>
                        </thead>
                        <tbody>                       

                            <?php
                            include_once "../modell/Funcionario.class.php";
                            $lote = new Funcionario();
                            $matriz = $lote->listaFuncionario();
                            if (empty($matriz)) {
                                echo "<div class='alert alert-info alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Não possui nenhum registro armazenado.
                                            </div>";
                            } else {
                                while ($dados = $matriz->fetchObject()) {
                                    if ($dados->ativo == true) {
                                        echo "<tr>
                                                    <td title='id'>" . $dados->id_funcionario . "</td>
                                                    <td title='nome' class='editavel'>" . $dados->nome . "</td>
                                                    <td title='departamento' class='editavel'>" . $dados->departamento . "</td>
                                                    <td title='id_setor' class='editavel'>" . $dados->descricao_setor . "</td>
                                                        <td>     
							<span class='glyphicon glyphicon-eye-open' id='desativar' value='desativar'  onclick='desativar(" . $dados->id_funcionario . ");'></span> 													
                                                    </td>
                                                </tr>";
                                    } else {
                                        echo "<tr>
                                                    <td title='id'>" . $dados->id_funcionario . "</td>
                                                    <td title='nome' class='editavel'>" . $dados->nome . "</td>
                                                    <td title='departamento' class='editavel'>" . $dados->departamento . "</td>
                                                    <td title='id_setor' class='editavel'>" . $dados->descricao_setor . "</td>
                                                    <td>     
							<span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_funcionario . ");'></span> 													
                                                    </td>
                                                </tr>";
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="../ajax/desativar_funcionario.js"></script>
<?php require_once "./actionRodape.php"; ?>
    </body>
</html>