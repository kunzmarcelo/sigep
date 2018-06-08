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
                                                <label for="descricao">Descrição:</label>
                                                <input type="text" id="descricao" name="descricao" class="form-control" placeholder="descrição" required="required"/>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                            </div> 
                                    </div>
                                    </form>

                                    <?php
                                    include_once "../modell/Setores.class.php";

//instancia a classe de controle
                                    $prod = new Setores();

                                    $descricao = \filter_input(INPUT_POST, 'descricao');
                                    $ordem = 0;

                                    $status_setor = TRUE;
//                       
                                    $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                    if (isset($cadastro)) {
                                        if (empty($descricao)) {
                                            echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                            //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                        } else {
                                            $status = $prod->cadastraSetor($descricao, $ordem, $status_setor);
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
                        <div class="panel panel-default">            
                            <div class="panel-heading" style="text-align: center;">
                                Listagem
                            </div>
                            <div class="table-responsive">                       
                                <table class="table table-hover" id="tblEditavel">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Descrição</th>
                                            <th>Ordem</th>
                                            <th><i class="fa fa-low-vision"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $matriz = $prod->listaSetor();
                                        while ($dados = $matriz->fetchObject()) {
                                            if ($dados->status_setor == TRUE) {
                                                echo "<tr>
                                                <td title='id_setor'>" . $dados->id_setor . "</td>
                                                <td title='descricao'>" . $dados->descricao . "</td>
                                                <td title='ordem' class='editavel'>" . $dados->ordem . "</td>
                                               <td>     
                                                    <span class='glyphicon glyphicon-eye-open' id='desativar' value='desativar'  onclick='desativar(" . $dados->id_setor . ");'></span> 													
                                                </td>
                                            </tr>";
                                            } else {
                                                echo "<tr>
                                                <td title='id_setor'>" . $dados->id_setor . "</td>
                                                <td title='descricao'>" . $dados->descricao . "</td>
                                                <td title='ordem' class='editavel'>" . $dados->ordem . "</td>
                                               <td>     
                                                    <span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_setor . ");'></span> 													
                                                </td>
                                            </tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="../ajax/setor/ativa_setor.js"></script>
<?php require_once "./actionRodape.php"; ?>
    </body>
</html>